<?php 
//Define Absolute path 

require_once("../includes/initialize.php");

//find all photos
$photos = Photograph::find_all();
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="initial-scale=1.0">
		<title>Photo Gallery</title>
		<link rel="stylesheet" href="stylesheets/normalize.css" media="screen">
		<link rel="stylesheet" href="stylesheets/main.css" media="screen">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="Zor">
	</head>
<body>
	
	
<h3><a href="admin/login.php">Log-in</a></h3>

<?php foreach($photos as $photo): ?>

	<div>
		<a href="photo.php?id=<?php echo $photo->id; ?>">
			<img src="<?php echo $photo->image_path(); ?>" width="200" />
		</a>
		<p><?php echo $photo->caption; ?></p>
	</div>

<?php endforeach; ?>
</body>
</html>