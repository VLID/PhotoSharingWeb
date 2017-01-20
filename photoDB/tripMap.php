<!-- 
 * Final Project
 * Yaoxi Luo
 * COMP-3704
 * photoDB/tripMap.php
 * June 5,2016
-->

<?php
	require '../session.php';
	require '../mysql_config.php';

	$bool_empty = true;
	$lat_arr = array();
	$lon_arr = array();

	if ($_SESSION['username']) {
		$sql = "SELECT * FROM ".$login_session;
		$result = mysqli_query($con, $sql);
		if($result->num_rows > 0){
			while($row = mysqli_fetch_array($result)){
				if($row['lat'] != "" && $row['lon'] != ""){
					$lat_arr[] = $row['lat'];
					$lon_arr[] = $row['lon'];
				}
			}
			$bool_empty = false;
		}else{
			$bool_empty = true;
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Trip Map</title>
    <link rel="stylesheet" type="text/css" href="../main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>
<body onload="drop()">
<section>
	<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Upload</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li class="active"><a href="tripMap.php">Trip Map</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>
	<div class="mainPage">
		<div class="box">
			<h1>Trip Map</h1>
			<?php
				if ($bool_empty) {
					echo "<h5>Cannot find the location information of your photos.</h5>";
				}else{
					echo "<h4>You went to these places: </h4>";
					echo "<div id=\"googleMap\"></div>";
				}
			?>
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
    	var neighborhoods = [
    		<?php
    			$arrlength = count($lat_arr);
    			for ($x = 0; $x < $arrlength; $x++) {
    				if ($x != ($arrlength-1)) {
    					echo "{lat: ".$lat_arr[$x].", lng: ".$lon_arr[$x]."}, ";
    				}else{
    					echo "{lat: ".$lat_arr[$x].", lng: ".$lon_arr[$x]."} ";
    				}
    			}
    		?>
		];

		var markers = [];
		var map;

		function initMap() {
		  map = new google.maps.Map(document.getElementById('googleMap'), {
		    zoom: 8,
		    center: {lat: 39.679369, lng: -104.962603},
		    disableDefaultUI:true,
			mapTypeId:google.maps.MapTypeId.ROADMAP
		  });
		}

		function drop() {
		  clearMarkers();
		  for (var i = 0; i < neighborhoods.length; i++) {
		    addMarkerWithTimeout(neighborhoods[i], i * 500);
		  }
		}

		function addMarkerWithTimeout(position, timeout) {
		  window.setTimeout(function() {
		    markers.push(new google.maps.Marker({
		      position: position,
		      map: map,
		      animation: google.maps.Animation.DROP
		    }));
		  }, timeout);
		}

		function clearMarkers() {
		  for (var i = 0; i < markers.length; i++) {
		    markers[i].setMap(null);
		  }
		  markers = [];
		}

		google.maps.event.addDomListener(window, 'load', initMap);
	</script>
</body>
</html>