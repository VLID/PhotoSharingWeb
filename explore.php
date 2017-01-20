<!--  
 * Final Project
 * Yaoxi Luo
 * COMP-3704
 * explore.php
 * June 5,2016
-->

<?php
	require 'mysql_config.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Explore</title>
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
            <li class="active"><a href="explore.php">Explore</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="signup.php">Sign Up</a></li>
            <li><a href="login.php">Login</a></li>
          </ul>
        </div>
      </div>
    </nav>
<section>
	<div class="mainPage">
      <div class="box">
      	<h1>Exploring Amazing Photos</h1>
        <p>In this page, you can explore the shared photos from other users. Also, you can share your photos here. If you want to share yours, when you upload your photo, please choose "Yes" in "Share" option, then your photos will appear in this page. Enjoy!</p>
      </div>
      <div class="box" id="gallery">
      		<?php
				$sql = "SELECT * FROM sharedImage";
				$result = mysqli_query($con, $sql);
				if($result->num_rows > 0){
					while($row = mysqli_fetch_array($result)){
						echo "<div class=\"photosDiv\">";
						echo "<table id=\"g\">";
						echo "<tr>";
						echo "<span class=\"hint--left hint--info\" aria-label=\"".$row['de']."\"><img class=\"photo img-circle\" src=\"data:image;base64,".$row['image']."\"></span>";
						echo "</tr>";
						echo "<tr>";
						echo "<form action=\"view.php\" method=\"post\" enctype=\"multipart/form-data\">";
						echo "<input type=\"text\" name=\"pid\" value=\"".$row['pid']."\" hidden>";
						echo "<input class=\"btn btn-info\" type=\"submit\" value=\"View\" name=\"submit\">";
						echo "</form>";
						echo "</tr>";
						echo "</table>";
						echo "</div>";
					}
				}else{
					echo "There is no shared photo.";
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