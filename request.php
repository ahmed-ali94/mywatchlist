<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<?php include("inc/head.inc") ?>
<body>
<?php include("inc/header.inc") ?>

<div class="container">
      <div id="results">
        <div id="rows" class="row">

        
    

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
    echo "<div class='col-sm-3 text-center mb-4'>\n"
    ."<div id='$movie->id'>\n"
    ."<p class=' russo-one'> $movie->title </p>\n"
    ."<a href='movie.php?id=$movie->id'>\n"
    ."<div class='img-container'>\n"
    ."<img class='img-thumbnail' src='http://image.tmdb.org/t/p/w185$movie->poster_path' data-toggle='modal' data-target='#movie_info'>\n"
    ."<div class='overlay'>\n"
    ."<span>\n"
    ."<p>Rating:<br /> $movie->vote_average / 10</p>";    // overlay content 

    if ($movie->vote_average >= 9 && $movie->vote_average <= 9.9 )  // adding emojis based on movie rating
    {
        echo "<i class='fas fa-grin-stars'></i>";
    }
    
    if ($movie->vote_average >= 8 && $movie->vote_average <= 8.9 )  
    {
        echo "<i class='fas fa-grin-hearts'></i>";
    }

    if ($movie->vote_average >= 7 && $movie->vote_average <=7.9 )
    {
        echo "<i class='fas fa-grin'></i>";
    }

    if ($movie->vote_average >= 6 && $movie->vote_average <=6.9 )
    {
        echo "<i class='fas fa-meh'></i>";
    }

    if ($movie->vote_average < 6 )
    {
        echo "<i class='fas fa-flushed'></i>";
    }



    echo "<button type='button' id='movie_details' class='btn btn-info text-purple' onclick='movie_info($movie->id)'>Details</button>\n"
    ."<button type='button' id='add_movie' class='btn btn-success btn-sm mt-4 text-purple' onclick='add_movie($movie->id)'>Add</button>\n"
    ."</span>\n"
    ."<span id='response'> </span>\n"
    ."</div>\n"
    ."</div>\n"
    ."</div>\n"
    ."</a>\n"
    ."</div>";
    }
    
}

// PAGNIATION

$total_pages = $json_output->total_pages;

if ($total_pages > 1)
{
    echo "<div class='container'>\n"
    ."<nav  aria-label='Page navigation example'>\n"
    ."<ul class='pagination justify-content-center'>\n"
    ."<li class='page-item disabled'>\n"
    ."<a class='page-link' href='#' tabindex='-1'>Previous</a>\n"
    . "</li>";

    for($i = max(1, $cur_page - 5); $i <= min($cur_page + 5, $total_pages); $i++)
    {
        echo "<li class='page-item'><a class='page-link' href='request.php?search=$encode_search&page=$i'>$i</a></li>";
    }

    $next_page = $cur_page + 1;

    if ( $next_page < $i)
    {

    echo "<a class='page-link' href='request.php?search=$encode_search&page=$next_page'>Next</a>\n"
    ."</li>\n"
    ."</ul>\n"
    ."</nav>\n"
    ."</div>";
    }

    else 
    {
    echo "</li>\n"
    ."</ul>\n"
    ."</nav>\n"
    ."</div>";
        
    }

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
</div>
 </div>
</div>
<?php include("inc/footer.inc")?> 
</body>

</html>