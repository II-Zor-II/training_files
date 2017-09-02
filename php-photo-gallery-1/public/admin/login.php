<?php 
//Define Absolute path 
require_once("../../includes/initialize.php");

if($session->is_logged_in()){
	redirect_to("index.php");
}

if(isset($_POST['submit'])){
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	
	//check db if user + pw exist
	$found_user = User::authenticate($username, $password);
	
	if($found_user){
		$session->login($found_user);
		redirect_to("index.php");
	}else{
		$message = "USERNAME/PASSWORD combination incorrect.";
	}
} else {
	$username = "";
	$password = "";
}
?>
<?php include_layout_template('admin_header.php'); ?>
		<div class="main">
			<h2>Staff Login</h2>
			<form action="login.php" method="post">
				<table>
					<tr>
						<td>USERNAME: </td>
						<td>
							<input type="text" name="username" maxlenght="30" value="<?php echo htmlentities($username); ?>" />
						</td>
					</tr>
					<tr>
						<td>PASSWORD: </td>
						<td>
							<input type="password" name="password" maxlenght="30" value="<?php echo htmlentities($password); ?>" />
						</td>
					</tr>
					<tr>
						 <td colspan="2">
						 	<input type="submit" name="submit" value="Login" />
						 </td>
					</tr>
				</table>
			</form>
		</div>
<?php include_layout_template('admin_footer.php'); ?>