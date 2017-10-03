<?php
session_start();
include 'config.php';
include 'functions/functions.php';

if($_GET){
	$sql = 'SELECT posts.authorid, posts.title, posts.description, posts.body, posts.post_date, users.username as author 
	FROM posts 
	INNER JOIN users 
	ON users.id = posts.authorid 
	WHERE users.username = :user 
	ORDER BY post_date DESC';
	$stmt = $db_conn->prepare($sql);
	$stmt->execute(array(':user' => ($_GET['user'])));
} 
if(!$_GET['user']){
	header('Location: index.php');
} 


include 'views/header.php';
?>

		
		<div class="container" style="background-color:#ddd;">
			
			<?php if ($stmt->rowCount() == 0) {
				echo "<p>This user hasn't made any Posts.</p>";
			} else {
			
				while ($userinfo = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
					<h2><a href="post.php?post=<?php echo $userinfo['title'];?>"><?php echo $userinfo['title'];?></a></h2>
					<p> by <?php echo $userinfo['author'];?> <small><?php echo timeAgo($userinfo['post_date']);?></small></p>
					<p><?php echo $userinfo['description'];?></p>
					<p><?php echo $userinfo['body'];?></p>
					<hr />
				<?php } 
			}?>
			
		</div>

		
	
<?php include 'views/footer.php'; ?>
