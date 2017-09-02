<?php 
defined("MAIN_DIR") ? null : define("MAIN_DIR", dirname(__FILE__));

require_once(MAIN_DIR."/config.php");

require_once(MAIN_DIR."/functions.php");

require_once(MAIN_DIR."/session.php");

require_once(MAIN_DIR."/database.php");

require_once(MAIN_DIR."/database_object.php");

require_once(MAIN_DIR."/user.php");

require_once(MAIN_DIR."/photograph.php");

require_once(MAIN_DIR."/comments.php");
?>