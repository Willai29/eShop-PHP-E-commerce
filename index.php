<?php
/* Main page with two forms: sign up and log in */
require 'db.php';
session_start();
<<<<<<< HEAD

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        require 'login.php';
    } elseif (isset($_POST['register'])) {
        require 'register.php';
    }
}
=======
>>>>>>> adeb3f71bee11c739d84340e67ba4b07b3b73e95
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sign-Up/Login</title>
  <?php include 'css/css.html'; ?>
</head>
<<<<<<< HEAD
=======

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['login'])) { //user logging in

        require 'login.php';

    }

    elseif (isset($_POST['register'])) { //user registering

        require 'register.php';

    }
}
?>
>>>>>>> adeb3f71bee11c739d84340e67ba4b07b3b73e95
<body>
  <div class="form">

      <ul class="tab-group">
        <li class="tab"><a href="#signup">Sign Up</a></li>
        <li class="tab active"><a href="#login">Log In</a></li>
      </ul>

      <div class="tab-content">

         <div id="login">
          <h1>Welcome Back!</h1>

          <form action="index.php" method="post" autocomplete="off">

            <div class="field-wrap" style="height:35px">
<<<<<<< HEAD
              <label>
                Email Address<span class="req">*</span>
              </label>
              <input type="email" required autocomplete="off" name="email"/>
            </div>

            <div class="field-wrap">
              <label>
                Password<span class="req">*</span>
              </label>
              <input type="password" required autocomplete="off" name="password"/>
            </div>

            <button type="submit" class="button button-block" name="login">Log In</button>
=======
            <label>
              Email Address<span class="req">*</span>
            </label>
            <input type="email" required autocomplete="off" name="email"/>
          </div>

          <div class="field-wrap">
            <label>
              Password<span class="req">*</span>
            </label>
            <input type="password" required autocomplete="off" name="password"/>
          </div>

          <button class="button button-block" name="login" />Log In</button>
>>>>>>> adeb3f71bee11c739d84340e67ba4b07b3b73e95

          </form>

        </div>

        <div id="signup">
          <h1>Sign Up for Free</h1>

          <form action="index.php" method="post" autocomplete="off">

<<<<<<< HEAD
            <div class="top-row">
              <div class="field-wrap">
                <label>
                  First Name<span class="req">*</span>
                </label>
                <input type="text" required autocomplete="off" name="firstname" />
              </div>

              <div class="field-wrap">
                <label>
                  Last Name<span class="req">*</span>
                </label>
                <input type="text" required autocomplete="off" name="lastname" />
              </div>
=======
          <div class="top-row">
            <div class="field-wrap">
              <label>
                First Name<span class="req">*</span>
              </label>
              <input type="text" required autocomplete="off" name='firstname' />
>>>>>>> adeb3f71bee11c739d84340e67ba4b07b3b73e95
            </div>

            <div class="field-wrap">
              <label>
<<<<<<< HEAD
                Email Address<span class="req">*</span>
              </label>
              <input type="email" required autocomplete="off" name="email" />
            </div>

            <div class="field-wrap">
              <label>
                Address<span class="req">*</span>
              </label>
              <input type="text" required autocomplete="off" name="address" />
            </div>

            <div class="field-wrap">
              <label>
                Phone Number<span class="req">*</span>
              </label>
              <input type="tel" required autocomplete="off" name="phone" />
            </div>

            <div class="field-wrap">
              <label>
                Set A Password<span class="req">*</span>
              </label>
              <input type="password" required autocomplete="off" name="password"/>
            </div>

            <button type="submit" class="button button-block" name="register">Register</button>
=======
                Last Name<span class="req">*</span>
              </label>
              <input type="text"required autocomplete="off" name='lastname' />
            </div>
          </div>

          <div class="field-wrap">
            <label>
              Email Address<span class="req">*</span>
            </label>
            <input type="email"required autocomplete="off" name='email' />
          </div>

          <div class="field-wrap">
            <label>
              Address<span class="req">*</span>
            </label>
            <input type="text"required autocomplete="off" name='address' />
          </div>

          <div class="field-wrap">
            <label>
              Phone Number<span class="req">*</span>
            </label>
            <input type="tel"required autocomplete="off" name='phone' />
          </div>

          <div class="field-wrap">
            <label>
              Set A Password<span class="req">*</span>
            </label>
            <input type="password"required autocomplete="off" name='password'/>
          </div>

          <button type="submit" class="button button-block" name="register" />Register</button>
>>>>>>> adeb3f71bee11c739d84340e67ba4b07b3b73e95

          </form>

        </div>

      </div><!-- tab-content -->

<<<<<<< HEAD
  </div><!-- /form -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="js/index.js"></script>

</body>
</html>
=======
</div> <!-- /form -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/index.js"></script>

</body>
</html>
>>>>>>> adeb3f71bee11c739d84340e67ba4b07b3b73e95
