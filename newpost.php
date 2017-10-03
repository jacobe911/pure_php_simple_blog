<?php
session_start();
include 'config.php';
include 'functions/functions.php';

if(empty($_SESSION)){
	header('Location: index.php');
} 

$error = '';
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
		$sql = 'SELECT title FROM posts WHERE title = :title';
		$stmt = $db_conn->prepare($sql);
		$stmt->execute(array(':title' => ($_POST['title'])));
		if($stmt->rowCount() > "0") {
			$error = "<p>This title has already been used.</p>";
		}
	}

	if(empty($error)) {
		$sql ='INSERT INTO posts (authorid, title, description, body, post_date) VALUES ('.$_SESSION['id'].', :title, :description, :body, NOW())';
		$stmt = $db_conn->prepare($sql);
		$stmt->execute(array(':title' => ($_POST['title']),':description' => ($_POST['description']),':body' => ($_POST['body'])));
		if($stmt->rowCount() > "0") {
			$success = "<p>Post successful!</p>";
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
					<input class="form-control" type="text" id="title" name="title" value="<?php if(isset($_POST['title'])){echo $_POST['title'];} ?>">
				  </div>
				</div>
				<div class="form-group row">
				  <label for="description" class="col-2 col-form-label">Description</label>
				  <div class="col-10">
					<input class="form-control" type="text" id="description" name="description" value="<?php if(isset($_POST['description'])){echo $_POST['description'];} ?>">
				  </div>
				</div>
				<div class="form-group row">
				  <label for="body" class="col-2 col-form-label">Body</label>
				  <div class="col-10">
					<textarea onkeyup="auto_grow(this)" class="form-control" id="body" name="body" style="overflow:hidden;resize:none;min-height:100px;"><?php if(isset($_POST['body'])){echo $_POST['body'];} ?></textarea>
				  </div>
				</div>
					<button type="submit" class="btn btn-primary" id="post">Post</button>
				<div>
					<?php echo $error; echo $success; ?>
				</div>
			</form>
			
		</div>
			
	
<?php include 'views/footer.php'; ?>

<script>

function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}

</script>