<!-- 
 * Final Project
 * Yaoxi Luo
 * COMP-3704
 * photoDB/gallery.php
 * June 5,2016
-->

<?php
	require '../mysql_config.php';
	require '../session.php';

	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && isset($_POST['delete'])){
		$pid = $_POST['delete'];
		$sql = "DELETE FROM ".$login_session." WHERE pid = '$pid'";
		mysqli_query($con, $sql);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Gallery</title>
	<link rel="stylesheet" type="text/css" href="../main.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/hint.css/2.3.0/hint.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<section>
	<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Upload</a></li>
            <li class="active"><a href="gallery.php">Gallery</a></li>
            <li><a href="tripMap.php">Trip Map</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>
	<div class="mainPage">
		<div class="box">
			<h1>Gallery</h1>
			<div id="gallery">
			<?php
			if ($_SESSION['username']) {
				$user = $_SESSION["username"];
				$sql = "SELECT * FROM ".$user;
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
					echo "You haven't upload any images. Please upload your images first.";
				}
			}
			?>
			</div>
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