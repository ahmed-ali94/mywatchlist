<?php 
session_start();

if (isset($_GET["id"]) && isset($_SESSION["user_Id"]))
{
    
    require_once "sql_settings.php";


    $conn = mysqli_connect($host,$user,$pwd,$sql_db);

    if (!$conn)
    {
        exit("Database Connection error");
    }
    else
    {
        $table = "watch_list";
    }

    $user_id = $_SESSION["email"];

    $movie_id = $_GET["id"];


    $query = "UPDATE $table SET Movies = Movies + '$movie_id' WHERE List_owner = '$email';";

    $result = mysqli_query($conn,$query);

    if (!$result)
    {
        echo "Problem with the query";
    }

    else
    {
        echo "Movie added";

        mysqli_close($conn);
    }





}

else
{
    echo "404";
}









?>