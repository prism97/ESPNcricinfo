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
		<a href="http://localhost/ESPNcricinfo/views/livescore.php">Live scores</a>
		<a href="http://localhost/ESPNcricinfo/views/serieslist.php">Series</a>
		<a href="http://localhost/ESPNcricinfo/views/teams.view.php">Teams</a>
		<a href="http://localhost/ESPNcricinfo/views/stats.view.php">Stats</a>

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

		<a href="javascript:void(0)" class="material-icons" id="search-icon">search</a> 
	</div>
</body>
</html>