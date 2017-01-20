
<!--  
 * Final Project
 * Yaoxi Luo
 * COMP-3704
 * login.php
 * June 5,2016
-->


<?php
   require 'mysql_config.php';
   require 'app/function.php';
   session_start();
   $error = "";
   ini_set('display_errors', 'Off');
   header("X-XSS-Protection: 1");

   if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

      if (!isset($_POST['username'])) {
         die();
      }

      $sql = "SELECT * FROM userlist";
      $result = mysqli_query($con, $sql);
      $u_exist = false;
      $p_exist = false;
      $username = clean_input($_POST["username"]);
      $password = md5(clean_input($_POST['password']));
      if($result->num_rows > 0){
         while($row = mysqli_fetch_array($result)){
            if ($row['username'] == $username){
               $u_exist = true;
            }
            if ($row['password'] == $password){
               $p_exist = true;
            }
         }
      }
      if(!$u_exist){
         $error = "This username doesn't exist. <br><span style=\"color:#000\">please go to <a href=\"signup.php\">Sign up</a> page to create a new account.</span>";
      }else{
         if(!$p_exist){
            $error = "Password invalid. Please try again.";
         }else{
            $user = $db->prepare("SELECT * FROM userlist WHERE username = :username");
            $user->execute(['username' => $_POST['username']]);
            $user = $user->fetchObject();
            setcookie('UID', md5(f($user->username)), time()+(60*60*24*1), '/', NULL, NULL, true);
            $_SESSION['UID'] = $_COOKIE['UID'];
            $_SESSION['username'] = $username;
            $active = $db->prepare("UPDATE userlist SET active = 1 WHERE username = :username");
            $active->execute([
               'UID' => $_SESSION['UID'],
            ]);
            header("location: photoDB/index.php");
         }
      }
   }
?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
   <title>Login</title>
   <link rel="stylesheet" type="text/css" href="main.css">
   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/hint.css/2.3.0/hint.min.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
   <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="index.html">STPA <span style="font-size:10px">beta</span></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.html">Home</a></li>
            <li><a href="explore.php">Explore</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="signup.php">Sign Up</a></li>
            <li class="active"><a href="login.php">Login</a></li>
          </ul>
        </div>
      </div>
    </nav>
<section>
   <div class="mainPage">
      <div class="box">
         <p>Please use your username and password to login below. If you don't have an account, please click <a href="signup.php">here</a> to create a new account.</p>
      </div>
      <div class="box">
         <h1>Login</h1>
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="form-inline">
               <div class="form-group">
                  <label for="username" class="sr-only">Username</label>
                  <input id="username" type = "text" name = "username" class = "item_line form-control" placeholder="Username" />
               </div>
               <div class="form-group">
                  <label for="password" class="sr-only">Password</label>
                  <input id="password" type = "password" name = "password" class = "item_line form-control" placeholder="Password" AUTOCOMPLETE='OFF'/>
               </div>
               <input class="btn btn-primary" type="submit" name="submit" id="submit" value="Submit" />
         </form>
         <div class="e"><?php echo $error; ?></div>
      </div>
      <br><br>
   </div>
   <footer>
       <div class="text-center">
           &copy; 2016 Copyright. All Rights Reserved By Vince Luo
       </div>
   </footer>
</section>
</body>
</html>