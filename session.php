<!--  
 * Final Project
 * Yaoxi Luo
 * COMP-3704
 * session.php
 * June 5,2016
-->

<?php
   session_start();

   if(!isset($_SESSION['username'])){
      header("location:index.php");
   }
   
   $login_session = $_SESSION['username'];
   $_SESSION['_token'] = bin2hex(openssl_random_pseudo_bytes(16));
   
?>