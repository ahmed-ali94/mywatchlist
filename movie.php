<?php session_start(); ?>
<?php
require_once ("sanatise_data.php");

if (isset($_GET["id"]) ) // checks if we have got the movie id form the url
{

$id =  sanatise_input($_GET["id"]); // sanatise the input

if (!preg_match("/^[0-9]{1,6}$/", $id)) // if the id does not match 6 digits
{
    header("Location: home.html");
}

$encode_id = urlencode($id);



// Request movie  with TMDB using cURL


$url = "https://api.themoviedb.org/3/movie/$encode_id?api_key=c2293950755394e5c99ca7f387cb2c2d&append_to_response=videos,images,credits,similar"; 

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

$json_output = json_decode($result);

curl_close($ch);

}

else // if no user id was in the url then relocate user back to home page.
{
    header("Location: home.html");

}
?>
<!DOCTYPE html>
<html  lang="en">
<?php include("inc/head.inc") ?>
<body>
<?php include("inc/header.inc") ?>

<div class="container-fluid pl-0">
<div class="row">

<div class="col-sm-8 ">

<div class="card bg-dark ">

    <div id="movie_images" class="carousel slide shadow-lg" data-ride="carousel">
                <ol class="carousel-indicators">

                <?php

                $length = count($json_output->images->backdrops);

                if ($length > 6)
                {
                    $length = 6;
                }

                for ( $i = 0; $i < $length; $i++)
                {
                    echo "<li data-target='#movie_images' data-slide-to='$i' class='active'></li>";
                }
                ?>
                </ol>
            <div class="carousel-inner ">
            <div class='carousel-item active'>
                <img class="w-100  img-fluid" src="http://image.tmdb.org/t/p/w1280/<?php echo $json_output->images->backdrops[0]->file_path; ?>">
                </div>

                <?php

                $length = count($json_output->images->backdrops);

                if ($length > 6)
                {
                $length = 6;
                }

                for ( $i = 1; $i < $length; $i++)
                {
                echo "<div class='carousel-item '>\n"
                ."<img class='w-100  img-fluid' src='http://image.tmdb.org/t/p/w1280//" .$json_output->images->backdrops[$i]->file_path. "'></div>";
                }

            ?>
            <a class="carousel-control-prev" href="#movie_images" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#movie_images" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            </div>
            </div>

  <div class="card-body">
<h5 class="display-4 text-center card-title russo-one"><?php echo $json_output->title  ?></h5>

<p class="mt-4 card-text text-center"><?php  
echo "$json_output->release_date\n"
."<sep>|</sep> ";
// add genres
if (empty($json_output->genres) == false) // if genre array exists display all genres
{
    $lastgenre = end($json_output->genres);
    

    

    foreach($json_output->genres as $genre) // display all genres
    {
        

    if ($genre != $lastgenre)
    {
            echo "$genre->name, ";
    }
    
    if ($genre == $lastgenre) // need to find the last genre to put a . rather than ,
    {
        echo "$genre->name <sep>|</sep> ";
    }

    }

    // add runtime
    $runtime = floor($json_output->runtime/60);
    $minutes = $json_output->runtime % 60;
    echo "$runtime Hr & $minutes Minutes";
}
?></p>

<hr class="hr " />
<div class="container">
<div class="d-flex ">
<div class="p-2 mr-auto">
<h4 class="h4 text-left russo-one"> Overview</h4>
</div>
<div class="btn-group btn-group-sm mr-4 dropup">
  <button type="button" class="btn  btn-light">Add</button>
  <button type="button" class="btn btn-outline-warning dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span class="sr-only">Toggle Dropdown</span>
  </button>
  <div class="dropdown-menu bg-dark">
  <?php
    // list users movie list in the drop down menu
    if (isset($_SESSION["user_Id"]))
    {
        $user_id = $_SESSION["user_Id"];
        require_once "sql_settings.php";

        $conn = mysqli_connect($host,$user,$pwd,$sql_db);

        if (!$conn)
        {
            exit("Database connection error");

        }

        else
        {
            $table = "watch_list";
        }


        $query = "SELECT * FROM $table WHERE user_Id = $user_id;";

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

            echo "<button class='dropdown-item russo-one'"."onclick='add_movie($json_output->id,".$row['List_id']. ")'>" .$row['List_title']."</button>";
            }
            mysqli_free_result($result);
            mysqli_close($conn);
            }
            else
            {
                echo "<button class='dropdown-item russo-one' href='watchlist.php'>Create a list!</a>";
            }

    } 

    }
    else
    {
        echo "<button class='dropdown-item russo-one' data-toggle='modal' data-target='#loginModalCenter'>Login</button>";

    }     
  ?>
</div>
</div>


<span id="response"></span>
<div class="p-2 ">
<a href="https://www.imdb.com/title/<?php echo $json_output->imdb_id; ?>"><i class="fab fa-imdb"></i></a>
</div>
<div class="p-2 my-auto ">
<p class="russo-one pt-2"><?php echo "$json_output->vote_average / 10"; ?></p>
</div>
</div>
<hr class="hr w-25 text-center">
</div>



<p class="text-left mt-4"> <?php  echo $json_output->overview ?> </p>
</div>
</div>
</div>
<div class="col-sm-4 mt-3">
<div class="card bg-dark">
  <div class="card-header russo-one">
    Trailer
  </div>
  <div class="card-body py-0 px-0">
<div class="embed-responsive embed-responsive-16by9">
<iframe class="embed-responsive-item" src='https://www.youtube.com/embed/<?php  

// add trailers

if (empty($json_output->videos) == false) // if video array exists display  trailer
{
    $key = $json_output->videos->results[0]->key;
    
        echo $key;
    

}

?>'allowfullscreen></iframe>
</div>
</div>
</div>

<div class="card bg-dark mt-3 ">
  <div class="card-header russo-one">
    More like this
  </div>
  <div class="card-body pb-1">
    <div class="row">
        <?php

        $similar_movies_length = $json_output->similar->results;

        if ($similar_movies_length >= 7)
        {
            $similar_movies_length= 7;  // only print 8 similar movies

            for ($i=0; $i <= $similar_movies_length; $i++)
            {
                echo "<div class='col-sm-3'><a href='movie.php?id=".$json_output->similar->results[$i]->id."'><img class='mb-3 img-fluid similar' src='http://image.tmdb.org/t/p/w154/".$json_output->similar->results[$i]->poster_path. "'alt='" .$json_output->similar->results[$i]->title. "'></a></div>";
            }

        }

        else
        {
            for ($i=0; $i <= $similar_movies_length; $i++) // if similar movies is less than 8 
            {
                echo "<div class='col-sm-2'><a href='movie.php?id=".$json_output->similar->results[$i]->id."'><img class='mb-3 img-fluid similar' src='http://image.tmdb.org/t/p/w154/".$json_output->similar->results[$i]->poster_path. "'alt='" .$json_output->similar->results[$i]->title. "'></a></div>";
            }

        }
        ?>
    </div>
  </div>
</div>

</div>
</div>
</div>

<div class="container mt-4">
            <div class="card text-center bg-dark ">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle='tab' href="#credits">Credits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle='tab' href="#box_office">Box Office</a>
                    </li>
                    </ul>
                </div>
            <div class="tab-content"> 
                <div id="credits" class="card-body text-left tab-pane show active">
                    <h6 class="card-title russo-one text-center">Credits</h6>
                    <hr class="hr w-25">
                <div class="row">
                <div class="col-sm-6">
                    <div id="directors" class='mt-4'>
                    <h6 class='text-left russo-one'>Directors</h6>
                        
                        <?php

                        foreach ($json_output->credits->crew as $crew)
                        {
                            if ($crew->job == "Director")
                            {
                                if (empty($crew->profile_path) == false ) // if there is a profile image
                                {

                                echo "<img class='border  rounded-circle'  src='http://image.tmdb.org/t/p/w45/$crew->profile_path'>\n"
                                ." <sep>|</sep> $crew->name<br/><br/>";
                                }

                                else
                                {
                                    echo "<div class='d-flex '><i  class='fas fa-user-circle'></i>\n"
                                    ."<div class='ml-1 mt-3'><sep>|</sep> $crew->name</div></div><br/><br/>";
                                }

                            }
                        }
                        
                        
                        ?>
                        
                    
                    </div>

                    <div id="producers">

                    <h6 class='text-left russo-one'>Executive Producers</h6>

                    <?php

                        foreach ($json_output->credits->crew as $crew)
                        {

                            if ($crew->job == "Executive Producer")
                            {
                                if (empty($crew->profile_path) == false ) // if there is a profile image
                                {

                                echo "<img class='border rounded-circle'  src='http://image.tmdb.org/t/p/w45/$crew->profile_path'>\n"
                                ." <sep>|</sep> $crew->name<br/><br/>";
                                }

                                else
                                {
                                    echo "<div class='d-flex '><i  class='fas fa-user-circle'></i>\n"
                                    ."<div class='ml-1 mt-3'><sep>|</sep> $crew->name</div></div><br/><br/>";
                                }
                            }
                        }

                    ?>


                    </div>

                    <div id="writers">

                    <h6 class='text-left russo-one'>Screenplay</h6>

                    <?php

                        foreach ($json_output->credits->crew as $crew)
                        {

                            if ($crew->job == "Screenplay")
                            {
                                if (empty($crew->profile_path) == false ) // if there is a profile image
                                {

                                echo "<img class='border rounded-circle'  src='http://image.tmdb.org/t/p/w45/$crew->profile_path'>\n"
                                ." <sep>|</sep> $crew->name<br/><br/>";
                                }

                                else
                                {
                                    echo "<div class='d-flex '><i  class='fas fa-user-circle'></i>\n"
                                    ."<div class='ml-1 mt-3'><sep>|</sep> $crew->name</div></div><br/><br/>";
                                }
                            }
                        }

                    ?>


                    </div>

                    <div id="composer">

                    <h6 class='text-left russo-one'>Composer</h6>

                    <?php

                        foreach ($json_output->credits->crew as $crew)
                        {

                            if ($crew->job == "Original Music Composer")
                            {
                                if (empty($crew->profile_path) == false ) // if there is a profile image
                                {

                                echo "<img class='border rounded-circle'  src='http://image.tmdb.org/t/p/w45/$crew->profile_path'>\n"
                                ." <sep>|</sep> $crew->name<br/><br/>";
                                }

                                else
                                {
                                    echo "<div class='d-flex '><i  class='fas fa-user-circle'></i>\n"
                                    ."<div class='ml-1 mt-3'><sep>|</sep> $crew->name</div></div><br/><br/>";
                                }
                            }
                        }

                    ?>


                    </div>


                    



                </div>

                <div class="col-sm-6">

                
                <div id="cast" class="mt-4">
                <h6 class="text-left russo-one">Cast</h6>
                <dl class="row">

                <?php

                        for ($i = 0 ; $i <= 6; $i++)
                        {

                                if (empty($json_output->credits->cast[$i]->profile_path) == false ) // if there is a profile image
                                {

                                echo "<dt class='col-sm-6 mt-4 '> <img class='border rounded-circle'  src='http://image.tmdb.org/t/p/w45/" .$json_output->credits->cast[$i]->profile_path."'>\n"
                                ."<sep>|</sep> <actor class='pl-2'>" . $json_output->credits->cast[$i]->name."</actor></dt> <dd class='col-sm-5 mt-4 py-4 russo-one'>" . $json_output->credits->cast[$i]->character. "</dd>";
                                }

                                else
                                {
                                    echo "<dt class='col-sm-6 mt-4'> <i  class='fas fa-user-circle'></i>\n"
                                    ."<sep>|</sep> <actor class='pl-2'>" . $json_output->credits->cast[$i]->name."</actor></dt> <dd class='col-sm-5 mt-4 py-4 russo-one'>" . $json_output->credits->cast[$i]->character. "</dd>";
                                }
                        }

                    ?>


                
                
                
                </dl>
                
                </div>
                </div>


                </div>
                </div>
                


            <div id="box_office" class="card-body text-left tab-pane fade">
                <h6 class="text-center russo-one">Box Office</h6>
                <hr class="hr w-25">
            <dl class="row mt-5">
                <dt class="col-sm-3 russo-one mt-4">Budget:</dt>
                <dd class="col-sm-9 mt-4"><?php echo "$ " .$json_output->budget. " USD" ?></dd>

                <dt class="col-sm-3 russo-one mt-4">Revenue:</dt>
                <dd class="col-sm-9 mt-4"><?php echo "$ " .$json_output->revenue. " USD" ?></dd>

                <dt class="col-sm-3 russo-one mt-4 ">Companies Involved:</dt>
                <dd class="col-sm-9 mt-4"><?php

                    foreach($json_output->production_companies as $company)
                    {
                        if (empty($company->logo_path) == false ) // if logo exists
                        {
                            echo "<img class='pr-3' src='http://image.tmdb.org/t/p/w45/$company->logo_path' alt='$company->name'>";
                        }

                        else
                        {
                            echo " <small class='text-muted pr-3'>$company->name</small>";
                        }

                        
                    }
                
                
                ?></dd>

                
            
            
            
            
            
            </dl>

        </div>
            
            
            
            
            
            
            
            
            
            
            
            </div>
            








            </div>
            </div>


            


</body>
</html>