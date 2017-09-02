


<?php 
//Define Absolute path
define("MAIN_DIR",dirname(getcwd())."/");
// include all reusable templates
require_once(MAIN_DIR."includes/session.php");
require_once(MAIN_DIR."includes/db_connection.php");
require_once(MAIN_DIR."includes/functions.php");
include(MAIN_DIR."includes/layouts/header.php") 
?>


<?php 
// Process Form Submission
	if(isset($_POST['submit'])){
	$field_array = array("username","password");
	check_field_presence($field_array);
		if($errors){
			foreach($errors as $error){
				echo $error;
			}
		}else{
			$username = $_POST["username"];
			$password = $_POST["password"];
			$found_admin = attempt_login($username, $password);
			if($found_admin){
				$_SESSION["admin_id"] = $found_admin["id"];
				$_SESSION["username"] = $found_admin["username"];
				redirect_to("admin.php");
			}else{
				echo "FAILED LOGIN";
			}
		}
	}
?>
<div id="login-admin">
			<h2>LOGIN</h2>
			<form action="login.php" method="post">
				<p>Username
					<input type="text" name="username" value="" autocomplete="off"/>
				</p>
				<p>Password:
					<input type="password" name="password" value="" />
				</p>
				<input type="submit" name="submit" value="Submit" />
			</form>
			<a href="index.php">Cancel</a>
</div>
<?php include(MAIN_DIR."includes/layouts/footer.php"); ?>

