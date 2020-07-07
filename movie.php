
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


$url = "https://api.themoviedb.org/3/movie/$encode_id?api_key=c2293950755394e5c99ca7f387cb2c2d&append_to_response=videos,images,credits"; 

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
<script> $(document).ready(function() {
    $('.jumbotron').css('background-image','linear-gradient(0deg, rgba(123,34,195,.5) 0%, rgba(253,187,45,.5) 100%),url(<?php 

    $background_image = $json_output->images->backdrops[0]->file_path;

    echo "http://image.tmdb.org/t/p/original/$background_image";
    
    
    
    
    ?>)');


}); </script>
<?php include("inc/header.inc") ?>


<div class="container-fluid mt-4">
<div class="row">
<div class="col-sm-4 d-flex justify-content-end">
<img class="rounded-lg rounded-bottom border border-warning" src='http://image.tmdb.org/t/p/w342/<?php echo $json_output->poster_path ?>'>

</div>
<div class="col-sm-4 text-center">


<h5 class="display-4"><?php echo $json_output->title  ?></h5>

<p class="mt-4"><?php  
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
<h4 class="h4 text-left"> Overview</h4>
</div>
<div class="p-2 ">
<a href="https://www.imdb.com/title/<?php echo $json_output->imdb_id; ?>"><i class="fab fa-imdb"></i></a>
</div>
<div class="p-2 my-auto ">
<p class="russo-one pt-2"><?php echo "$json_output->vote_average / 10"; ?></p>
</div>
</div>
</div>



<p class="text-left text-wrap lead mt-4"> <?php  echo $json_output->overview ?> </p>
</div>
<div class="col-4">
<div class="embed-responsive embed-responsive-16by9 ">
<iframe class="embed-responsive-item  " src='https://www.youtube.com/embed/<?php  

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
</div>

<div class="container-fluid mt-4">
    <div class="row ">
        <div class="col mr-0">

            <div class="card text-center bg-dark">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#credits">Credits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Box Office</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">Tech Specs</a>
                    </li>
                    </ul>
                </div>
                <div id="credits" class="card-body text-left">
                    <h5 class="card-title text-center">Credits</h5>
                    <hr class="hr w-25">
                    <div id="directors">
                    <h6 class='text-left russo-one'>Directors</h6>
                        
                        <?php

                        foreach ($json_output->credits->crew as $crew)
                        {
                            if ($crew->job == "Director")
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
            </div>

        </div>
        <div class="col-sm-6 mr-0">

            <div id="movie_images" class="carousel slide shadow-lg w-50" data-ride="carousel">
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
                <img class="d-block w-100 img-fluid" src="http://image.tmdb.org/t/p/w780/<?php echo $json_output->images->backdrops[0]->file_path; ?>">
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
                ."<img class='d-block w-100 img-fluid' src='http://image.tmdb.org/t/p/w780//" .$json_output->images->backdrops[$i]->file_path. "'></div>";
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
</div>
</div>

</body>
</html>