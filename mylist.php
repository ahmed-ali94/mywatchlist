<?php 
session_start();

// create token

// Request token  with TMDB using cURL


$url = "https://api.themoviedb.org/3/authentication/token/new?api_key=c2293950755394e5c99ca7f387cb2c2d";

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

$json_output = json_decode($result);

curl_close($ch);

$request_token = $json_output->request_token;











?>