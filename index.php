<?php

session_start();

include 'config.php';
include 'functions/functions.php';

include 'views/header.php';
?>
		
		<div class="container" style="background-color:#ddd;">
			<?php 
				//perform query
				$stmt = $db_conn->query('SELECT posts.authorid, posts.title, posts.description, posts.body, posts.post_date, users.username as author 
				FROM posts 
				INNER JOIN users 
				ON users.id = posts.authorid 
				ORDER BY post_date DESC');
				
				//display results
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {?>
					<h2><a href="post.php?post=<?php echo $row['title'];?>"><?php echo $row['title'];?></a></h2>
					<p> by <a href="profile.php?user=<?php echo $row['author'];?>"><?php echo $row['author'];?></a> <small><?php echo timeAgo($row['post_date']);?></small></p>
					<p><?php echo $row['description'];?></p>
					<!-- <p><?php // echo $row['body'];?></p> -->
					<hr />
				<?php } ?>
			
		
		</div>
	
	
	
<?php include 'views/footer.php'; ?>