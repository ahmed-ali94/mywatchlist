<?php
session_start();

if (isset($_SESSION["user_Id"]) && isset($_POST["title"]) && isset($_POST["desc"]))
{
    require_once "sanatise_data.php";
    require_once "sql_settings.php";

    // validate inputs

    $user_id = $_SESSION["user_Id"];
    $list_title = sanatise_input($_POST["title"]);
    $list_desc = sanatise_input($_POST["desc"]);
    $errMsg = "";

    if ( $list_title == "")
    {
        $errMsg = "<p style='color:red;'>Server: List title field is empty.</p>";
        echo $errMsg;

    }
    elseif (preg_match("/^[a-zA-Z0-9 ]{0,50}$/", $list_title) == false)
    {
        $errMsg = "<ul style='color:red;'><li>Only numbers and letters allowed, no special characters.</li><li>Max 50 characters allowed.</li></ul>";
        echo $errMsg;

    }

    if ( $list_desc == "")
    {
        $errMsg = "<p style='color:red;'>Server: List description field is empty.</p>";
        echo $errMsg;

    }
    elseif (preg_match("/^[a-zA-Z0-9 ]{0,250}$/", $list_title) == false)
    {
        $errMsg = "<ul style='color:red;'><li>Only numbers and letters allowed, no special characters.</li><li>Max 350 characters allowed.</li></ul>";
        echo $errMsg;

    }

    if ($errMsg == "")
    {
        $conn = mysqli_connect($host,$user,$pwd,$sql_db);

        if (!$conn)
        {
            exit("Database connection error");

        }

        else
        {
            $table = "watch_list";
        }

        // sanatise the data to remove any quotes in case of sql injection

        $list_title = mysqli_real_escape_string($conn,$list_title);
        $list_desc = mysqli_real_escape_string($conn,$list_desc);

        $query = "INSERT INTO $table (List_title,List_desc,user_Id) VALUES ('$list_title','$list_desc','$user_id');";

        $result = mysqli_query($conn,$query);

        if (!$result)
        {
            echo "Problem with SQL Query". mysqli_error($conn)."";
            mysqli_close($conn);
        }
        else
        {
            echo "Added";
            mysqli_close($conn);
        }

    }
}

else
{
    header("Location: home.php");
}
?>