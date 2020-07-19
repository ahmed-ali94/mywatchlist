<?php 
session_start();
if (isset($_GET["movie_id"]) && isset($_GET["list_id"]) && isset($_SESSION["user_Id"]))
{
    $movie_id = $_GET["movie_id"];
    $list_id = $_GET["list_id"];
    $encode_id = urlencode($movie_id);

    // Request movie  with TMDB using cURL
    
    $url = "https://api.themoviedb.org/3/movie/$encode_id?api_key=c2293950755394e5c99ca7f387cb2c2d"; 

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    $curl_result = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    $json_output = json_decode($curl_result);

    curl_close($ch);
    
    require_once "sql_settings.php";


    $conn = mysqli_connect($host,$user,$pwd,$sql_db);

    if (!$conn)
    {
        exit("Database Connection error");
    }
    else
    {
        $table = "movies";
    }

    $query = "INSERT INTO $table (List_id,Movie,Poster_path) VALUES ('$list_id','$movie_id','$json_output->poster_path');";

    $result = mysqli_query($conn,$query);

    if (!$result)
    {
        echo "Problem with the query";
    }

    else
    {
        echo "<p>$json_output->title has been added!</p>";
        mysqli_close($conn);
    }
}

else
{
    echo "404";
}
?>