<?php 
//Define Absolute path
define("MAIN_DIR",dirname(getcwd())."/");
// include all reusable templates
require_once(MAIN_DIR."includes/session.php");
require_once(MAIN_DIR."includes/db_connection.php");
require_once(MAIN_DIR."includes/functions.php");
include(MAIN_DIR."includes/layouts/header.php") 
?>
<?php ?>
<div>
 <a href="login.php">LOG-IN as Admin</a> <br />
 <a href="add_admin.php">Add New Admin</a>
</div>
<div id="Main">
  <?php $divisions = get_visible_divisions();
	while($row =  mysqli_fetch_assoc($divisions)){ ?> 
	<div class="division">
	<?php
		echo "<h2>";										  
		echo $row["division_name"];
		echo "</h2>";
		echo "<p>";										  
		echo $row["content"];
		if ($row["id"]==2){
			// display post here
			$posts = get_posts();
			while($row_posts = mysqli_fetch_assoc($posts)){
	echo "<div class='single-post'>";
		echo "<h3>{$row_posts["title"]}</h3><br>";
		echo "<h5>Created: {$row_posts["date_created"]}";
		echo "<hr>";
		echo "<p>";
		echo "{$row_posts["content"]}";
		echo "</p>";
	echo "</div>";
			}
		}
		echo "</p>";										  
	?>
	</div>
	<?php 											  
	}
	?>
</div>
<?php include(MAIN_DIR."includes/layouts/footer.php"); ?>