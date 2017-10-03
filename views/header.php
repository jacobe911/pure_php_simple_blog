<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
	<link rel="stylesheet" href="http://localhost/test/views/styles.css" type="text/css">
	<link rel="stylesheet" href="comments/commentstyles.css" type="text/css">

  </head>
  <body>
		<nav class="navbar navbar-toggleable-sm navbar-light bg-faded">
		  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>
		  <a class="navbar-brand" href="index.php">Home</a>
		  <div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav">
			  <li class="nav-item">
				<?php if (empty($_SESSION['user'])) { ?><a class="nav-link" href="register.php">Login/Signup</a><?php } else { echo "<a class=\"nav-link active\" href=\"#\">Logged in as ".($_SESSION['user'])."</a>";} ?>
			  </li>
			  <?php if(isset($_SESSION['id'])) { if($_SESSION['id'] == "1") { ?><li class="nav-item">
				<a class="nav-link" href="users.php">Users</a>
			  </li><?php }} ?>
			  <?php if(isset($_SESSION['id'])) { ?><li class="nav-item">
				<a class="nav-link" href="profile.php?user=<?php echo $_SESSION['user']; ?>">Profile</a>
			  </li><?php } ?>
			  <?php if(isset($_SESSION['id'])) { ?><li class="nav-item">
				<a class="nav-link" href="newpost.php">New Post</a>
			  </li> <?php } ?>
			  <?php if(isset($_SESSION['id'])) { ?><li class="nav-item">
				<a class="nav-link" href="logout.php">LogOut</a>
			  </li><?php } ?>
			</ul>
		  </div>
		</nav> 
		
