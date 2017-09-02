<?php 
require_once(MAIN_DIR."\database.php");
class DatabaseObject {
	
	protected static $table_name;
	
	public static function find_all(){
		$sql = "SELECT * FROM ".static::$table_name;
		return static::find_by_sql($sql);
	}
	
	public static function find_by_id($id=0){
		global $database;
		
		$sql = "SELECT * FROM ".static::$table_name." WHERE id=".$database->escape_value($id)." LIMIT 1";
		$result_array = static::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false; // array_shift pulls the first row in the array
	}
	
	public static function find_by_sql($sql=""){
		global $database;
		$result_set = $database->query($sql);;
		$object_array = array(); // create an array object that will sent back
		while($row = $database->fetch_array($result_set)){
			$object_array[] = static::instantiate($row);
		}
		return $object_array;
	}
	
	private static function instantiate($record){
		$class_name = get_called_class();
		$obj = new $class_name;
		//		$obj->id = $record['id'];
		//		$obj->username = $record['$username'];
		//		$obj->password = $record['$password'];
		//		$obj->first_name = $record['$first_name'];
		//		$obj->last_name = $record['$last_name'];
		foreach($record as $attribute=>$value){
			if($obj->has_attribute($attribute)){
				$obj->$attribute = $value;
			}
		}
		return $obj;
	}

	private function has_attribute($attribute){
		//get object vars returns an associative array w/ all attributes
		// including private ones as the keys and their current values as the value
		$object_vars = get_object_vars($this);
		return array_key_exists($attribute, $object_vars);
	}
}
?>














