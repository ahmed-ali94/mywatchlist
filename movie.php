<?php
require_once ("sanatise_data.php");

if (isset($_GET["movie"]) ) // checks if we have got the movie id form the url
{

$id =  sanatise_input($_GET["movie"]); // sanatise the input

if (!preg_match("/^[0-9]{1,6}$/", $id)) // if the id does not match 6 digits
{
    header("Location: home.html");
}

$encode_id = urlencode($id);



// Request movie  with TMDB using cURL


$url = "https://api.themoviedb.org/3/movie/$encode_id?api_key=c2293950755394e5c99ca7f387cb2c2d"; 

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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $json_output->title; ?></title>
</head>
<body>
<?php

// details

echo "<div>\n"
. "<h4>$json_output->title</h4>\n" // title
."<img src='http://image.tmdb.org/t/p/w185$json_output->poster_path' >\n" // poster
."<div>\n" // the div for the movie info next to the poster
."<h5>Synopsis</h5>\n" // synopsis
."<p> $json_output->overview</p>";


if (empty($json_output->genres) == false) // if genre array exists display all genres
{
    $lastgenre = end($json_output->genres);
    

    echo "<h5>Genre</h5>\n" // genre"
    ."<p>";

    foreach($json_output->genres as $genre) // display all genres
    {
        

    if ($genre != $lastgenre)
    {
            echo "$genre->name, ";
    }
    
    if ($genre == $lastgenre) // need to find the last genre to put a . rather than ,
    {
        echo "$genre->name.";
    }

    }

    echo "</p>";


}

echo "<h5>Release Date</h5>\n" // Release date
."<p> $json_output->release_date</p>\n"
."<h5>Language</h5>\n" // Language
."<p> $json_output->original_language</p>\n"
."<h5>Popularity</h5>\n" // Popularity
."<p> $json_output->popularity</p>";


if (empty($json_output->imdb_id) == false) // check for imdb id
{
    echo "<h5>IMDB</h5>\n" // IMDB
    ."<a href='https://www.imdb.com/title/$json_output->imdb_id'>IMDB</a>\n"
    ."<h5>Rating</h5>\n"
    ."<p>$json_output->vote_average</p>\n"
    ."<h5>Voters</h5>\n"
    ."<p>$json_output->vote_count</p>";
    
}




echo "</div>\n"
."</div>";



?>















    
</body>
</html>