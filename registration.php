<?php

require_once "sanatise_data.php";

if ( isset($_POST["username"]) && isset($_POST["pwd"]) && isset($_POST["c_pwd"]) && isset($_POST["dob"]) && isset($_POST["email"]) )
{
    $username = sanatise_input($_POST["username"]);
    $password = sanatise_input($_POST["pwd"]);
    $c_pwd = sanatise_input($_POST["c_pwd"]);
    $dob = sanatise_input($_POST["dob"]);
    $email = sanatise_input($_POST["email"]);
    $hash = md5( rand(0,1000) );
    $created = date("Y/m/d");



    $errMsg = "";


    // usrname

    if ($username == "")
    {
        $errMsg = "<p style='color:red;'>Username field is empty.  </p>";
        echo $errMsg;
    }

    elseif (preg_match("/^[a-zA-Z0-9.\-_$@*!]{3,15}$/", $username) == false)
    {
        $errMsg = "<p style='color:red;'>Usernames must be 3 to 15 characters. No spaces or commas. </p>";
        echo $errMsg;
        
    }

    // pwd

    if ($password == "")
    {
        $errMsg = "<p style='color:red;'>Password field is empty.  </p>";
        echo $errMsg;

    }

    elseif (preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/", $password) == false)
    {
        $errMsg = "<p style='color:red;'>Password must be between 6 to 20 characters which contain at least one numeric digit, one uppercase and one lowercase letter. </p>";
        echo $errMsg;
        
    }

    if ($c_pwd == "")
    {
        $errMsg = "<p style='color:red;'>Confirm Password field is empty. </p>";
        echo $errMsg;

    }

    elseif (strcmp($password,$c_pwd) != 0)
    {
        $errMsg = "<p style='color:red;'>Passwords dont match.</p>";
        echo $errMsg;

    }

    // dob

    if ($dob == "")
    {
        $errMsg = "<p style='color:red;'>Please enter the date of birth.</p>";
        echo $errMsg;

    }

    // email

    if ($email == "")
    {
        $errMsg = "<p style='color:red;'>Email cannot be empty.</p>";
        echo $errMsg;
    }

    elseif (preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email) == false)
    {
        $errMsg = "<p style='color:red;'>Invalid email address.</p>";
        echo $errMsg;

    }

    if ($errMsg == "")
    {
        // connect to database

        require_once "sql_settings.php";
        
        $conn = mysqli_connect($host,$user,$pwd,$sql_db);

        if (!$conn)
        {
            echo "<p style='color:red;'>Database connection error</p>";
        }
        else
        {
            $sql_table = "users";
        }

        // check if email exists in database

        $check_email_query = "SELECT Email FROM $sql_table WHERE Email = '$email' ";
        $result_email = mysqli_query($conn, $check_email_query);
        $email_rows = mysqli_num_rows($result_email);

        // check if username exists in database

        $check_username_query = "SELECT Username FROM $sql_table WHERE Username = '$username' ";
        $result_username = mysqli_query($conn,$check_username_query);
        $username_rows = mysqli_num_rows($result_username);

        if ($email_rows == 1)
        {
            echo "<p style='color:red;'>Account with this email already exists!. Please login or enter a new email.</p>";
            mysqli_free_result($result_email);
            mysqli_close($conn);
        }

        elseif ($username_rows == 1)
        {
            echo "<p style='color:red;'>Username exists</p>";
            mysqli_free_result($result_username);
            mysqli_close($conn);
        }

        else
        {
            $query = "INSERT INTO $sql_table (Username,Email,Pwd,Dob,Hash_activation,Created) VALUES ('$username','$email','$password','$dob','$hash','$created')";

            $result = mysqli_query($conn, $query);

            if (!$result)
            {
            echo "<p style='color:red;'>Error with query</p>";
            }

            else
            {

            mysqli_close($conn); // close SQL connection.

            echo 200;

            // send email for activation

            
            $to = "somebody@example.com";
            $subject = "Movie Watchlist Account Activation";
            $txt = "
            <html>
            <body>
            Dear $username, Thanks for creating an account with Movie Watchlist. The final step of the account creation process is to activate your account. <br />
            <h4>Account details</h4>
            ------------------- <br />
            <strong>Username:</strong> $username <br />
            <strong>Password:</strong> $password <br />
            ------------------- <br />
            
            Please use the link below to activate your account. <br />
            <a href='activation.php?email=$email&hash=$hash'>Activate</a> <br />
            </body>
            </html>";
            $headers = "MIME-Version: 1.0" . "\r\n"; // ensureing that the message can be formatted using html tags 
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .=  "From: webmaster@example.com" . "\r\n";
            mail($to, $subject, $txt, $headers,"-r me@example.com");

            }

        }
    }
}

?>