<?php 
//Define Absolute path
define("MAIN_DIR",dirname(getcwd())."/");
// include all reusable templates
require_once(MAIN_DIR."includes/session.php");
require_once(MAIN_DIR."includes/db_connection.php");
require_once(MAIN_DIR."includes/functions.php");
include(MAIN_DIR."includes/layouts/header.php") 
?>
<div id="Main">

</div>
<?php include(MAIN_DIR."includes/layouts/footer.php"); ?>