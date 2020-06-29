<?php 

if ( isset($_POST["email"]) && !empty($_POST["email"]) AND isset($_POST["pwd"]) && !empty($_POST["pwd"]) )
{
    require_once "sql_settings.php";

    require_once "sanatise_data.php";


    // Performing server side validation 



    $errMsg = "";

    $email = sanatise_input($_POST["email"]);
    $password = sanatise_input($_POST["pwd"]);

    if ( $email == "")
    {
        $errMsg = "<p style='color:red;'>Server: Email field is empty.</p>";
        echo $errMsg;

    }
    elseif (preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email) == false)
    {
        $errMsg = "<p style='color:red;'>Server: Invalid format for email address.</p>";
        echo $errMsg;

    }

    if ($password == "")
    {
        $errMsg = "<p style='color:red;'>Server: Password field is empty.</p>";
        echo $errMsg;

    }

    // When there are no errors with the recieved inputs , begin authentication.

    if ($errMsg == "")
    {

    

        $conn = mysqli_connect($host,$user,$pwd,$sql_db);

        if (!$conn)
        {
            exit("Database Connection error");
        }
        else
        {
            $table = "users";
        }

        // sanatise the data to remove any quotes incase of sql injection

        $email = mysqli_real_escape_string($conn,$email);
        $password = mysqli_real_escape_string($conn,$password);

        // The query to check if the user has activated thier account.

        $active_acc_query = "SELECT Email, Active FROM $table WHERE Email='$email' AND Active='1'";
        $active_acc_result = mysqli_query($conn,$active_acc_query);

        if (!$active_acc_result)
        {
            mysqli_close($conn);
            exit("Error with the query");
        }
        else
        {
            $active_acc_rows = mysqli_num_rows($active_acc_result);

            if ($active_acc_rows == 1)
            {
                // free the memory assocaited with the check active account result
                mysqli_free_result($active_acc_result);

                // The query to auth login

                $query = "SELECT Email, Pwd, Active FROM $table WHERE Email='$email' AND Pwd='$password' AND Active='1'";

                $result = mysqli_query($conn,$query);

                if (!$result)
                {
                    mysqli_close($conn);
                    exit("Error with the query");
                }
                else
                {
                    $rows = mysqli_num_rows($result);

                    if ($rows == 1)
                    {
                        // free the memory assocaited with the login result
                        mysqli_free_result($result);
                        mysqli_close($conn);
                        echo "logged in";
                    }

                    else
                    {
                        mysqli_close($conn);
                        exit("Wrong email or passowrd");
                    }
                }

            }
            else
            {
                mysqli_close($conn);
                exit("<p style='color:red;'>Please activate your account. The activation link has been already sent to your email address.</p>");
            }
        }
    }
}

else
{
    header("Location: home.html");
}


























?>