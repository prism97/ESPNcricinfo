<?php 
	require 'navbar.php';
	require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';
	$match_types = ['Test', 'ODI', 'T20'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://localhost/ESPNcricinfo/stylesheets/match.css">
	<style>
		.matchbox {
			background-color: #ffffff;
			margin-bottom: 20px;
			width: 30%;
			padding: 10px;
			display: inline-block;
		}
		#container {
			display: flex;
			flex-direction: column;
		}
		.score-show h3, h4 {
		  margin: 10px 0 5px;
		}
		.score-show {
		  width: 40%;
		  display: flex;
		  flex-direction: row;
		  align-items: center;
		  justify-content: space-between;
		}
	</style>
</head>
<body>
	<div id="container">
		<?php
			$matches_res = pg_query("SELECT *, to_char(date, 'Mon DD YYYY') AS mdate FROM match WHERE CURRENT_DATE between date AND date+5");
			$matches = pg_fetch_all($matches_res);
			$score_gen = pg_prepare($dbconn, "score_query", 'SELECT team_id, runs, wickets FROM team_score WHERE match_id=$1 AND innings=$2 AND match_order=$3');

			$team_score_gen = pg_prepare($dbconn, "team_score_query", 'SELECT * FROM team_score WHERE match_id=$1 AND innings=$2 AND team_id=$3');
			foreach ($matches as $m) {
				$team1 = $m['team_1'];
				$team2 = $m['team_2'];
				$mtype = $m['type'];
				$mvenue = $m['venue'];
				$mdate = $m['mdate'];
				$mid = $m['match_id'];
				$t1_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($team1));
				$t1 = pg_fetch_result($t1_res, 0, 0);
				$t2_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($team2));
				$t2 = pg_fetch_result($t2_res, 0, 0);
				echo '<a href="'.htmlspecialchars("livecomment.php?mid=".urlencode($mid)).'">'; 
				echo '<div class="matchbox">';
				echo '<p>'.$mvenue.', '.$mdate.'</p>';

				if($mtype == 1)
				{
					$o11_res = pg_execute($dbconn, "score_query", Array($mid, 1, 1));
					$o11 = pg_fetch_assoc($o11_res, 0);

					if($o11['team_id'] == $team1)
					{
						$o1_team_name = $t1;
						$o2_team = $team2;
						$o2_team_name = $t2;
					}
					else if($o11['team_id'] == $team2)
					{
						$o1_team_name = $t2;
						$o2_team = $team1;
						$o2_team_name = $t1;
					}

					$o12_res = pg_execute($dbconn, "team_score_query", Array($mid, 2, $o11['team_id']));
					$o12 = pg_fetch_assoc($o12_res, 0);
					if(is_null($o11['runs']))
					{
						echo '<div class="score-show"><h3>'.$o1_team_name.'</h3></div>'; 
					}
					else if(is_null($o12['runs']))
					{
						echo '<div class="score-show"><h3>'.$o1_team_name.'</h3><h4>'.$o11['runs'].'/'.$o11['wickets'].'</h4></div>';
					}
					else 
					{
						echo '<div class="score-show"><h3>'.$o1_team_name.'</h3><h4>'.$o11['runs'].'/'.$o11['wickets'].' & '.$o12['runs'].'/'.$o12['wickets'].'</h4></div>';
					}
					$o21_res = pg_execute($dbconn, "team_score_query", Array($mid, 1, $o2_team));
					$o21 = pg_fetch_assoc($o21_res, 0);
					$o22_res = pg_execute($dbconn, "team_score_query", Array($mid, 2, $o2_team));
					$o22 = pg_fetch_assoc($o22_res, 0);
					if(is_null($o21['runs']))
					{
						echo '<div class="score-show"><h3>'.$o2_team_name.'</h3></div>';
					}
					else if(is_null($o22['runs']))
					{
						echo '<div class="score-show"><h3>'.$o2_team_name.'</h3><h4>'.$o21['runs'].'/'.$o21['wickets'].'</h4></div>';
					}
					else 
					{
						echo '<div class="score-show"><h3>'.$o2_team_name.'</h3><h4>'.$o21['runs'].'/'.$o21['wickets'].' & '.$o22['runs'].'/'.$o22['wickets'].'</h4></div>';
					}
				}
				else 
				{
					$o1_res = pg_execute($dbconn, "score_query", Array($mid, 0, 1));
					$o1 = pg_fetch_assoc($o1_res, 0);
					if($o1['team_id'] == $team1)
					{
						$o1_team_name = $t1;
						$o2_team = $team2;
						$o2_team_name = $t2;
					}
					else if($o1['team_id'] == $team2)
					{
						$o1_team_name = $t2;
						$o2_team = $team1;
						$o2_team_name = $t1;
					}
					$o2_res = pg_execute($dbconn, "team_score_query", Array($mid, 0, $o2_team));
					$o2 = pg_fetch_assoc($o2_res, 0);
					if(is_null($o1['runs']))
					{
						echo '<div class="score-show"><h3>'.$t1.'</h3></div>';
						echo '<div class="score-show"><h3>'.$t2.'</h3></div>';
					}
					else if(is_null($o2['runs']))
					{
						echo '<div class="score-show"><h3>'.$o1_team_name.'</h3><h4>'.$o1['runs'].'/'.$o1['wickets'].'</h4></div>';
						echo '<div class="score-show"><h3>'.$o2_team_name.'</h3></div>';
					}
					else
					{
						echo '<div class="score-show"><h3>'.$o1_team_name.'</h3><h4>'.$o1['runs'].'/'.$o1['wickets'].'</h4></div>';
						echo '<div class="score-show"><h3>'.$o2_team_name.'</h3><h4>'.$o2['runs'].'/'.$o2['wickets'].'</h4></div>';
					}
				}

				if(!is_null($m['result']))
				{
					pg_query($dbconn, "BEGIN;");
					$result = pg_query_params("SELECT * FROM result_generator($1);", Array($mid));
					$res = pg_fetch_result($result, 0,0);
					echo '<p>'.$res.'</p>';
				}
				echo '</div></a>';
			}
		?>
	</div>
</body>
</html>