<?php
session_start();

if(empty($_SESSION['id'])) {
	header ('Location:index.php');
}

if($_SESSION['id'] != "1") {
	header ('Location:index.php');
}

include 'config.php';
include 'functions/functions.php';

include 'views/header.php';
?>
		
		<div class="container" style="background-color:#ddd;">

				
				<table class="table table-sm">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>UserName</th>
					  <th>Password</th>
					  <th>Email</th>
					  <th>Reg Date</th>
					</tr>
				  </thead>
				  
				<?php 
					//perform query
					$stmt = $db_conn->query('SELECT * FROM users');
					
					//display results
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
					  <tbody>
						<tr>
						  <th scope="row"><?php echo $row['id']; ?></th>
						  <td><?php echo $row['username']; ?></td>
						  <td><?php echo $row['password']; ?></td>
						  <td><?php echo $row['email']; ?></td>
						  <td><?php echo $row['reg_date']; ?></td>
						</tr>
					  </tbody>
					<?php } ?>
				</table>
		
		</div>
	
	
	
<?php include 'views/footer.php'; ?>