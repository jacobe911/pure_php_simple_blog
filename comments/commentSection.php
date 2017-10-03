<?php

// include your DB pdo config file location
include_once 'config.php';
//include the required functions
include_once 'functions/functions.php';

$replylevel = '';	
$pageurl = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

function getComments($row,$db_conn,$pageurl,$replylevel) { ?>

	<!-- Display Each Comment !-->
	<div class="comment reply<?php echo $replylevel; ?>">
		<?php if($row['parent_id'] > "0") {  
			$sql = 'SELECT comments.comment_id, users.username as parentauthor 
					FROM comments
					INNER JOIN users
					ON comments.user_id = users.id
					WHERE comments.comment_id = :parent_id';
			$stmt = $db_conn->prepare($sql);
			$stmt->execute(array(':parent_id' => $row['parent_id']));
			$pid = $stmt->fetch(PDO::FETCH_ASSOC);
		?>
		<p class="commentby"><?php echo "Reply to "; ?> 
			<a class="user" href="/test/profile.php?user=<?php echo $pid['parentauthor']; ?>"><?php echo $pid['parentauthor']; ?></a><?php } else { ?>
			<p class="commentby"> <?php } ?> by <a class="user" href="/test/profile.php?user=<?php echo $row['author'];?>"><?php echo $row['author'];?></a> <small><?php echo timeAgo($row['post_date']);?></small></p>
		<p class="comment"><?php echo nl2br($row['comment']);?></p>
		<a href='#commentform' class='reply' id="<?php echo $row['comment_id']; ?>">Reply</a>
	<hr class="commentdivider" />			
	</div>
	
	<?php  

	//get replys for comment
	$sql = 'SELECT c.comment_id, c.comment, c.parent_id, c.post_date, u.username as author, cp.page_url as pageurl 
	FROM comments c
	INNER JOIN users u 
	ON u.id = c.user_id	
	INNER JOIN comment_page cp
	ON cp.page_id = c.page_id
	WHERE cp.page_url = :url
	AND c.parent_id = :parent_id 
	ORDER BY post_date DESC';
	$stmt = $db_conn->prepare($sql);
	$stmt->execute(array(':url' => $pageurl, ':parent_id' => $row['comment_id']));
	if($stmt->rowCount() > "0") {
		if($replylevel < "5") {
			$replylevel++;
		} else {
			$replylevel = "5";
		}
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			getComments($row,$db_conn,$pageurl,$replylevel);
		}
	}
 } 

//get comments for page
$sql = 'SELECT c.comment_id, c.comment, c.parent_id, c.post_date, u.username as author, cp.page_url as pageurl 
FROM comments c
INNER JOIN users u 
ON u.id = c.user_id 
INNER JOIN comment_page cp
ON cp.page_id = c.page_id
WHERE cp.page_url = :url
AND c.parent_id = 0 
ORDER BY post_date DESC';
$stmt = $db_conn->prepare($sql);
$stmt->execute(array(':url' => $pageurl));

?>

<div class="commentsection"><?php

	//Get and display comments
	if($stmt->rowCount() == "0") {
		echo "<p>There are no comments yet. Be the first to add one.</p>";
	} else {
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$replylevel = '0';
			getComments($row,$db_conn,$pageurl,$replylevel);

		} 
	}
	
	
	//New comment form
	if(isset($_SESSION['id'])) { ?>
		<form method="post" id="commentform" class="addcomment" action="/test/comments/postComment.php">
			<div>
			  <label class ="commentlabel" id="commentlabel" for="comment">Add a Comment:</label>
			  <div class="commenttextarea">
				<textarea onkeyup="auto_grow(this)" class="comment" id="commentbox" name="comment"><?php if(isset($_POST['comment'])){echo $_POST['comment'];} ?></textarea>
			  </div>
			</div>
			<div class="submitcomment">
				<button class="submitcomment" type="submit" id="post">Post</button>
			</div>
			<div>
				<input name="pageurl" type="hidden" value="<?php echo $pageurl ?>">
				<input type='hidden' name='parent_id' id='parent_id' value='0'/>
			</div>
			<div>
				<?php if(isset($_SESSION['message'])) {echo $_SESSION['message'];} unset($_SESSION['message']); ?>
			</div>
		</form>
	<?php } else { ?>
		<p>Please <a href="/test/register.php">Sign in</a> to comment</p>
	<?php } 	

?></div>

<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<script type='text/javascript'>
$(function(){
	$("a.reply").click(function() {
		var id = $(this).attr("id");
		$("#parent_id").attr("value", id);
		$("#commentlabel").html("X &nbsp;&nbsp;&nbsp;&nbsp; Reply:");
	});
});

$(function(){
	$("#commentlabel").click(function() {
		$("#parent_id").attr("value", "0");
		$("#commentlabel").html("Add a Comment:");
	});
});


function auto_grow(element) {
	element.style.height = "5px";
	element.style.height = (element.scrollHeight)+"px";
}

</script>
