<!-- 
 * Final Project
 * Yaoxi Luo
 * COMP-3704
 * app/function.php
 * June 5,2016
-->

<?php

function f($value) {
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function clean_input($string) {
    $string = trim($string);
    $string = stripslashes($string);
    $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    return $string;
}

function gps($coordinate, $hemisphere) {
	for ($i = 0; $i < 3; $i++) {
		$part = explode('/', $coordinate[$i]);
		if (count($part) == 1) {
	  		$coordinate[$i] = $part[0];
		} else if (count($part) == 2) {
	  		$coordinate[$i] = floatval($part[0])/floatval($part[1]);
		} else {
	    	$coordinate[$i] = 0;
	  	}
	}
	list($degrees, $minutes, $seconds) = $coordinate;
	$sign = ($hemisphere == 'W' || $hemisphere == 'S') ? -1 : 1;
	return $sign * ($degrees + $minutes/60 + $seconds/3600);
}

?>