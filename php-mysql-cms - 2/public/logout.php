<?php 

//Define Absolute path 
define("MAIN_DIR",dirname(getcwd())."/");

require_once(MAIN_DIR."includes/session.php");
require_once(MAIN_DIR."includes/functions.php"); 


session_start();
$_SESSION = array();
if(isset($_COOKIE[session_name()])){
	setcookie(session_name(), null, time()-42000,'/');
}
session_destroy();
redirect_to("login.php");

?>