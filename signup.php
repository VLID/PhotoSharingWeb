<!--  
 * Final Project
 * Yaoxi Luo
 * COMP-3704
 * signup.php
 * June 5,2016
-->

<?php
	require 'mysql_config.php';
   	require 'app/function.php';
	$error = "";
	ini_set('display_errors', 'Off');
   	header("X-XSS-Protection: 1");
   	$success = false;

   	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

      if (!isset($_POST['newusername']) && !isset($_POST['newpassword']) && !isset($_POST['repassword'])) {
         die();
      }

      	$sql = "SELECT * FROM userlist";
		$result = mysqli_query($con, $sql);
		$exist = false;
		if($result->num_rows > 0){
			while($row = mysqli_fetch_array($result)){
				if ($row['username'] == clean_input($_POST["newusername"])){
					$exist = true;
				}
			}
		}
		if($exist){
			$error = "This username has already existed. Please change another one.";
			$success = false;
		}else{
			$user = clean_input($_POST['newusername']);
			if (!preg_match("/^[a-zA-Z0-9]{6,}$/", $user)) {
				$error = "The username doesn't meet the requests, please try again.";
				$success = false;
			}else{
				if($_POST['newpassword'] != $_POST['repassword']){
	      			$error = "Password doesn't match, try again!";
	      			$success = false;
	      		}else{
	      			$submittedPassword = clean_input($_POST['newpassword']);
	      			if (!preg_match("/(?=[a-zA-Z0-9]*?[A-Z])(?=[a-zA-Z0-9]*?[a-z])(?=[a-zA-Z0-9]*?[0-9])[a-zA-Z0-9]{6,}$/", $submittedPassword)) {
	      				$error = "Your password is not allowed. Please follow the requests.";
	      				$success = false;
	      			}else{
	      				$submittedPassword = md5($submittedPassword);
	      				$active = 0;
		        		$sql = "INSERT INTO userlist (username, password, active) VALUES "."('$user','$submittedPassword','$active')";
		        		mysqli_query($con,$sql);
		        		$success = true;
		        		$sql1 = "CREATE TABLE ".$user." ( pid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, image LONGBLOB NOT NULL, lat VARCHAR(255) NOT NULL, lon VARCHAR(255) NOT NULL, de VARCHAR(255) NOT NULL, t VARCHAR(255) NOT NULL )";
		        		mysqli_query($con, $sql1);
	      			}
	      		}
			}
		}
   	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Sign up</title>
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
            <li class="active"><a href="signup.php">Sign Up</a></li>
            <li><a href="login.php">Login</a></li>
          </ul>
        </div>
      </div>
    </nav>
<section>
	<div class="mainPage">
      <div class="box">
      	<h1>Sign Up</h1>
      	<h5>Hi! In this page, you can create a new account to use the "STPA".</h5>
        	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="form-inline" >
        		<div class="form-group">
				    <label for="newusername" class="sr-only">Username</label>
				    <span class="hint--top" aria-label="At least 6 characters with A-Z, a-z, 0-9"><input type = "text" id="newusername" name = "newusername" class = "item_line form-control" placeholder="New Username" /></span>
				</div>
				<div class="form-group">
				    <label for="newpassword" class="sr-only">Password</label>
				    <span class="hint--bottom" aria-label="At least 6 characters, at least 1 upper letter, 1 lower letter, and 1 number"><input type = "password" id="newpassword" name = "newpassword" class = "item_line form-control" AUTOCOMPLETE='OFF' placeholder="New Password" /></span>
				</div>
				<div class="form-group">
				    <label for="repassword" class="sr-only">Retype Password</label>
				    <span class="hint--top" aria-label="Type your new password again!"><input type = "password" id="repassword" name = "repassword" class = "item_line form-control" AUTOCOMPLETE='OFF' placeholder="Retype Password" /></span>
				</div>
                <input class="btn btn-primary" type="submit" name="submit" id="submit" value="Submit" />
         	</form>
        	<?php 
        		if($success){
        			echo "<script>if (confirm(\"You created an account successfully, please login.\")) { window.location = \"login.php\"; };</script>";
        		}else{
        			echo "<div class=\"e\">";
        			echo $error;
        			echo "</div>";
        		}
        	?>
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