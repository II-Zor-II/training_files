<?php 
/*
a class to assist Sessions
 - manage logging users in and out
 
 inadvisable to store DB-related obj in sessions
*/



class Session{
	
	private $logged_in=false;
	public $user_id;
	public $message; // contains message that will be passed around during the session
	
	function __construct(){
		session_start(); // figures out the cookie and binds the session 
		$this->check_login();
		$this->check_message();
		if($this->logged_in){
			
		}else{
			
		}
	}
	
	public function is_logged_in(){
		return $this->logged_in;
	}

	public function login($user){
		//db should find user based on uname/pw
		if($user){
			$this->user_id = $_SESSION['user_id'] = $user->id;
			$this->logged_in = true;
		}
	}
	
	public function logout(){
		unset($_SESSION['user_id']);
		unset($this->user_id);
		$this->logged_in = false;
	}
	
	public function message($msg=""){
		if(!empty($msg)){
			$_SESSION['message'] = $msg;
		}else{
			return $this->message;
		}
	}
	
	private function check_login(){
		if(isset($_SESSION['user_id'])){
			$this->user_id = $_SESSION['user_id'];
			$this->logged_in = true;
		}else{
			unset($this->user_id);
			$this->logged_in = false;
		}
	}
	
	private function check_message(){
		if(isset($_SESSION['message'])){
			$this->message = $_SESSION['message'];
			unset($_SESSION['message']); // Add as an attrib then erase the stored version in the server
		}else{
			$this->message = "";
		}
	}
	
}



$session = new Session();
$message = $session->message();
?>