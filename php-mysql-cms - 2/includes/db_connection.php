<?php
define("DB_HOST","localhost");
define("DB_USER","root"); // change database username here
define("DB_PASS",""); // change database password here
define("DB_NAME","custom_cms");

$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
// Test if connection occured.
if(mysqli_connect_errno()){
	die("Database connection failed: ".
			mysqli_connect_error().
			" (".mysqli_connect_errno().")"
		);
}

?>