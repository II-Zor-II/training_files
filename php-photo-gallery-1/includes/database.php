<?php 
require_once("config.php");

class MySQLDatabase {
	
	private $connection;
	
	function __construct(){
		$this->open_connection();
	}
	public function open_connection(){
	$this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if(mysqli_connect_errno()){
			die("Database connection failed: " .
			     mysqli_connect_error() .
			   " (" . mysqli_connect_errno() . ")" //Returns the last error code for the most recent MySQLi function call that can succeed or fail.
			);
		}
	}
	
	public function close_connection(){
		if(isset($this->connection)){
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}
	
	public function query($sql){
		$result = mysqli_query($this->connection, $sql);
		$this->confirm_query($result);
		return $result;
	}
	/* create database agnostic function names that can be reused 
		by other databases
	*/
	public function escape_value($string){
		$escaped_string = mysqli_real_escape_string($this->connection, $string);
		return $escaped_string;
	}
	
	private function confirm_query($result){
		if(!$result){
			die("Database query failed. ");
		}
	}
	
	public function fetch_array($result_set){
		return mysqli_fetch_array($result_set);
	}
	
	public function num_rows($result_set){
		return mysqli_num_rows($result_set);
	}
	
	public function insert_id(){
		// get the last id inserted over the current db connection
		return mysqli_insert_id($this->connection);
	}
	
	public function affected_rows(){
		return mysqli_affected_rows($this->connection);
	}
	
}

$database = new MySQLDatabase();
$db =& $database;
?>