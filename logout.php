<?php 
session_start();
if(isset($_SESSION["user_Id"]))
{

    session_destroy();

    echo 200;



}
else
{
    header("Location: home.php");
}










?>