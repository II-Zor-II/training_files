


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
			$hashed_password = password_encrypt($_POST["password"]);
			
				$query = "INSERT INTO admin (";
				$query .= " username, hashed_password";
				$query .= ") VALUES (";
				$query .= " '{$username}', '{$hashed_password}'";
				$query .= ")";
				$result = mysqli_query($connection, $query);
				
				if($result){
					echo "new ADMIN created";
				} else {
					echo "Failed to create";
					echo var_dump($result);
				}
		}
	}
?>
<div id="login-admin">
			<h2>Add Admin</h2>
			<form action="add_admin.php" method="post">
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

