<?php 
//Define Absolute path 
require_once("../../includes/initialize.php");

if(!$session->is_logged_in()){
	redirect_to("login.php");
}
?>
		<?php include_layout_template('admin_header.php'); ?>

<?php 
//$user = new User();
//$user->username = "johnsmith";
//$user->password = "abcd12345";	
//$user->first_name = "johnsmith";	
//$user->last_name = "johnsmith";	
//$user->create();

/*$user = User::find_by_id(2);
$user->password = "12345wxyz";
$user->save();*/
/*
$user = User::find_by_id(2);
$user->delete();*/
//$first_path = dirname(MAIN_DIR);
//echo $first_path."\\public\\".;
?>
		<?php include_layout_template('admin_footer.php'); ?>