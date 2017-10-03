<?php
session_start();
include 'config.php';
include 'functions/functions.php';

$error = '';
$success = '';

if($_POST){
	
	if(empty($_POST['username'])) {
		$error .= "<p>Please enter a username</p>";
	}
	
	if(empty($_POST['email']) AND $_POST['register'] == 1) {
		$error .= "<p>Please enter an email address</p>";
	}
	
	if(empty($_POST['password'])) {
		$error .= "<p>Please enter a password</p>";
	}
	
	if($error == '' AND $_POST['register'] == 1){
		$sql = 'SELECT username FROM users WHERE username = :username';
		$stmt = $db_conn->prepare($sql);
		$stmt->execute(array(':username' => ($_POST['username'])));
		if ($stmt->rowCount() > 0) {
			$error = "<p>This username is taken</p>";
		} 
		$sql = 'SELECT email FROM users WHERE email = :email';
		$stmt = $db_conn->prepare($sql);
		$stmt->execute(array(':email' => ($_POST['email'])));
		if ($stmt->rowCount() > 0) {
			$error .= "<p>This email is taken</p>";
		} 	
			
		if(empty($error)) {
			$sql ='INSERT INTO users (username, password, email, reg_date) VALUES (:username, :password, :email, NOW())';
			$stmt = $db_conn->prepare($sql);
			$stmt->execute(array(':username' => ($_POST['username']),':password' => ($_POST['password']),':email' => ($_POST['email'])));
			$_SESSION['id'] = $db_conn->lastInsertId();
			$_SESSION['user'] = $_POST['username'];
			$success = 'Registered successfully!';
		}
	} else if($error == '' AND $_POST['register'] == 0) {
		$sql = 'SELECT * FROM users WHERE username = :username';
		$stmt = $db_conn->prepare($sql);
		$stmt->execute(array(':username' => ($_POST['username'])));
		if ($stmt->rowCount() == 0) {
			$error = "<p>The username or password was incorrect.</p>";
		} else { 
			$userinfo = $stmt->fetch(PDO::FETCH_ASSOC);
			if($_POST['password'] == $userinfo['password']) {
				$_SESSION['id'] = $userinfo['id'];
				$_SESSION['user'] = $userinfo['username'];
				header ("Location: index.php");
			} else {
				$error = "<p>The username or password was incorrect.</p>";
			}
		}
	}
}

include 'views/header.php';
?>
		
	<div class="container" style="background-color:#ddd;">
		
		<form method="post">
		  <p><?php echo $error; ?></p>
			<div class="form-group row">
			  <label for="username" class="col-2 col-form-label">Username</label>
			  <div class="col-10">
				<input class="form-control" type="text" id="username" name="username">
			  </div>
			</div>
			<div class="form-group row" id="emailfield" style="display:none">
			  <label for="email" class="col-2 col-form-label">Email</label>
			  <div class="col-10">
				<input class="form-control" type="email" id="email" name="email">
			  </div>
			</div>
			<div class="form-group row">
			  <label for="password" class="col-2 col-form-label">Password</label>
			  <div class="col-10">
				<input class="form-control" type="password" id="password" name="password">
			  </div>
			</div>
			  <div class="form-check">
				  <input name="register" type="hidden" value="0" id="isReg">
			  </div>
				<button type="submit" class="btn btn-primary" id="Register">Login</button><a href="#"><div style="float:right;" id="LoginSignup">Register</div></a>
		</form>
		  <p><?php echo $success; ?></p>			
	</div>
	

	
<?php include 'views/footer.php'; ?>

<script>

	$("#LoginSignup").click(function() {
		if ($("#isReg").val() == "0") {
			$("#Register").html("Register");
			$("#LoginSignup").html("Login");
			$("#emailfield").toggle();
			$("#isReg").val("1");
		} else {
			$("#Register").html("Login");
			$("#LoginSignup").html("Register");
			$("#emailfield").toggle();
			$("#isReg").val("0");
		}
	});

</script>