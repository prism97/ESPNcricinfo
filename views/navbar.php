<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>ESPNcricinfo</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="http://localhost/ESPNcricinfo/stylesheets/navbar.css">
</head>
<body>
	<div id="navbar">
		<a href="http://localhost/ESPNcricinfo/views/homepage.view.php">Home</a>
		<a href="javascript:void(0)">Live scores</a>
		<a href="http://localhost/ESPNcricinfo/views/series.view.php">Series</a>
		<a href="javascript:void(0)">Teams</a>
		<a href="javascript:void(0)">Stats</a>

		<?php
			if(isset($_SESSION['curr_user_id']))
			{
				echo "<a href='http://localhost/ESPNcricinfo/includes/logout.inc.php'>Logout</a>";
			}
			else 
			{
				echo "<a href='http://localhost/ESPNcricinfo/views/login.php'>Login</a>";
				echo "<a href='http://localhost/ESPNcricinfo/views/signup.php'>Signup</a>";
			}
		?>

		<!-- <a href="http://localhost/ESPNcricinfo/views/login.php">Login</a>
		<a href="http://localhost/ESPNcricinfo/views/signup.php">Signup</a> -->
		<a href="javascript:void(0)" class="material-icons" id="search-icon">search</a> 
	</div>

	<script>
		window.onscroll = function() {myFunction()};

		var navbar = document.getElementById("navbar");
		var sticky = navbar.offsetTop;

		function myFunction() {
		  if (window.pageYOffset >= sticky) {
		    navbar.classList.add("sticky")
		  } else {
		    navbar.classList.remove("sticky");
		  }
		}
	</script>
</body>
</html>