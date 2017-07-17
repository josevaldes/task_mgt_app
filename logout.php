<?php
   require "util.php";
   session_start();

   if(isset($_SESSION['uname']) )
   {
     $_SESSION = array();
     setcookie(session_name(), '', time() - 2592000, '/');
     session_destroy();
   }
   header("Location: login.php");
   die();
 ?>
