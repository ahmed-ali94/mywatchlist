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

    if (search == "")
    {
        var searchbar = document.getElementById("search");

        document.querySelector("search").style.border = "2px solid red";
    }

    else
    {
        window.location.href = "request.php?search=" + encodeURIComponent(search);

    }

    
}



function add_movie(movie_id,list_id)
{
    xhr.open("GET","add_movie.php?movie_id=" + encodeURIComponent(movie_id) + "&list_id=" + encodeURIComponent(list_id)  , true);
        
        xhr.onreadystatechange = function() 
        {
            if (xhr.readyState == 4 && xhr.status == 200)
            {
                var result = xhr.responseText;

                if (result == "404")
                {
                    $('#loginModalCenter').modal();

                }
                else
                {
                    document.getElementById("response").innerHTML = result;

                    $("#response").fadeIn(3000);
                    $("#response").fadeOut(3000);
                }
            }
        }

        xhr.send(null);
    
}


function add_list()
{
    var title = document.getElementById("list_title").value;
    var description = document.getElementById("list_description").value;

    var errMsg = "";

    if (title == "")
    {
        errMsg = "<p style='color:red;'>Please enter the list title.</p>";
        document.getElementById("list_title_msg").innerHTML = errMsg;
    }

    else if(title.match(/^[a-zA-Z0-9 ]{0,50}$/) == null) // Matches Alphanumeric characters with space alone.
    {
        errMsg = "<ul style='color:red;'><li>Only numbers and letters allowed, no special characters.</li><li>Max 50 characters allowed.</li></ul>";
        document.getElementById("list_title_msg").innerHTML = errMsg;

    }
    else
    {
        document.getElementById("list_title_msg").innerHTML = "";
    }

    if (description == "")
    {
        errMsg = "<p style='color:red;'>Please enter a description.</p>";
        document.getElementById("list_description_msg").innerHTML = errMsg;

    }
    else if (description.match(/^[a-zA-Z0-9 ]{0,250}$/) == null) // Matches Alphanumeric characters with space alone.
    {
        errMsg = "<ul style='color:red;'><li>Only numbers and letters allowed, no special characters.</li><li>Max 250 characters allowed.</li></ul>";
        document.getElementById("list_description_msg").innerHTML = errMsg;

    }

    else
    {
        document.getElementById("list_description_msg").innerHTML = "";


    }

    if (errMsg == "")
    {
        xhr.open("POST","add_list.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("title=" + encodeURIComponent(title) + "&desc=" + encodeURIComponent(description));
        xhr.onreadystatechange = function() 
        {
            if (xhr.readyState == 4 && xhr.status == 200)
            {
                var result = xhr.responseText;

                if (result == "Added")
                {
                    $("#addlistModalCenter").modal("hide");
                    location.reload();
                }
                else
                {
                    document.getElementById("addlist_server_response").innerHTML = result;
                }
               
                
            }
        }

    }


    

}

function show_list_movies(list_id)
{
    xhr.open("GET","get_movielist.php?list_id=" + encodeURIComponent(list_id), true);
        


        xhr.onreadystatechange = function() 
        {
            if (xhr.readyState == 4 && xhr.status == 200)
            {
                var result = xhr.responseText;

                document.getElementById(list_id).innerHTML = result;

                $("#response").fadeIn(3000);
            }
        }

        xhr.send(null);



}




function login()
{
    // validate login input

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

                    $("#loginModalCenter").modal("hide");
                    location.reload();


                    


                }
                else
                {
                    document.getElementById("login_message").innerHTML = result;

                }

            }
        }

        }

}


function logout()
{
    xhr.open("GET","logout.php", true);
        
        xhr.onreadystatechange = function() 
        {
            if (xhr.readyState == 4 && xhr.status == 200)
            {
                var result = xhr.responseText;

                if (result == 200)
                {
                    location.reload();
                }

            }
        }

        xhr.send(null);

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