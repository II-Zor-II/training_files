<?php 
require_once(MAIN_DIR."\database.php");

class Comment extends DatabaseObject{
	
	protected static $table_name="comments";
	protected static $db_fields=array('id', 'photograph_id', 'created', 'author', 'body');
	
	public $id;
	public $photograph_id;
	public $created;
	public $author;
	public $body;
	
	public static function make($photo_id, $author="Anonymous", $body=""){
		if(!empty($photo_id)&& !empty($author)&&!empty($body)){
			$comment = new Comment();
			$comment->photograph_id = (int)$photo_id;
			$comment->created = strftime("%Y-%m-%d %H:%M:%S",time());
			$comment->author = $author;
			$comment->body = $body;
			return $comment;
		}else{
			return false;
		}
		
	}

	public static function find_comments_on($photo_id=0){
		global $database;
		
		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE photograph_id=" .$database->escape_value($photo_id);
		$sql .= " ORDER BY created ASC";
		return self::find_by_sql($sql);
	}
	
	private function has_attribute($attribute){
		$object_vars = $this->attributes(); // returns an assoc array w/ all attrib incl priv ones. as the kesy and their current values as the value
		
		return array_key_exists($attribute, $object_vars);
	}
	
	public function attributes(){
		//return get_object_vars($this);
	
		$attributes = array();
		foreach(self::$db_fields as $field){
		 if(property_exists($this, $field)){
			$attributes[$field] = $this->$field;
		 }
		}
		return $attributes;
	}
	
	protected function sanitized_attributes(){
		global $database;
		$clean_attributes = array();
		
		foreach($this->attributes() as $key => $value){
			$clean_attributes[$key] = $database->escape_value($value);
		}
		return $clean_attributes;
	}
	
	public function save(){
		return isset($this->id) ? $this->update() : $this->create();
	}
	
	public function create(){
		global $database;
		
		$attributes = $this->sanitized_attributes();
		$sql = "INSERT  ".self::$table_name." (";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		if($database->query($sql)){
			$this->id = $database->insert_id();
			return true;
		}else{
			return false;
		}
	}
	
	public function update(){
		global $database;
		
		$attributes = $this->sanitized_attributes();
		$attributes_pairs = array();
		foreach($attributes as $key => $value){
			$attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".self::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id=". $database->escape_value($this->id);
		$database->query($sql);
		return($database->affected_rows() == 1)? true : false;
	}
	
	public function delete(){
		global $database;
		
		$sql = "DELETE FROM ".self::$table_name." ";
		$sql .= "WHERE id=".$database->escape_value($this->id);
		$sql .= " LIMIT 1";
		$database->query($sql);
		return ($database->affected_rows() == 1)? true : false;
	}
}

?>