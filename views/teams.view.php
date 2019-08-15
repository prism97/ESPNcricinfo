<?php require 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<style>
		.team-div {
			background-color: #ffffff;
			box-sizing: border-box;
			max-width: 700px;
			margin: 50px auto;
			padding: 50px;
		}
		ul {
			padding: 0;
		}
		li {
			padding-bottom: 20px;
		}
		li a {
			text-decoration: none;
			font-weight: bold;
			color: #03A8F8;
		}
	</style>
</head>
<body>
	<?php 
		require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';
		
		$result = pg_query("SELECT * FROM team");
		if(!$result)
		{
			echo "query did not execute";
		}
		else 
		{
			$rows = pg_fetch_all_columns($result, 1);
		}
	?>
	<div class="team-div">
		<h1>Cricket Teams Index</h1>
		<hr style="background-color: #E0E0E0; height: 1px; border: 0;">
		<h3 style="color: #505050;">Popular International Teams</h3>
		<ul style="list-style-type: none;">
			<?php 
				foreach ($rows as $row) 
				{
					echo '<li><a href="'.htmlspecialchars("teaminfo.view.php?teamname=" .
        urlencode($row)).'">'.$row.'</a></li>';
				}
			?>
		</ul>
	</div>

	
</body>
</html>