<!-- 
 * Final Project
 * Yaoxi Luo
 * COMP-3704
 * photoDB/logout.php
 * June 5,2016
-->

<?php
	require '../session.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Logout</title>
    <link rel="stylesheet" type="text/css" href="../main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<section>
	<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Upload</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="tripMap.php">Trip Map</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>
	<div class="mainPage">
		<div class="box">
			<h1>See you <?php echo "<span>".$login_session."</span>"; ?> !</h1>
			<br>
	      	<p>Considering the security, please click on "Logout my account" button below before you leave the website. Thank you for understanding!</p>
	      	<br><br>
	      	<form action="../logout.php" method="post">
				<input class="btn btn-danger" type="submit" value="Logout my account"></input>
				<input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>"></input>
			</form>
		</div>
	</div>
	<br><br>
	<footer>
       <div class="text-center">
           &copy; 2016 Copyright. All Rights Reserved By Vince Luo
       </div>
   	</footer>
</section>
</body>
</html>