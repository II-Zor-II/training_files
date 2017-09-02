<?php 
//Define Absolute path 
define("MAIN_DIR",dirname(getcwd())."/");
// include all reusable templates
require_once(MAIN_DIR."includes/session.php");
require_once(MAIN_DIR."includes/db_connection.php");
require_once(MAIN_DIR."includes/functions.php");
?>


<?php 
	if(create_new_post()){
		redirect_to("admin.php");
	}else{
		echo "<h3>FAILED</h3>";
	}
?>