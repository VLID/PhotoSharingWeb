<!--  
 * Final Project
 * Yaoxi Luo
 * COMP-3704
 * mysql_config.php
 * June 5,2016
-->

<?php
	$servername = "localhost";
	$username = "yaoxiluo";
	$password = "Dfgste778";
	$dbname = "yaoxiluo";
	$con = mysqli_connect($servername,$username,$password,$dbname);

   	$db = new PDO('mysql:host=localhost;dbname=yaoxiluo', 'yaoxiluo','Dfgste778');
?>