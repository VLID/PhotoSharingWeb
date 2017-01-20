<!-- 
 * Final Project
 * Yaoxi Luo
 * COMP-3704
 * photoDB/index.php
 * June 5,2016
-->

<?php
	require '../mysql_config.php';
	require '../session.php';
	require '../app/function.php';

	$bool = false;
	$errors = array();

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && isset($_SESSION["username"])){
	$user = $_SESSION["username"];
	$desc = clean_input($_POST['desc']);
	$file_type = $_FILES['image']['type'];
	$file_size = $_FILES['image']['size'];
	$file_tmp_name = $_FILES['image']['tmp_name'];
	$fileSHA1 = sha1_file($file_tmp_name);
	$file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

	if (!getimagesize($file_tmp_name)) {
		$errors[]="Please select an image file first.";
	}

	//Check the executable files
	$blackList = array(".php", ".php3", ".php4", ".phtml", ".pl", ".py", ".jsp", ".asp", ".htm", ".shtml", ".sh", ".cgi");
    foreach ($blackList as $file) {
    	if (preg_match("/$file\$/i", $_FILES['image']['name'])) {
    		$errors[]="No executable file.";
    	}
    }

    //Check the image type
	$extensions = array("jpeg", "jpg", "png", "gif");
	if(!in_array($file_ext,$extensions)){
         $errors[]="Only JPEG, JPG, PNG, GIF are allowed.";
    }

    if($file_size > ( 3 * 1024 * 1024)) {
         $errors[]='File size must be less than 3MB.';
    }

    //Check duplicate uploads
    $sql = "SELECT * FROM ".$user;
	$result = mysqli_query($con, $sql);
	if($result->num_rows > 0){
		while($row = mysqli_fetch_array($result)){
			if ($row['name'] == $fileSHA1){
				$errors[]="You've already uploaded this image, please upload another one.";
			}
		}
	}

	if(empty($errors)==true){
		$gps = false;
		$time = false;
		$exif = exif_read_data($file_tmp_name);
		foreach ($exif as $key => $section) {
		    if ($key == "GPSLatitude" || $key == "GPSLongitude") {
		    	$gps = true;
		    }
		    if ($key == "DateTime") {
    			$time = true;
    		}
		}
		if ($time){
			$timeValue = $exif[exif_tagname(306)];
			$dateValue = substr(str_replace(":", "-", $timeValue), 0, 10);
			$tValue = substr($timeValue, strpos($timeValue, " "));
			$timeValue = $dateValue." ".$tValue;
		}
		if ($gps == true){
			$latitude = gps($exif["GPSLatitude"], $exif['GPSLatitudeRef']);
			$longitude = gps($exif["GPSLongitude"], $exif['GPSLongitudeRef']);
		}else{
			$latitude = "";
			$longitude = "";
		}

		$image = addslashes($file_tmp_name);
		$name = addslashes($_FILES['image']['name']);
		$image = file_get_contents($image);
		$image = base64_encode($image);

		$sql = "INSERT INTO ".$user." VALUES ('', '$fileSHA1', '$image', '$latitude', '$longitude', '$desc', '$timeValue')";
		mysqli_query($con, $sql);

		if($_POST['share'] == "yes"){
			$bool_dupl = false;
			$sql = "SELECT * FROM sharedImage";
			$result = mysqli_query($con, $sql);
			if($result->num_rows > 0){
				while($row = mysqli_fetch_array($result)){
					if ($fileSHA1 == $row['name']) {
						$bool_dupl = true;
					}
				}
				if (!$bool_dupl) {
					$sql = "INSERT INTO sharedImage VALUES ('', '$fileSHA1', '$image', '$latitude', '$longitude', '$desc', '$timeValue', '$user')";
					mysqli_query($con, $sql);
				}
			}else{
				$sql = "INSERT INTO sharedImage VALUES ('', '$fileSHA1', '$image', '$latitude', '$longitude', '$desc', '$timeValue', '$user')";
				mysqli_query($con, $sql);
			}
		}
		$bool = true;
	}else{
		$bool = false;
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Upload Your Images</title>
    <link rel="stylesheet" type="text/css" href="../main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body>
<section>
	<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Upload</a></li>
            <li><a href="gallery.php">Gallery</a></li>
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
	      	<h1>Welcome back, <?php echo "<span>".$login_session."</span>"; ?></h1>
	      	<p>You successfully logged in your personal photo space.</p>
	      	<p>Before you close this page, please click on "Logout" in navigation bar. Thank you!</p>
      	</div>
		<div class="box">
			<h2>Start to upload!</h2>
			<p>This website allows you to upload your images taken by your smartphone and you can view your images in gallery section. You can look at your trip track and help you remember every place you went.</p>
			<div id="uploadStatus">
				<?php
					if($bool == true){
						echo "<p class=\"message s\">Upload Successful!</p>";
						echo "<p>To view your images, please <a href=\"gallery.php\">Click Here</a></p>";
					}else{
						echo "<p class=\"message w\">";
						for($x = 0; $x < count($errors); $x++){
							echo $errors[$x];
							echo "<br>";
						}
						echo "</p>";
					}
				?>
			</div>
			<hr/>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" id="uploadForm" enctype="multipart/form-data">
					<div class="form-group">
					    <label for="image">Select image to upload: </label>
					    <input type="file" name="image" id="image">
					    <p class="help-block">Image size should be less than <span>3MB</span>, and only <span>JPEG, JPG, PNG, GIF</span> are allowed.</p>
					</div>
					<div class="form-group">
					    <label for="desc">Image Description: </label>
					    <input class="form-control" type="text" name="desc" id="desc" size="30"></input>
					</div>
					<div class="form-group">
						<label for="share1 share2">Do you want to share this photo? </label>
						<div class="radio-inline">
							<label>
								<input id="share1" type="radio" name="share" value="yes">Yes</input>
							</label>
						</div>
						<div class="radio-inline">
							<label>
								<input id="share2" type="radio" name="share" value="no" checked>No</input>
							</label>
						</div>
					</div>
					<input class="btn btn-primary" type="submit" value="Upload Image" name="submit">
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