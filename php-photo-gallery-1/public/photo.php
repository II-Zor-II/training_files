<?php 
//Define Absolute path 

require_once("../includes/initialize.php");

	if(empty($_GET['id'])){
		$session->message("No photograph ID was provided.");
		redirect_to('index.php');
	}

	$photo = Photograph::find_by_id($_GET['id']);
	if(!$photo){
		$session->message("The photo could not be located.");
		redirect_to('index.php');
	}

	if(isset($_POST['submit'])){
		$author = trim($_POST['author']);
		$body = trim($_POST['body']);

		$new_comment = Comment::make($photo->id, $author, $body);
		if($new_comment && $new_comment->save()){
			 //comment saved
			redirect_to("photo.php?id={$photo->id}");
		}else{
			//failed
			$message = "There was an error regarding the comment.";
		}
	}else{
		$author = "";
		$body = "";
	}

$comments = Comment::find_comments_on($photo->id);
?>
<a href="index.php">&laquo; Back</a>
<br />
<br />
<hr>
<div style="margin-left: 20px;">
	<img src="<?php echo $photo->image_path(); ?>" />
	<p><?php echo $photo->caption; ?></p>
</div>

<!-- list comments -->

<div id="comments">
	<?php foreach($comments as $comment): ?>
		<div class="comment">
			<div class="author">
				<?php echo htmlentities($comment->author); ?> wrote:
			</div>
			<div class="body">
				<?php echo strip_tags($comment->body, '<strong><em><p>'); ?>
			</div>
			<div class="meta-info" style="font-size: 0.75em">
				<?php echo datetime_to_text($comment->created); ?>
			</div>
		</div>
	<?php endforeach; ?>
	<?php if(empty($comments)){ echo "No comments.";} ?>
</div>

<div id="comment-form">
	<h3>New Comment</h3>
	<?php echo output_message($message); ?>
	<form action="photo.php?id=<?php echo $photo->id; ?>" method="post">
		<table>
			<tr>
				<td>Your name: </td>
				<td><input type="text" name="author" value="<?php echo $author; ?>"/></td>
			</tr>
			<tr>
				<td>Your comment:</td>
				<td><textarea name="body" cols="40" rows="8"><?php echo $body; ?></textarea></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" value="Submit Comment"/></td>
			</tr>
		</table>
	</form>
</div>