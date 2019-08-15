<?php 
	require 'navbar.php';
	require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';
	$mtype=$_GET['mtype'];
	$qtype=$_GET['qtype'];
	$qno=$_GET['qno'];
	$player_gen = pg_prepare($dbconn, "player_name_query", "SELECT name FROM player WHERE player_id=$1");
	$head = array(array("Wins", "Runs", "Runs"),array("Runs", "Runs", "Sixes"),array("Wickets", "Runs", "Runs"));
	$queries = array(
		array(
			"SELECT t.team_id AS tid, COUNT(m.result) AS data
			FROM team AS t, match AS m
			WHERE m.result = t.team_id AND m.type =".$mtype."
			GROUP BY tid
			ORDER BY data DESC",
			"SELECT team_id AS tid, MAX(runs) AS data
			FROM team_score
			WHERE match_id IN (SELECT match_id FROM match WHERE result IS NOT NULL AND type=".$mtype.")
			GROUP BY tid
			ORDER BY data DESC",
			"SELECT team_id AS tid, MIN(runs) AS data
			FROM team_score
			WHERE match_id IN (SELECT match_id FROM match WHERE result IS NOT NULL AND type=".$mtype.")
			GROUP BY tid
			ORDER BY data ASC"
		),
		array(
			"SELECT player_id AS pid, SUM (runs) AS data
			FROM player_score
			WHERE type = 1 AND match_id IN (SELECT match_id FROM match WHERE type=".$mtype.")
			GROUP BY pid
			ORDER BY data DESC",
			"SELECT player_id AS pid, MAX(runs) AS data
			FROM player_score
			WHERE type = 1 AND match_id IN (SELECT match_id FROM match WHERE type=".$mtype.")
			GROUP BY pid
			ORDER BY data DESC",
			"SELECT player_id AS pid, SUM(sixes) AS data
			FROM player_score
			WHERE type = 1 AND match_id IN (SELECT match_id FROM match WHERE type=".$mtype.")
			GROUP BY pid
			ORDER BY data DESC"
		),
		array(
			"SELECT player_id AS pid, SUM (wickets) AS data
			FROM player_score
			WHERE type = 2 AND match_id IN (SELECT match_id FROM match WHERE type=".$mtype.")
			GROUP BY pid
			ORDER BY data DESC",
			"SELECT player_id AS pid, SUM(runs) AS data
			FROM player_score 
			WHERE type = 2 AND match_id IN (SELECT match_id FROM match WHERE type=".$mtype.")
			GROUP BY pid
			ORDER BY data DESC",
			"SELECT player_id AS pid, MAX(runs) AS data
			FROM player_score 
			WHERE type = 2 AND match_id IN (SELECT match_id FROM match WHERE type=".$mtype.")
			GROUP BY pid
			ORDER BY data DESC"
		)
	);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<style>
		table, td, th {
			border: 1px solid #E0E0E0;
		}
		th, td {
			padding: 5px;
		}
		table {
			width: 30%;
			background-color: #ffffff;
			margin: 20px auto;
			border-collapse: collapse;
		}
	</style>
</head>
<body>
	<?php 
		$run_query = pg_query($queries[$qtype][$qno]);
		$res = pg_fetch_all($run_query);
	?>
	<table>
		<tr>
			<?php if($qtype == 0) { echo "<th>Team</th>"; }
					else { echo "<th>Player</th>"; } ?>
			<th><?php echo $head[$qtype][$qno]; ?></th>
		</tr>
	<?php
		foreach ($res as $row) {
			if($qtype == 0)
			{
				$pn_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", array($row['tid']));
				$pn = pg_fetch_result($pn_res, 0, 0);
			}
			else 
			{
				$pn_res = pg_execute($dbconn, "player_name_query", array($row['pid']));
				$pn = pg_fetch_result($pn_res, 0, 0);
			}
	?>
		<tr>
			<td><?php echo $pn; ?></td>
			<td><?php echo $row['data']; ?></td>
		</tr>
	<?php } ?>
		
	</table>
</body>
</html>