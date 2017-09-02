<?php 

function redirect_to($new_location){
	header("Location: " .$new_location);
	exit;
}

function mysql_prep($string){
	global $connection;
	$safe_mysql_str = mysqli_real_escape_string($connection, $string);
	return $safe_mysql_str;
}

function confirm_query($result_set){
	if(!$result_set){
		echo var_dump($result_set);
		die("Database query failed.");
	}	
}

function get_division_by_id($div_id){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM division ";
	$query .= "WHERE ID = {$div_id} ";		
	$query .= "LIMIT 1";
	
	$div_set = mysqli_query($connection, $query);
	confirm_query($div_set);
	return $div_set;
}

function get_visible_divisions($public=true){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM division ";
	if($public){
		$query .= "WHERE visibility = 1 ";	
	}
	$query .= "ORDER BY position ASC";
	
	$division_set = mysqli_query($connection, $query);
	confirm_query($division_set);
	return $division_set;
}

function check_field_presence($field_array){
	global $errors;
	foreach($field_array as $field){
		if($_POST[$field]==""||$_POST[$field]=="null"){
			$errors[$field] = "{$field} can't be blank. <br/>";
		}
	}
}

function edit_division($div_id,$division_name,$content,$visibility){
	global $connection;
	
	$safe_div_id = mysql_prep($div_id);
	$safe_div_name = mysql_prep($division_name);
	$safe_content = mysql_prep($content);

	$query = "UPDATE division ";
	$query .= "SET division_name = '{$safe_div_name}', ";
	$query .= "visibility = {$visibility}, ";
	$query .= "content = '{$safe_content}' ";
	$query .= "WHERE id = {$safe_div_id} ";
	$query .= "LIMIT 1";
	$result = mysqli_query($connection, $query);
	confirm_query($query);
	if($result && mysqli_affected_rows($connection)==1){
		return true;
	}else{
		return false;
	}
}

function find_admin_by_username($username){
	global $connection;
	$query = "SELECT * ";
	$query .= "FROM admin ";
	$query .= "WHERE username = '{$username}' ";
	$query .= "LIMIT 1";
	$admin_set = mysqli_query($connection, $query);
	confirm_query($admin_set);
	if($admin = mysqli_fetch_assoc($admin_set)){
		return $admin;
	} else {
		return null;
	}
}

function password_encrypt($password){
	$hash_format = "$2y$10$";
	$salt_length = 22;
	$salt = generate_salt($salt_length);
	$format_and_salt = $hash_format . $salt;
	$hash = crypt($password, $format_and_salt);
	return $hash;
}

function generate_salt($length){
	$unique_random_string = md5(uniqid(mt_rand(), true));
	
	$base64_string = base64_encode($unique_random_string);
	
	$modified_base_64_string = str_replace('+','.',$base64_string);
	
	$salt = substr($modified_base_64_string, 0, $length);
	
	return $salt;
}


function password_check($password,$existing_hash){
	$hash = crypt($password, $existing_hash);
	if($hash === $existing_hash){
		return true;
	}else{
		return false;
	}
}

function attempt_login($username,$password){
	$admin = find_admin_by_username($username);
	if($admin){
		if(password_check($password, $admin["hashed_password"])){
			return $admin;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function logged_in(){
	return isset($_SESSION['admin_id']);
}

function confirm_logged_in(){
	if(logged_in()){
		redirect_to("login.php");
	}
}

function get_posts($public=true){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM posts ";
	if($public){
		$query .= "WHERE visibility = 1 ";	
	}
	$query .= "ORDER BY id ASC";
	
	$posts_set = mysqli_query($connection, $query);
	confirm_query($posts_set);
	return $posts_set;
}

function get_post_by_id($post_id){
	global $connection;
	
	$query = "SELECT * ";
	$query .= "FROM posts ";
	$query .= "WHERE ID = {$post_id} ";		
	$query .= "LIMIT 1";
	
	$post_set = mysqli_query($connection, $query);
	confirm_query($post_set);
	return $post_set;
}

function edit_post($post_id,$post_title,$post_content,$visibility){
	global $connection;
	
	$safe_post_id = mysql_prep($post_id);
	$safe_post_title = mysql_prep($post_title);
	$safe_post_content = mysql_prep($post_content);
	
	$query = "UPDATE posts ";
	$query .= "SET title = '{$safe_post_title}', ";
	$query .= "visibility = {$visibility}, ";
	$query .= "content = '{$safe_post_content}' ";
	$query .= "WHERE id = {$safe_post_id} ";
	$query .= "LIMIT 1";
	$result = mysqli_query($connection, $query);
	confirm_query($query);
	if($result && mysqli_affected_rows($connection)==1){
		return true;
	}else{
		return false;
	}
}

function create_new_post(){
	global $connection;
	
	$query = "INSERT INTO posts ";
	$query .= "(title,visibility)";
	$query .= "VALUES (";
	$query .= "'Default Title',0";
	$query .= ")";
	$result = mysqli_query($connection, $query);
	confirm_query($query);
	if($result && mysqli_affected_rows($connection)==1){
		return true;
	}else{
		return false;
	}
}

?>


















