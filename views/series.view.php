<?php 
	require 'navbar.php';
	require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';
	$sid = $_GET['sid'];
	$match_types = ['Test', 'ODI', 'T20'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://localhost/ESPNcricinfo/stylesheets/match.css">
	<style>
		.tab-content a {
			text-decoration: none;
  			color: #565656;
		}
		.matchbox {
			background-color: #E0E0E0;
			margin-bottom: 20px;
			width: 20%;
			padding: 10px;
			display: inline-block;
		}
	</style>
</head>
<body>
	<div id="container">
		<div class="button-container">
			<button class="tab-btn" onclick="showTab(0)" style="border-top-left-radius: 10px;">Fixtures</button>
		</div>
		<div class="content-container">
			<hr style="background-color: #E0E0E0; height: 1px; border: 0; margin: 0;">
			<div class="tab-content">
				<?php
					$matches_res = pg_query_params("SELECT *, to_char(date, 'Mon DD YYYY') AS mdate FROM match WHERE match_id IN (SELECT match_id FROM matches_in_series WHERE series_id=$1) ORDER BY date DESC", Array($sid));
					$matches = pg_fetch_all($matches_res);
					foreach ($matches as $m) {
						$team1 = $m['team_1'];
						$team2 = $m['team_2'];
						$mtype = $m['type'];
						$mvenue = $m['venue'];
						$mdate = $m['mdate'];
						$mres = $m['result'];
						$t1_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($team1));
						$t1 = pg_fetch_result($t1_res, 0, 0);
						$t2_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($team2));
						$t2 = pg_fetch_result($t2_res, 0, 0);
					?>
					<?php echo '<a href="'.htmlspecialchars("match.view.php?mid=".urlencode($m['match_id'])).'">'; ?>
        				<div class="matchbox">
							<?php echo '<p>'.$match_types[$mtype-1].', '.$mvenue.', '.$mdate.'</p>'; ?>
							<h3><?php echo $t1; ?></h3>
							<h3><?php echo $t2; ?></h3>
							<?php 
							if(!is_null($mres))
							{
								pg_query($dbconn, "BEGIN;");
								$result = pg_query_params("SELECT * FROM result_generator($1);", Array($m['match_id']));
								$res = pg_fetch_result($result, 0,0);
								echo '<p>'.$res.'</p>';
							}
							?>
						</div>
					</a>
				<?php } ?>
			</div>
		</div>
	</div>

	<script>
		var tabButtons = document.querySelectorAll("#container .button-container .tab-btn");
		var tabContents = document.querySelectorAll("#container .tab-content");
		function showTab(tabIndex) {
			tabContents.forEach(function(node) {
				node.style.display = "none";
			});
			tabContents[tabIndex].style.display = "flex";
			tabContents[tabIndex].style.flexDirection = "column";
			tabContents[tabIndex].style.justifyContent = "space-between";
			var i;
			for (i = 0; i < tabButtons.length; i++) {
  				tabButtons[i].style.backgroundColor = "#ffffff";
			}
			tabButtons[tabIndex].style.backgroundColor = "#efefef";
			tabButtons[tabIndex].style.outlineColor = "#efefef";
		}
		showTab(0);
	</script>
</body>
</html>