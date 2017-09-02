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
	if(isset($_POST["post_title"])&&isset($_POST["post_content"])){
			$post_id = $_SESSION["post_id"];
			$post_title = $_POST["post_title"];
			$post_content = $_POST["post_content"];
			$visibility = (int) $_POST["visibility"];
			if(edit_post($post_id,$post_title,$post_content,$visibility)){
				"SUCCESSFUL";
			}else{
				echo "content update failed";
			}
	}else{
	$field_array = array("division_name","content");
	check_field_presence($field_array);
	if($errors){
		foreach($errors as $error){
			echo $error;
		}
	}else{
		$div_id = $_SESSION["division_id"];
		$division_name = $_POST["division_name"];
		$content = $_POST["content"];
		$visibility = (int) $_POST["visibility"];
		if(edit_division($div_id,$division_name,$content,$visibility)){
			"SUCCESSFUL";
		}else{
			echo "content update failed";
		}
	}
	}
}
?>
<div id="Main">
<div class="content-list">
   <ul>
  <?php $divisions = get_visible_divisions(false);
	while($row =  mysqli_fetch_assoc($divisions)){ 
		echo "<li>";
		if($row["id"]==2){
			echo "<a href='admin.php?division_id={$row["id"]}&show_posts=true'>";
			echo $row["division_name"];
			echo "</a>";
			if(isset($_GET["show_posts"])&&$_GET['show_posts']==true){
				$posts = get_posts(false);
				echo "<ul>";
				while($post_row=mysqli_fetch_assoc($posts)){
					echo "<li>";
					echo "<a href='admin.php?division_id={$row["id"]}&show_posts=true&post_id={$post_row["id"]}'>{$post_row["title"]}</a>";
					echo "</li>";
				}
				echo "</ul>";
			}
		}else{
													  
			echo "<a href='admin.php?division_id={$row["id"]}'>";
			echo $row["division_name"];
			echo "</a>";
		
		}
		echo "</li>";								  
	}
	?>
	</ul>
	<a href="logout.php">Logout</a>
</div>
<div class="edit-content">
	<?php 
	if(isset($_GET["division_id"])){
		$division = get_division_by_id($_GET["division_id"]);
		if($row = mysqli_fetch_assoc($division)){ 
		 if($row["id"]==2){ ?>
		 
	<h2><?php 
			$_SESSION["division_id"] = $row["id"];
			echo $row["division_name"];?></h2>
	<form action="admin.php" method="post">
		<p>Division Name: <br>
			<input type="text" name="division_name" value="<?php echo $row["division_name"];?>" autocomplete="off"/>
		</p>
		<p>Content:<br>
			<textarea rows="4" cols="50" name="content"><?php echo $row["content"];?></textarea>
		<br>Visible: <br>
			<?php 
		echo "<input type='radio' name='visibility' value='1'";
			if($row["visibility"]){
				echo " checked> Yes<br>"."<input type='radio' name='visibility' value='0'> No<br>";
			}else{
				echo "> Yes<br>"."<input type='radio' name='visibility' value='0' checked> No<br>";
			}
			?>
		</p>
		<input type="submit" name="submit" value="Submit" />
	</form>
		<br>
		<a href="add_new_post.php">+ Add new post</a>			 
		<?php if(isset($_GET["post_id"])){ ?>
	<h2><?php 
	$post = get_post_by_id($_GET["post_id"]);
		if($post_row = mysqli_fetch_assoc($post)){	 
		$_SESSION["post_id"] = $post_row["id"];
		echo $post_row["title"];?></h2>
	<form action="admin.php" method="post" id="single_post_form">
		<p>Post Title: <br>
			<input type="text" name="post_title" value="<?php echo $post_row["title"]; ?>" autocomplete="off"/>
		</p>
		<p>Content:<br>
			<textarea rows="4" cols="50" name="post_content"><?php echo $post_row["content"]; ?></textarea><br>
			Visible: <br>
			<?php 
		echo "<input type='radio' name='visibility' value='1'";
			if($post_row["visibility"]){
				echo " checked> Yes<br>"."<input type='radio' name='visibility' value='0'> No<br>";
			}else{
				echo "> Yes<br>"."<input type='radio' name='visibility' value='0' checked> No<br>";
			}
			?>
		</p>
		<input type="submit" name="submit" value="Submit" />
	</form>				
						 
	<?php 		}
			}
		 }else{
		 ?>	
	<h2><?php 
			$_SESSION["division_id"] = $row["id"];
			echo $row["division_name"];?></h2>
	<form action="admin.php" method="post">
		<p>Division Name: <br>
			<input type="text" name="division_name" value="<?php echo $row["division_name"];?>" autocomplete="off"/>
		</p>
		<p>Content:<br>
			<textarea rows="4" cols="50" name="content"><?php echo $row["content"];?></textarea>
		<br>Visible: <br>
			<?php 
		echo "<input type='radio' name='visibility' value='1'";
			if($row["visibility"]){
				echo " checked> Yes<br>"."<input type='radio' name='visibility' value='0'> No<br>";
			}else{
				echo "> Yes<br>"."<input type='radio' name='visibility' value='0' checked> No<br>";
			}
			?>
		</p>
		<input type="submit" name="submit" value="Submit" />
	</form>
	<?php	}
		}
	}else{
		echo "<br><br>Welcome <h3><strong>{$_SESSION["username"]}</strong></h3> <br><br> Please select on the side which Division you would like to edit.";	
	}
	?>
</div>
</div>
<?php include(MAIN_DIR."includes/layouts/footer.php"); ?>