<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<style>
		.form-div {
			max-width: 500px;
			margin: 50px auto;
			background-color: #03A8F8;
			padding: 40px;
			box-shadow: 5px 5px 10px #5F9F9F;
			font-size: 30px;
			font-family: "Arial";
		}

		form {
			display: flex;
			flex-direction: column;
			align-items: center;
		}
		.form-inp {
			margin-bottom: 20px;
		}
		.form-inp > input {
			border: 0;
			width: 260px;
			padding: 10px 20px;
			outline: none;
		}
		.form-inp > button {
			border: 2px solid #ffffff;
			padding: 10px 20px;
			background-color: #03A8F8; 
			color: #ffffff;
			cursor: pointer;
			transition: all 0.5s ease;
			font-weight: bolder;
		}
		.form-inp > button:hover {
			background-color: #ffffff; 
			color: #03A8F8;
		}
	</style>
</head>
<body>
	<?php require 'navbar.php'; ?>
	<div class="form-div">
		<form action="http://localhost/ESPNcricinfo/includes/signup.inc.php" method="post">
			<div class="form-inp"><input type="text" name="su-name" placeholder="Enter username..."></div>
			<div class="form-inp"><input type="password" name="spwd" placeholder="Enter password..."></div>
			<div class="form-inp"><input type="password" name="spwd-repeat" placeholder="Retype password..."></div>
			<div class="form-inp"><button type="submit" name="signup-submit">SIGNUP</button></div>
		</form>
	</div>
</body>
</html>
