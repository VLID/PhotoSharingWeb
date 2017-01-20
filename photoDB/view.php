<!-- 
 * Final Project
 * Yaoxi Luo
 * COMP-3704
 * photoDB/view.php
 * June 5,2016
-->

<?php
	require '../session.php';
	require '../mysql_config.php';
	require '../app/function.php';
	$boolPosition = false;
	$boolTime = false;
	$boolDesc = false;

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["username"]) && isset($_POST['submit']) && isset($_POST['edit'])){
	$pid = $_POST['pid'];
	$changeDesc = clean_input($_POST['edit']);
	$sql = "UPDATE ".$login_session." SET de = '$changeDesc' WHERE pid = '$pid'";
	mysqli_query($con,$sql);
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["username"]) && isset($_POST['pid']) && isset($_POST['submit'])){
	$pid = $_POST['pid'];
	$sql = "SELECT * FROM ".$login_session." WHERE pid = '$pid'";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	if($row['lat'] == "" && $row['lon'] == ""){
		$latitude = "";
		$boolPosition = false;
	}else{
		$latitude = $row['lat'];
		$longitude = $row['lon'];
		$boolPosition = true;
	}

	if($row['t'] == ""){
		$photoTime = "";
		$boolTime = false;
	}else{
		$photoTime = $row['t'];
		$boolTime = true;
	}

	if($row['de'] == ""){
		$desc = "";
		$boolDesc = false;
	}else{
		$desc = $row['de'];
		$boolDesc = true;
	}
	$url = $row['image'];
}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>View Image</title>
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
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>
	<div class="mainPage">
		<div class="box">
			<div class="viewInfo">
				<img class="viewP img-thumbnail" src="data:image;base64,<?php echo $url; ?>">
				<?php
					if($boolTime){
						echo "<h5>Date and time of photo taken: ".$photoTime."</h5>";
					}
					if($boolDesc && !$boolPosition){
						echo "<h5>Description: ".$desc."</h5>";
					}elseif (!$boolDesc && !$boolPosition) {
						echo "<h5>No Description</h5>";
					}
					echo "<br><form action=\"view.php\" method=\"post\" enctype=\"multipart/form-data\" class=\"form-inline\">";
					echo "<input type=\"text\" name=\"pid\" value=\"".$pid."\" hidden>";
					echo "<input class=\"form-control\" type=\"text\" name=\"edit\" placeholder=\"Change Description\">";
					echo "<input class=\"btn btn-primary\" type=\"submit\" name=\"submit\" value=\"Change Description\">";
					echo "</form>";
					echo "<br><form action=\"gallery.php\" method=\"post\" id=\"uploadForm\" enctype=\"multipart/form-data\" class=\"form-inline\">";
					echo "<input type=\"text\" name=\"delete\" value=\"".$pid."\" hidden>";
					echo "<input class=\"btn btn-danger\" type=\"submit\" name=\"submit\" value=\"Delete Image\">";
					echo "</form>";
					if($boolPosition){
						echo "<hr/><h4>Location: </h4>";
						echo "<div id=\"googleMap\"></div>";
					}
				?>
				<br><br>
				<a href="gallery.php"><button type="button" class="btn btn-info">Back to the Gallery</button></a>
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
	<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBAsyW1ll5hg6G3aM49_DAs7PiMNUAAzLI&sensor=false"></script>
    <script>
		var myCenter=new google.maps.LatLng(<?php echo $latitude.",".$longitude; ?>);

		function initialize()
		{
			var mapProp = {
			  center:myCenter,
			  zoom:16,
			  disableDefaultUI:true,
			  mapTypeId:google.maps.MapTypeId.ROADMAP
		};

		var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);

		var marker=new google.maps.Marker({
		  position:myCenter,
		  animation:google.maps.MarkerImage
		  });

		marker.setMap(map);

		var infowindow = new google.maps.InfoWindow({
		  content:"<?php echo $desc; ?>"
		  });

		infowindow.open(map,marker);

		var homeControlDiv = document.createElement('div');
		var homeControl = new HomeControl(homeControlDiv, map);
		map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);
		}

		function HomeControl(controlDiv, map) {
		  controlDiv.style.padding = '5px';
		  var controlUI = document.createElement('div');
		  controlUI.style.backgroundColor = 'lightblue';
		  controlUI.style.border='1px solid';
		  controlUI.style.cursor = 'pointer';
		  controlUI.style.textAlign = 'center';
		  controlUI.title = 'Set map to my place';
		  controlDiv.appendChild(controlUI);
		  var controlText = document.createElement('div');
		  controlText.style.fontFamily='Arial,sans-serif';
		  controlText.style.fontSize='12px';
		  controlText.style.paddingLeft = '4px';
		  controlText.style.paddingRight = '4px';
		  controlText.innerHTML = '<b>Back to the Marker<b>'
		  controlUI.appendChild(controlText);

		  google.maps.event.addDomListener(controlUI, 'click', function() {
		    map.setCenter(myCenter);
		    map.setZoom(16);
		  });
		}

		google.maps.event.addDomListener(window, 'load', initialize);
	</script>
</body>
</html>