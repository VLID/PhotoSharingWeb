<!--  
 * Final Project
 * Yaoxi Luo
 * COMP-3704
 * logout.php
 * June 5,2016
-->

<?php

require 'app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	die();
}

$logout = $db->prepare("UPDATE userlist SET active = 0 WHERE username = :username");

$logout->execute([
	'UID' => $_SESSION['UID'],
]);

setcookie('UID', '', time()-(60*60*24*7), '/', NULL, NULL, true);
session_destroy();
header('location: index.html');

?>