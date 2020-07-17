<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<?php include("inc/head.inc")?>
<body>
<?php include("inc/header.inc")?>
    <!--Hero-->

    <div class="jumbotron jumbotron-fluid ">
      <div class="container">
        <h1 class="display-4 main-font">Movie Watchlist</h1>
        <p class="lead">Search movies and add them to your watchlist!</p>
        <hr class="my-4">
        <div class="form ">
          <div class="form-group row ">
              <i class="fas fa-search col-sm-2"></i>
            
              <input type="search" class="form-control-lg col-10 russo-one " id="search" name="search" placeholder="Movie title..">
            
          </div>
          <input type="button" class="btn btn-lg btn-primary text-purple mt-4" value="Search" id="button" onclick="search()">

        </div>
        
      </div>
    </div>



    

  
    <div class="container">
      <div id="results">
        <div id="rows" class="row">

        </div>
      
        


      </div>

    </div>
    





    
</body>
</html>