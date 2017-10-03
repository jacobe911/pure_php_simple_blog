<?php

session_start();

print_r($_SESSION);

?>
<html>

	<head>

		<link rel="stylesheet" href="/test/comments/commentstyles.css" type="text/css"> 

		<style>
		body{padding:0;margin:0}
		</style>
		
	</head>

	<body>

		<?php include 'commentSection.php'; ?>				

	</body>

</html>