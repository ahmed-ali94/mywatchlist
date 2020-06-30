const xhr = new XMLHttpRequest();

function enter_key()
{
    var input = document.getElementById("search");

    input.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) // if enter key is pressed
        {
            search();
            event.preventDefault();
            input.blur();

        } 
    })
}
window.onload = enter_key;



function search()
{
    var search = document.getElementById("search").value;

    xhr.open("GET", "request.php?search=" + encodeURIComponent(search), true);

    xhr.onreadystatechange = function()
    {
        if (xhr.readyState == 4 && xhr.status == 200 )
        {
            var result = xhr.responseText;

            if (result == "")
            {
                document.getElementById("results").innerHTML = "No results";
            }
            else
            {
                document.getElementById("results").innerHTML = result;
            }

        }

    }

    xhr.send(null);
}

function clicked_page(page)
{
    var search = document.getElementById("search").value;

    

    xhr.open("GET", "request.php?search=" + encodeURIComponent(search) + "&page=" + encodeURIComponent(page), true);

    xhr.onreadystatechange = function()
    {
        if (xhr.readyState == 4 && xhr.status == 200 )
        {
            var result = xhr.responseText;

            
                document.getElementById("results").innerHTML = result;
            

        }

    }

    xhr.send(null);
}

function display_info(id)
{
    xhr.open("GET","modal_info.php?id=" + encodeURIComponent(id), true);

    xhr.onreadystatechange = function() 
    {
        if (xhr.readyState == 4 && xhr.status == 200)
        {
            var result = xhr.responseText;

            document.getElementById("info").innerHTML = result;

        }
    }

    xhr.send(null);
    
}

function login()
{
    // validate logn input

    var email = document.getElementById("login_email").value;
    var pwd = document.getElementById("login_password").value;

    var errMsg = "";


    if ( email == "")
    {
        errMsg = "<p style='color:red;'>Please enter your email.</p>";
        document.getElementById("login_email_msg").innerHTML = errMsg;

    }
    else if (email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/) == null) // https://www.w3resource.com/javascript/form/email-validation.php
    {
        errMsg = "<p style='color:red;'>Invalid format for email address. </p>";
        document.getElementById("login_email_msg").innerHTML = errMsg;
    }

    else
    {
        document.getElementById("login_email_msg").innerHTML = "";
    }

    if (pwd == "")
    {
        errMsg = "<p style='color:red;'>Please enter your password.</p>";
        document.getElementById("login_pwd_msg").innerHTML = errMsg;
    }

    else
    {
        document.getElementById("login_pwd_msg").innerHTML = "";

    }

    if (errMsg == "") // if there are no errors 
    {
        xhr.open("POST","authenticate.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("email=" + encodeURIComponent(email) + "&pwd=" + encodeURIComponent(pwd));

        xhr.onreadystatechange = function() 
        {
            if (xhr.readyState == 4 && xhr.status == 200)
            {
                var result = xhr.responseText;

                if ( result == 200 )
                {
                    // change header links 

                    


                }
                else
                {
                    document.getElementById("login_message").innerHTML = result;

                }

            }
        }

        }

}

function signup_validation()
{
    var errMsg = "";

    var username = document.getElementById("username").value;
    var password = document.getElementById("sign_pwd").value;
    var c_password = document.getElementById("confirm_pwd").value; // confirm password
    var dob = document.getElementById("dob").value;
    var email = document.getElementById("sign_email").value;

    // Username

    if (username == "")
    {
        errMsg = "<p style='color:red;'>Username cannot be empty</p>";
        document.getElementById("username_msg").innerHTML = errMsg;
        
    }

    else if (username.match(/^[a-zA-Z0-9.\-_$@*!]{3,15}$/) == null) // returns null if no match is found.
    {
        errMsg = "<p style='color:red;'>Usernames must be 3 to 15 characters. No spaces or commas.  </p>";
        document.getElementById("username_msg").innerHTML = errMsg;

    }

    else
    {
        document.getElementById("username_msg").innerHTML = "";
        

    }

    // Password

    if (password == "")
    {
        errMsg = "<p style='color:red;'>Password cannot be empty.</p>";
        document.getElementById("pwd_msg").innerHTML = errMsg;
        
    }

    else if (password.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/) == null) // returns null if no match is found.
    {
        errMsg = "<p style='color:red;'>Password must be between 6 to 20 characters which contain at least one numeric digit, one uppercase and one lowercase letter.</p>";
        document.getElementById("pwd_msg").innerHTML = errMsg;

    }
    else
    {
        
        document.getElementById("pwd_msg").innerHTML = "";

    }

    if (c_password == "")
    {
        errMsg = "<p style='color:red;'>Please confirm password.</p>";
        document.getElementById("confirm_pwd_msg").innerHTML = errMsg;

    }

    else if (c_password != password) // if confirm passowrd does not equal password.
    {
        errMsg = "<p style='color:red;'>Passwords dont match.</p>";
        document.getElementById("confirm_pwd_msg").innerHTML = errMsg;

    }
    else
    {
        
        document.getElementById("confirm_pwd_msg").innerHTML = "";

    }

    // DOB


    if (dob == "")
    {
        errMsg = "<p style='color:red;'>Please enter the date of birth.</p>";
        document.getElementById("dob_msg").innerHTML = errMsg;
    }


    else
    {
        document.getElementById("dob_msg").innerHTML = "";
    }

    // Email

    if (email == "")
    {
        errMsg = "<p style='color:red;'>Email cannot be empty.</p>";
        document.getElementById("email_msg").innerHTML = errMsg;

    }
    else if (email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/) == null) // https://www.w3resource.com/javascript/form/email-validation.php
    {
        errMsg = "<p style='color:red;'>Invalid email address.</p>";
        document.getElementById("email_msg").innerHTML = errMsg;

    }

    else
    {
        document.getElementById("email_msg").innerHTML = "";
    }

    if ( errMsg == "") // if there are no errors.
    {

        xhr.open("POST","registration.php",true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("username=" + encodeURIComponent(username) + "&pwd=" + encodeURIComponent(password) + "&c_pwd=" + encodeURIComponent(c_password) + "&dob=" + encodeURIComponent(dob) + "&email=" + encodeURIComponent(email));

        xhr.onreadystatechange = function()
        {
            if (xhr.readyState == 4 && xhr.status == 200)
            {
                var result = xhr.responseText;

                if (result == 200)
                {
                    
                    document.getElementById("server_response").innerHTML = "<p style='color:green;'>Account created! Please check email for account activation steps.</p>";
                }

                else
                {
                    document.getElementById("server_response").innerHTML = result;

                }

                


            }
        }



    }
}