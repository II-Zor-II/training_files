<?php 
//Define Absolute path 
require_once("../../includes/initialize.php");

if(!$session->is_logged_in()){
	redirect_to("login.php");
}

if(empty($_GET['id'])){
	$session->message("No photo ID was provided.");
	redirect_to('index.php');
}

$photo = Photograph::find_by_id($_GET['id']);
if($photo && $photo->destroy()){
	$session->message("The photo {$photo->file_name} was deleted.");
	$session->message("The photo was deleted.");
	redirect_to('list_photos.php');
}else{
	$session->message("The photo could not be deleted.");
	redirect_to('list_photos.php');
}


?>
<?php if(isset($database)){$database->close_connection();} ?>