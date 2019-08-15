<?php 
	require 'navbar.php'; 
	$bat_style_text = ['left-hand bat', 'right-hand bat'];
	$match_type_text = ['Tests', 'ODIs', 'T20s'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="http://localhost/ESPNcricinfo/stylesheets/player.css">
	<style>
		table {
			margin: 10px;
		}
		table, th, td {
			border: 1px solid #000000;
			border-collapse: collapse;	
		}
		th,td {
			padding: 5px;
		}
	</style>
</head>
<body>
	<?php 
		require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';
		$pid = $_GET['playerid'];
		$team_name = $_GET['teamname'];
		$p_res = pg_query_params("SELECT *, AGE(birth_date) AS age  FROM player WHERE player_id = $1", Array($pid));
		$p_info = pg_fetch_assoc($p_res, 0);
		$name = $p_info['name'];
		$birth_date = $p_info['birth_date'];
		$birth_place = $p_info['birth_place'];
		$birth_info = $birth_date.' , '.$birth_place;
		$age = $p_info['age'];
		$role = $p_info['playing_role'];
		$bat_style_int = $p_info['batting_style'];
		$bat_style = $bat_style_text[$bat_style_int-1];
		$bowl_style = $p_info['bowling_style'];

		$bat_gen = pg_prepare($dbconn, "bat_query", 
		"SELECT (SELECT COUNT(*) FROM squad WHERE player_id=p.player_id AND match_id IN (SELECT match_id FROM match WHERE type=$1) GROUP BY player_id) AS Matches, COUNT(*) AS Innings, (COUNT(*)-COUNT(p.wicket_type)) NotOut, SUM(p.runs) AS Runs, (SUM(p.runs)/COUNT(p.wicket_type)) AS Average, (100*(SUM(p.runs)/SUM(p.balls))) AS StrikeRate, SUM(p.fours) AS Fours, SUM(p.sixes) AS Sixes
		FROM player_score p
		WHERE p.type = 1 AND p.player_id = $2 AND p.match_id IN (SELECT match_id FROM match WHERE type=$1)
		GROUP BY p.player_id"
		) or die(pg_last_error($dbconn));

		$bowl_gen = pg_prepare($dbconn, "bowl_query", 
		"SELECT (SELECT COUNT(*) FROM squad WHERE player_id=p.player_id AND match_id IN (SELECT match_id FROM match WHERE type=$1) GROUP BY player_id) Matches, COUNT(*) Innings, SUM(p.balls) Balls, SUM(p.runs) Runs, SUM(p.wickets) Wickets, 
			(SELECT (p2.runs||'/'||p2.wickets) FROM player_score p2 WHERE p2.player_id = p.player_id AND match_id IN (SELECT match_id FROM match WHERE type=$1) ORDER BY p2.wickets DESC, p2.runs ASC LIMIT 1) BBI,
			(SUM(p.runs)/SUM(p.wickets)) Average, (SUM(p.runs)/((SUM(p.balls)-SUM(p.extras))/6)) Economy
		FROM player_score p
		WHERE p.type = 2 AND p.player_id = $2 AND p.match_id IN (SELECT match_id FROM match WHERE type=$1)
		GROUP BY p.player_id"
		) or die(pg_last_error($dbconn));

	?>
	<div class="container-div">
		<?php 
			echo "<h1>$name</h1>";
			echo "<h3>$team_name</h1>";
		?>
		<hr style="background-color: #E0E0E0; height: 1px; border: 0; margin: 0;">
		<?php
			echo "<div><span class='attr'>Born </span><span>$birth_info</span></div>";
			echo "<div><span class='attr'>Current age </span><span>$age</span></div>";  
			echo "<div><span class='attr'>Playing role </span><span>$role</span></div>";
			echo "<div><span class='attr'>Batting style </span><span>$bat_style</span></div>";
			echo "<div><span class='attr'>Bowling style </span><span>$bowl_style</span></div>";
		?>
		<table>
			<caption>Batting Records</caption>
			<tr>
				<th>Match Type</th>
				<th>Matches</th>
				<th>Innings</th>
				<th>NotOut</th>
				<th>Runs</th>
				<th>Average</th>
				<th>StrikeRate</th>
				<th>Fours</th>
				<th>Sixes</th>
			</tr>
			<?php 
				for($x = 1; $x <= 3; $x++) {
					$bat_res = pg_execute($dbconn, "bat_query", Array($x, $pid)) or die(pg_last_error($dbconn));
					$bat = pg_fetch_all($bat_res);
    				echo '<tr>';
    				echo '<td>'.$match_type_text[$x-1].'</td>';
    				echo '<td>'.$bat[0]['matches'].'</td>';
    				echo '<td>'.$bat[0]['innings'].'</td>';
    				echo '<td>'.$bat[0]['notout'].'</td>';
    				echo '<td>'.$bat[0]['runs'].'</td>';
    				echo '<td>'.$bat[0]['average'].'</td>';
    				echo '<td>'.$bat[0]['strikerate'].'</td>';
    				echo '<td>'.$bat[0]['fours'].'</td>';
    				echo '<td>'.$bat[0]['sixes'].'</td>';
    				echo '</tr>';
				}
			?>
		</table>
		<table>
			<caption>Bowling Records</caption>
			<tr>
				<th>Match Type</th>
				<th>Matches</th>
				<th>Innings</th>
				<th>Balls</th>
				<th>Runs</th>
				<th>Wickets</th>
				<th>BBI</th>
				<th>Average</th>
				<th>Economy</th>
			</tr>
			<?php 
				for($x = 1; $x <= 3; $x++) {
					$bowl_res = pg_execute($dbconn, "bowl_query", Array($x, $pid)) or die(pg_last_error($dbconn));
					$bowl = pg_fetch_all($bowl_res);
    				echo '<tr>';
    				echo '<td>'.$match_type_text[$x-1].'</td>';
    				echo '<td>'.$bowl[0]['matches'].'</td>';
    				echo '<td>'.$bowl[0]['innings'].'</td>';
    				echo '<td>'.$bowl[0]['balls'].'</td>';
    				echo '<td>'.$bowl[0]['runs'].'</td>';
    				echo '<td>'.$bowl[0]['wickets'].'</td>';
    				echo '<td>'.$bowl[0]['bbi'].'</td>';
    				echo '<td>'.$bowl[0]['average'].'</td>';
    				echo '<td>'.$bowl[0]['economy'].'</td>';
    				echo '</tr>';
				}
			?>
		</table>
	</div>
</body>
</html>