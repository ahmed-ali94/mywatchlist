<?php 

require_once "sanatise_data.php";
require_once "sql_settings.php";

if ( isset($_GET["email"]) && isset($_GET["hash"]) )
{
    $conn = mysqli_connect($host,$user,$pwd,$sql_db);

    if (!$conn)
    {
        exit("Database connection error");

    }

    else
    {
        $table = "users";
    }

    // use mysqli_real_escape_string to remove any single quotes 

    $email = mysqli_real_escape_string($conn,sanatise_input($_GET["email"]));
    $hash = mysqli_real_escape_string($conn,sanatise_input($_GET["hash"]));

    $query = "SELECT Email, Hash_activation, Active FROM $table WHERE Email = '$email' AND Hash_activation= '$hash' AND Active= '0'";

    $result = mysqli_query($conn,$query);

    if (!$result)
    {
        mysqli_close($conn);
        exit("Error with query");

    }
    else
    {
        $rows = mysqli_num_rows($result);

        if ($rows == 1)
        {
            $Activate_query = "UPDATE $table SET Active= '1' WHERE Email = '$email' AND Hash_activation= '$hash' AND Active= '0' ";

            $Activate_result = mysqli_query($conn,$Activate_query);

            if (!$Activate_result)
            {
                mysqli_close($conn);
                exit("Error with activation query");

            }
            else
            {
                mysqli_close($conn);

                echo "<p style='color:green;'>Account is now activated!</p>\n"
                ."<a href='home.html' class='btn btn-primary' data-toggle='modal' data-target='#loginModalCenter'>Login</a>";
            }



        }

        else
        {
            mysqli_close($conn);
            exit("Could not activate account. It may be already activated");
            
        }
    }





}

else
{
    header("Location: home.html");
}
?>