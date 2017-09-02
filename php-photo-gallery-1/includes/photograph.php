<?php 
require_once(MAIN_DIR."\database.php");

class Photograph extends DatabaseObject{
	
	protected static $table_name="photographs";
	protected static $db_fields=array('id', 'file_name', 'type', 'size', 'caption');
	public $id;
	public $file_name;
	public $type;
	public $size;
	public $caption;
	
	private $temp_path;
	protected $upload_dir="images";
	public $errors=array();
	
	protected $upload_errors = array(
		 UPLOAD_ERR_OK => "No errors.",
		 UPLOAD_ERR_OK => "Larger than upload_max_filesize.",
		 UPLOAD_ERR_OK => "Larger than form MAX_FILE_SIZE.",
		 UPLOAD_ERR_OK => "Partial upload.",
		 UPLOAD_ERR_OK => "No file.",
		 UPLOAD_ERR_OK => "No temporary directory.",
		 UPLOAD_ERR_OK => "Can't write to disk.",
		 UPLOAD_ERR_OK => "File upload stopped by extension.",
	);
	
	//Pass in $_FILE(['uploaded_file]) as an argument
	public function attach_file($file){
		//error checking on the form param
		if(!$file || empty($file)|| !is_array($file)){
			$this->errors[] = "no files was uploaded.";
			return false;
		}elseif($file['error']!=0){
			$this->errors[] = $this->uploaded_errors[$file['error']];
			return false;
		}else{
		//set obj attr to the form param
		$this->temp_path = $file['tmp_name'];
		$this->file_name = basename($file['name']);
		$this->type = $file['type'];
		$this->size = $file['size'];
		return true;
		}
	}
	
	public function save(){
		if(isset($this->id)){
			$this->update();
		}else{
			if(!empty($this->errors)){return false;}
			if(strlen($this->caption) > 255){ // if caption is greater than 255
				$this->errors[] = "The caption can only be 255 characters long.";
				return false;
			}
			if(empty($this->file_name)||empty($this->temp_path)){
				$this->errors[]="The file location was not available.";
				return false;
			}
			//Determine target_ath
			$first_path = dirname(MAIN_DIR);
			$target_path = $first_path."/public/".$this->upload_dir."/".$this->file_name;
			if(file_exists($target_path)){
				$this->errors[] = "The file {$this->file_name} already exists.";
				return false;
			}
			if(move_uploaded_file($this->temp_path, $target_path)){
				if($this->create()){
					unset($this->temp_path);
					return true;
				}
			}else{
				$this->errors[] = "The file upload failed. Incorrect permissions on the upload folder.";
				return false;
			}
		
		}
	}
	
	public function destroy(){
		if($this->delete()){ // removes the db entry
			$first_path = dirname(MAIN_DIR);
			$target_path = $first_path."/public/".$this->image_path();
			return unlink($target_path)? true : false;
		}else{
			// database delete failed
			return false;
		}
	}
	
	public function image_path(){
		return $this->upload_dir."/".$this->file_name;
	}
	
	public function size_as_text(){
		if($this->size < 1024){
			return "{$this->size} bytes";
		}
		elseif($this->size < 1048576){
			$size_kb = round($this->size/1024);
			return "{$size_kb} KB";
		}else{
			$size_nb = round($this->size/1048576, 1);
			return "{$size_mb} MB";
		}
	}
	// Common Db Methods
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
	
	public function create(){
		global $database;
		
		$attributes = $this->sanitized_attributes();
		$sql = "INSERT ".self::$table_name." (";
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
			$attribute_pairs[]="{$key}='{$value}'";
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
	
	public function comments(){
		return Comment::find_comments_on($this->id);
	}
}
//

//
//if(isset($_POST['submit'])){
//	$tmp_file = $_FILES['file_upload']['tmp_name'];
//	$target_file = basename($_FILES['file_upload']['name']);
//	$upload_dir = "uploads";
//	
//	if(move_uploaded_file($tmp_file, $upload_dir."/".$target_file)){
//		$message = "File uploaded successfully.";
//	}else{
//		$error = $_FILES['file_upload']['error'];
//		$message = $upload_errors[$error];
//	}
//}

?>