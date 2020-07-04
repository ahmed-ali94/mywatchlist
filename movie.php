
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


$url = "https://api.themoviedb.org/3/movie/$encode_id?api_key=c2293950755394e5c99ca7f387cb2c2d&append_to_response=videos,images"; 

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


<div class="container-fluid">
<div class="row">
<div class="col-sm-4 d-flex justify-content-end">
<img class="rounded-lg rounded-bottom border border-warning" src='http://image.tmdb.org/t/p/w342/<?php echo $json_output->poster_path ?>'>
</div>
<div class="col-sm-4 text-center">
<h5 class="display-4"><?php echo $json_output->title  ?></h5>
<p class="mt-4"><?php  
echo "$json_output->release_date\n"
."| ";
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
        echo "$genre->name | ";
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

<p class="text-left lead mt-4"> <?php  echo $json_output->overview ?> </p>
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
</body>
</html>