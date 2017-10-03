<?php
session_start();
include_once 'config.php';
include_once 'functions/functions.php';

$loggedin ='0';

if(!$_GET['post']){
	header('Location: index.php');
}

if($_GET){
	$sql = 'SELECT posts.authorid, posts.title, posts.description, posts.body, posts.post_date, posts.edit_date, users.username as author 
	FROM posts 
	INNER JOIN users 
	ON users.id = posts.authorid 
	WHERE posts.title = :post';
	$stmt = $db_conn->prepare($sql);
	$stmt->execute(array(':post' => ($_GET['post'])));
	if ($stmt->rowCount() == 0) {
		header ('Location:index.php');
	}
	$postinfo = $stmt->fetch(PDO::FETCH_ASSOC);
} 

if(isset($_SESSION['id'])) {
	if($_SESSION['user'] == $postinfo['author']) {
		$loggedin = '1';
	}
}

include 'views/header.php';
?>

		
		<div class="container" style="background-color:#ddd;">
		
				<h2><?php echo $postinfo['title'];?></h2>
				<p><?php if($loggedin == "1") { ?>
					<a href="editpost.php?edit=<?php echo $postinfo['title']; ?>">Edit Post</a>
				<?php } ?></p>
				<p> by <a href="profile.php?user=<?php echo $postinfo['author'];?>"><?php echo $postinfo['author'];?></a> <small><?php echo timeAgo($postinfo['post_date']);?></small></p>
				<p><?php echo $postinfo['description'];?></p>
				<p><?php echo nl2br($postinfo['body']);?></p>
				<?php if(isset($postinfo['edit_date'])) { ?><p><b>Edited <?php echo timeAgo($postinfo['edit_date']); ?></b></p><?php } ?>
			
		</div>
		
		<div class="container" style="background-color:#eee;">
			
			<?php include 'comments/commentSection.php'; ?>
		
		</div>
	
	
<?php include 'views/footer.php'; ?>

<script>

function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}

</script>