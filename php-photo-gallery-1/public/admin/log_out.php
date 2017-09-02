<?php 

//Define Absolute path 
require_once("../../includes/initialize.php");

session_start();
$_SESSION = array();
if(isset($_COOKIE[session_name()])){
	setcookie(session_name(), null, time()-42000,'/');
}
session_destroy();
redirect_to("login.php");

?>