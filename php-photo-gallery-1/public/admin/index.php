<?php 
//Define Absolute path 
require_once("../../includes/initialize.php");

if(!$session->is_logged_in()){
	redirect_to("login.php");
}
?>
		<?php include_layout_template('admin_header.php'); ?>

		<div>
			<h2>Menu</h2>
		</div>
		<?php echo output_message($message); ?>
		<ul>
			<li><a href="list_photos.php">View Gallery</a></li>
			<li><a href="log_out.php">Logout</a></li>
		</ul>
		<?php include_layout_template('admin_footer.php'); ?>