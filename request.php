<?php 

require_once ("sanatise_data.php");

if (isset($_GET["search"]) && strlen(trim($_GET['search'])) > 1 ) // checks if user has entred search text and contains no whitespace in the beggining
{
    if (isset($_GET["page"])) 
    {  
    $cur_page  = $_GET["page"];
    $cur_page  = sanatise_input($cur_page);
    }  
    else 
    {  
    $cur_page = 1;  
    };   

    $search = $_GET["search"];

    $search = sanatise_input($search);

$encode_search = urlencode($search);

$encode_page = urlencode($cur_page);

// Request movie search with TMDB using cURL


$url = "https://api.themoviedb.org/3/search/movie?api_key=c2293950755394e5c99ca7f387cb2c2d&query=$encode_search&page=$encode_page";

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

$json_output = json_decode($result);

curl_close($ch);

if ($json_output->total_results != 0)
{

foreach($json_output->results as $movie)
{
    if (empty($movie->poster_path) == false) // ignore movies from json array that dont have a poster img
    {
    echo "<div id='$movie->id' class='Movie'>\n"
    ."<p> Title : $movie->title </p>\n"
    ."<img src='http://image.tmdb.org/t/p/w185$movie->poster_path' onmouseover='display_info($movie->id)'><br />\n"
    ."<div id='info'></div>\n"
    ."</div>";
    }
    
}

// PAGNIATION

$total_pages = $json_output->total_pages;


echo "<div>";

for($i = max(1, $cur_page - 5); $i <= min($cur_page + 5, $total_pages); $i++)
{
    echo "<a href='request.php?search=$encode_search&page=$i' >$i</a>";
}


}

else
{
    echo "<p>No movies found!</p>";

}


}

else
{
    echo "<p>Please enter a movie<p>";
}



























?>