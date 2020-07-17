<?php
session_start(); 
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

    $result = mysqli_query($conn,$query);

    if (!$result)
    {
        echo "Error with SQL query";
        mysqli_free_result($result);
        mysqli_close($conn);
    }

    else
    {
        $row = mysqli_num_rows($result);

        if ($row >= 1 )
        {

        }
        else
        {
            echo "No ";
        }



    }

}

else
{
    header("Location: home.php");
}

?>  
<!DOCTYPE html>
<html lang="en">
<?php include("inc/head.inc")?>
<body>
<?php include("inc/header.inc")?>

<div class="modal fade " id="addlistModalCenter" tabindex="-1" role="dialog" aria-labelledby="addlistModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark russo-one">
          <h5 class="modal-title" id="addlistModalCenterTitle">Create a list</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body bg-secondary " id="addlist_body">
          <form novalidate>
            <div class="form-group">
              <label for="list_title" class="russo-one">List Title</label>
              <input type="text" class="form-control" id="list_title" required>
            </div>
            <small id="list_title_msg">
            </small>
            <div class="form-group">
              <label for="list_description" class="russo-one">Description</label>
              <input type="text" class="form-control" id="list_description" required>
            </div>
            <small id="list_description_msg" >
            </small>
          </form>
  
          <div id="addlist_server_response">
  
          </div>
        
        </div>
        <div class="modal-footer bg-dark">
          <button type="button" class="btn btn-success text-purple" onclick="add_list()">Add</button>
        </div>
      </div>
    </div>
  </div>

  <div class="container ">
    <div class="card bg-dark">
        <div class="card-header">
            <h5 class="mx-auto">Your watchlists</h5>
        </div>
        <div class="card-body">
            
        </div>
      </div>

    </div>

  </div>
















        <div id="sticky_bottom" class="fixed-bottom">
            <div class="card bg-dark">
                <div class="card-header d-flex flex-row">
                    <h5 class="mx-auto">Create your own movie watch list!</h5>
                    <i id="close" class="fas fa-times-circle ml-auto"></i>
                </div>
                <div class="card-body">
                    <button type="button" id="open_addlist_modal" class="btn btn-outline-success" data-toggle="modal" data-target="#addlistModalCenter">Create List</button>
                </div>
              </div>
        
            </div>
<script>
    $("#close").click(function() {

        $("#sticky_bottom").hide("slow");



    });
</script>


</body>
</html>