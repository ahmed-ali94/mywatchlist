<header>
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-gold">
        <a class="navbar-brand main-font" href="home.php">Movie Watchlist</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse order-3 dual-collapse2" id="navbarNavAltMarkup">
          <ul class="navbar-nav ml-auto">
            <!-- Button trigger modal -->
            <li class="nav-item  mr-5  ">
            <div class="form-inline ">
              <input class="form-control mr-sm-2" id="search" name="search" placeholder="Search">
              <input type="button" class="btn btn-outline-light my-2 my-sm-0" value="Search" id="button" onclick="search()">
          </div>
            </li>
            <?php
            if (isset($_SESSION["user_Id"]))
            {
              echo "<div class='btn-group mr-3'>\n"
              ."<button type='button' class='btn btn-warning'>".$_SESSION["Username"]."</button>\n"
              ."<button type='button' class='btn btn-warning dropdown-toggle dropdown-toggle-split' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>\n"
              ."<span class='sr-only'>Toggle Dropdown</span>\n"
              ."</button>\n"
              ."<div class='dropdown-menu'>\n"
              ."<a href='movielist.php' id='movielist_button' class='btn btn-primary dropdown-item'>Watch List</a>\n"
              ."<div class='dropdown-divider'></div>\n"
              ."<button type='button' id='logout' class='btn btn-primary dropdown-item' onclick='logout()'>Logout</button>\n"
              ."</div>\n"
              ."</div>";
            }
            else
            {
              echo  "<li class='nav-item mr-2'>\n"
                    ."<button type='button' id='login' class='btn btn-primary text-purple' data-toggle='modal' data-target='#loginModalCenter'>Login</button>\n"
                    ."</li>\n"
                    ."<li class='nav-item'>\n"
                    ."<button type='button' id='signup' class='btn btn-primary text-purple' data-toggle='modal' data-target='#signupModalCenter'>Signup</button>\n"
                    ."</li>";
            }
            ?>
          </ul>
        </div>
      </nav>
    </header>
<!-- Modal -->
  <!--Login-->
  <div class="modal fade" id="loginModalCenter" tabindex="-1" role="dialog" aria-labelledby="loginModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header modal-background-purple russo-one">
          <h5 class="modal-title" id="loginModalCenterTitle">Login</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div id="login_modal_body" class="modal-body text-purple">
            <form>
                <div class="form-group">
                  <label for="loginInputEmail1">Email address</label>
                  <input type="email" class="form-control" id="login_email" aria-describedby="emailHelp">
                </div>
                <small id="login_email_msg" >
                </small>
                <div class="form-group">
                  <label for="loginPassword">Password</label>
                  <input type="password" class="form-control" id="login_password">
                </div>
                <small id="login_pwd_msg" >
                </small>
              </form>
              <div id ="login_message"></div>
        </div>
        <div class="modal-footer modal-background-purple russo-one">
          <button type="button" class="btn btn-primary text-purple" onclick="login()">Login</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Signup-->
<div class="modal fade " id="signupModalCenter" tabindex="-1" role="dialog" aria-labelledby="signupModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header modal-background-purple russo-one">
        <h5 class="modal-title" id="signupModalCenterTitle">Register</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-purple" id="sign_up_body">
        <form class="needs-validation" novalidate>
          <div class="form-group">
          <label for="sign_pwd ">Username</label>
          <label class="sr-only" for="inlineFormInputGroup">Username</label>
          <div class="input-group mb-2">
          <div class="input-group-prepend">
          <div class="input-group-text">@</div>
          </div>
          <input type="text" class="form-control" id="username" placeholder="Username" aria-describedby="usernamehelp" required>
          </div>
          <small id="usernamehelp" class="text-muted">
            Usernames must be 3 to 15 characters, no spaces or commas.
          </small>
          </div>
          <small id="username_msg" >
          </small>
          
          
          <div class="form-group">
            <label for="sign_pwd">Password</label>
            <input type="password" class="form-control" id="sign_pwd" aria-describedby="pwdHelp" required>
            <div id="pwd_invalid" class="invalid-feedback">
            </div>
            <small id="pwdHelp" class="text-muted">
              Password must be between 6 to 20 characters which contain at least one numeric digit, one uppercase and one lowercase letter.
            </small>
            
          </div>
          <small id="pwd_msg" >
          </small>
          <div class="form-group">
            <label for="confirm_pwd">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_pwd" required>
          </div>
          <small id="confirm_pwd_msg" >
          </small>
          <div class="form-group ">
            <label for="dob">Date of Birth</label>
            <input type="date" class="form-control" id="dob" placeholder="DOB">
          </div>
          <small id="dob_msg" >
          </small>
        <div class="form-group">
          <label for="sign_email">Email address</label>
          <input type="email" class="form-control" id="sign_email" aria-describedby="emailHelp">
          <small id="emailHelp" class="text-muted">
            This email address will be used to login.
          </small>
        </div>
        <small id="email_msg" >
        </small>
        </form>

        <div id="server_response">

        </div>
        
         

      
      </div>
      <div class="modal-footer modal-background-purple">
        <button type="button" class="btn btn-primary text-purple" onclick="signup_validation()">Register</button>
      </div>
    </div>
  </div>
</div>