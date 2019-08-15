<?php require 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="http://localhost/ESPNcricinfo/stylesheets/teaminfo.css">
	<style>
		#tab1 {
			flex-direction: column;
		}
		#tab1 a {
			text-decoration: none;
  			color: #565656;
		}
		.matchbox {
			background-color: #E0E0E0;
			margin: 20px;
			width: 20%;
			padding: 10px;
			display: inline-block;
		}
		#pl {
			flex-direction: row;
		}
	</style>
</head>
<body>
	<?php 
		require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';
		$name = $_GET['teamname'];
		$id_res = pg_query_params("SELECT team_id FROM team WHERE name = $1", Array($name));
		$tid = pg_fetch_result($id_res, 0, 0);
		$match_types = ['Test', 'ODI', 'T20'];
	?>
	<div class="container-div">
		<?php echo "<h1 id='namehead'>$name</h1>"; ?>
		<hr style="background-color: #E0E0E0; height: 1px; border: 0; margin: 0;">
		<div class="tab-container">
			<div class="button-container">
				<button class="tab-btn" onclick="showTab(0)">Fixtures & Results</button>
				<button class="tab-btn" onclick="showTab(1)">Players</button>
			</div>
			<div class="tab-content" id="tab1">
				<?php 
					
				$matches_res = pg_query_params("SELECT *, to_char(date, 'Mon DD YYYY') AS mdate FROM match WHERE team_1 = $1 OR team_2 = $1 ORDER BY date ASC", Array($tid));
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
			<div class="tab-content" id="pl">
				<?php 
					
					$player_res = pg_query_params("SELECT * FROM player WHERE team_id = $1", Array($tid));
					$player_list = pg_fetch_all($player_res);
					$num_players = pg_num_rows($player_res);
					$n_players = ceil($num_players/8);
					for($n = 1; $n <= $n_players; $n++){
				?>
				<div>
				<ul style="list-style-type: none;">
					<?php 
						$m = ($n * 8) - 8;
						if($num_players < $n * 8) { $s = $num_players; }
						else { $s = $n * 8; }
						while($m < $s)
						{
							echo '<li><a href="'.htmlspecialchars("player.view.php?teamname=".urlencode($name)."&playerid=".urlencode($player_list[$m]['player_id'])).'">'.$player_list[$m]['name'].'</a></li>';
							$m = $m + 1;
						}
					?>
				</ul>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<script>
		var tabButtons = document.querySelectorAll(".container-div .tab-container .button-container .tab-btn");
		var tabContents = document.querySelectorAll(".container-div .tab-container .tab-content");
		function showTab(tabIndex) {
			tabContents.forEach(function(node) {
				node.style.display = "none";
			});
			tabContents[tabIndex].style.display = "flex";
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