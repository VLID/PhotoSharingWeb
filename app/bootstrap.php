<!-- 
 * Final Project
 * Yaoxi Luo
 * COMP-3704
 * app/bootstrap.php
 * June 5,2016
-->

<?php
require 'mysql_config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!isset($_POST['_token']) || ($_POST['_token'] != $_SESSION['_token'])) {
		die('Invalid CSRF token');
	}
}

?>