<?php
session_start();
include 'config.php';
include 'functions/functions.php';

if(!$_GET['edit']){
	header('Location: index.php');
} 

if($_GET){
	$sql = 'SELECT posts.id, posts.authorid, posts.title, posts.description, posts.body, posts.post_date, users.username as author 
	FROM posts 
	INNER JOIN users 
	ON users.id = posts.authorid 
	WHERE posts.title = :edit';
	$stmt = $db_conn->prepare($sql);
	$stmt->execute(array(':edit' => ($_GET['edit'])));
	$postinfo = $stmt->fetch(PDO::FETCH_ASSOC);
} 

if($_SESSION['user'] != $postinfo['author']) {
	header('Location:index.php');
}

$error ='';
$success = '';

if($_POST) {

	if(empty($_POST['title'])) {
		$error = "<p>Please enter a post title.</p>";
	}
	if(empty($_POST['description'])) {
		$error .= "<p>Please enter a post description.</p>";
	}
	if(empty($_POST['body'])) {
		$error .= "<p>Please enter the post content.</p>";
	}

	if(empty($error)) {
		if($_POST['title'] != $postinfo['title']) {
			$sql = 'SELECT title FROM posts WHERE title = :title';
			$stmt = $db_conn->prepare($sql);
			$stmt->execute(array(':title' => ($_POST['title'])));
			if($stmt->rowCount() > "0") {
				$error = "<p>This title has already been used.</p>";
			}
		}
	}

	if(empty($error)) {
		$sql ='UPDATE posts SET title = :title, description = :description, body = :body, edit_date = NOW() WHERE id = :id';
		$stmt = $db_conn->prepare($sql);
		$stmt->execute(array(':title' => ($_POST['title']),':description' => ($_POST['description']),':body' => ($_POST['body']),':id' => ($postinfo['id'])));
		if($stmt->rowCount() > "0") {
			$success = "<p>Post Updated!</p>";
		} else {
			$error = "<p>An error occured please try again</p>";
		}
	}
}

include 'views/header.php';
?>

		
		<div class="container" style="background-color:#ddd;">
		
			<form method="post">
				<div class="form-group row">
				  <label for="title" class="col-2 col-form-label">Title</label>
				  <div class="col-10">
					<input class="form-control" type="text" id="title" name="title" value="<?php if(isset($_POST['title'])){echo $_POST['title'];} else { echo $postinfo['title']; } ?>">
				  </div>
				</div>
				<div class="form-group row">
				  <label for="description" class="col-2 col-form-label">Description</label>
				  <div class="col-10">
					<input class="form-control" type="text" id="description" name="description" value="<?php if(isset($_POST['description'])){echo $_POST['description'];} else { echo $postinfo['description']; }?>">
				  </div>
				</div>
				<div class="form-group row">
				  <label for="body" class="col-2 col-form-label">Body</label>
				  <div class="col-10">
					<textarea onfocus="auto_grow(this)" onkeyup="auto_grow(this)" class="form-control" id="body" name="body" style="overflow:hidden;resize:none;min-height:100px;"><?php if(isset($_POST['body'])){echo $_POST['body'];} else { echo $postinfo['body']; }?></textarea>
				  </div>
				</div>
					<button type="submit" class="btn btn-primary" id="post">Edit</button>
				<div>
					<?php echo $error; echo $success; ?>
				</div>
			</form>
			
		</div>
		
		<div class="container">
			
			<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; ?>
		
		</div>
	
	
<?php include 'views/footer.php'; ?>

<script>

function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}

</script>