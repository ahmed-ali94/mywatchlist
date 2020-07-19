<?php
session_start();
if (isset($_SESSION["user_Id"]) && isset($_GET["list_id"]))
{
    $user_id = $_SESSION["user_Id"];
    $list_id = $_GET["list_id"];


    require_once "sql_settings.php";

          $conn = mysqli_connect($host,$user,$pwd,$sql_db);

          if (!$conn)
          {
              exit("Database connection error");

          }

          else
          {
              $table = "movies";
          }


          $query = "SELECT Movie,Poster_path FROM $table WHERE List_id = $list_id;";

          // get list id when seraching for the movies.


          $result = mysqli_query($conn,$query);

          if (!$result)
          {
              echo "Error with SQL query";
              mysqli_free_result($result);
              mysqli_close($conn);
          }

          else
          {
            $num_row = mysqli_num_rows($result);

            if ($num_row >= 1 )
            {
                while ($row = mysqli_fetch_assoc($result))
                {

                echo "<div class='col-auto'><a href='movie.php?id=".$row['Movie']."'><img class='mb-3 img-fluid similar' src='http://image.tmdb.org/t/p/w154/".$row['Poster_path']. "'alt='" .$row['Movie']. "'></a></div>";

                }

            }

            else
            {
                echo "No movies added yet!";
            }

            mysqli_free_result($result);
            mysqli_close($conn);
          }
}
else
{
    header("Location: home.php");
}
?>