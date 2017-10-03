<?php

if(!$_POST) {
	header("Location:index.php");
}

session_start();

include 'config.php';

$error = '';
$success = '';
	
if($_POST) {
	
	if(empty($_POST['comment'])) {
		$error .= "<p>Please enter your comment.</p>";
		$_SESSION['message'] = $error;
		header("Location:".$_POST['pageurl']);
	}
	
	if(empty($error)) {
		$sql = 'SELECT * FROM comment_page WHERE page_url = :pageurl';
		$stmt = $db_conn->prepare($sql);
		$stmt->execute(array(':pageurl' => $_POST['pageurl']));
		if($stmt->rowCount() > "0") {
			$pageinfo = $stmt->fetch(PDO::FETCH_ASSOC);
			$pageid = $pageinfo['page_id'];
		} else {
			$sql = 'INSERT INTO comment_page (page_url, date_modified, date_created) VALUES (:pageurl, NOW(), NOW())';
			$stmt = $db_conn->prepare($sql);
			$stmt->execute(array(':pageurl' => $_POST['pageurl']));
			if($stmt->rowCount() > "0") {
				$pageid = $db_conn->lastInsertId();
			} else {
				$error = "<p>An error has occurred please try again.</p>";
				$_SESSION['message'] = $error;
				header("Location:".$_POST['pageurl']);
			}
		}
	}
	
	if(empty($error)) {
		$sql ='INSERT INTO comments (page_id, user_id, comment, parent_id, post_date) VALUES ('.$pageid.', '.$_SESSION['id'].', :comment, '.$_POST['parent_id'].', NOW())';
		$stmt = $db_conn->prepare($sql);
		$stmt->execute(array(':comment' => ($_POST['comment'])));
		if($stmt->rowCount() > "0") {
			$_POST['comment'] = '';
			header("Location:".$_POST['pageurl']);
		} else {
			$error = "<p>An error occured please try again.</p>";
			$_SESSION['message'] = $error;
			header("Location:".$_POST['pageurl']);
		}
	}	
}

?>