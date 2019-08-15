<?php 
	session_start();
	require 'navbar.php';
	require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';
	$mid = $_GET['mid'];
	$_SESSION['mid'] = $mid;

	$teams_res = pg_query_params("SELECT *, to_char(date, 'Mon DD YYYY') AS mdate FROM match WHERE match_id=$1", Array($mid));
	$teams_list = pg_fetch_assoc($teams_res);
	$team1 = $teams_list['team_1'];
	$team2 = $teams_list['team_2'];
	$mtype = $teams_list['type'];
	$mvenue = $teams_list['venue'];
	$mdate = $teams_list['mdate'];
	$mres = $teams_list['result'];
	$mmom = $teams_list['man_of_the_match'];
	$t1_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($team1));
	$t1 = pg_fetch_result($t1_res, 0, 0);
	$t2_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($team2));
	$t2 = pg_fetch_result($t2_res, 0, 0);

	$score_gen = pg_prepare($dbconn, "score_query", 'SELECT team_id, runs, wickets FROM team_score WHERE match_id=$1 AND innings=$2 AND match_order=$3');

	$team_score_gen = pg_prepare($dbconn, "team_score_query", 'SELECT * FROM team_score WHERE match_id=$1 AND innings=$2 AND team_id=$3');

	$player_gen = pg_prepare($dbconn, "player_name_query", "SELECT name FROM player WHERE player_id=$1");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="http://localhost/ESPNcricinfo/stylesheets/match.css"> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<div id="container">
		<div class="button-container">
			<button class="tab-btn" onclick="showTab(0)" style="border-top-left-radius: 10px;">Summary</button>
			<button class="tab-btn" onclick="showTab(1)">Scorecard</button>
			<button class="tab-btn" onclick="showTab(2)" style="border-top-right-radius: 10px;">Commentary</button>
		</div>
		<div class="content-container">
			<hr style="background-color: #E0E0E0; height: 1px; border: 0; margin: 0;">
			<div class="tab-content">
				<?php 
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
					if(!is_null($mres))
					{
						pg_query($dbconn, "BEGIN;");
						$result = pg_query_params("SELECT * FROM result_generator($1);", Array($mid));
						$res = pg_fetch_result($result, 0,0);
						echo '<p>'.$res.'</p>';
					}
					if(!is_null($mmom))
					{
						pg_query($dbconn, "BEGIN;");
						$mom_res = pg_query_params("SELECT * FROM mom_generator($1);", Array($mid));
						$mom = pg_fetch_result($mom_res, 0,0);
						echo '<h3>Man of the match</h3>'.'<p>'.$mom.'</p>';
					}
					$player_res1 = pg_query_params("SELECT name FROM player WHERE player_id IN (SELECT player_id FROM squad WHERE team_id = $1 AND match_id = $2)", Array($team1, $mid));
					$player_list1 = pg_fetch_all($player_res1);
					$player_res2 = pg_query_params("SELECT name FROM player WHERE player_id IN (SELECT player_id FROM squad WHERE team_id = $1 AND match_id = $2)", Array($team2, $mid));
					$player_list2 = pg_fetch_all($player_res2);
				?>
				
				<h3>SQUAD</h3>
				<table>
					<tr>
						<th><?php echo $t1; ?></th>
					</tr>
					<?php 
						foreach ($player_list1 as $pl) {
							echo '<tr><td>'.$pl['name'].'</td></tr>';
						}
					?>
				</table>
				<table>
					<tr>
						<th><?php echo $t2; ?></th>
					</tr>
					<?php 
						foreach ($player_list2 as $pl) {
							echo '<tr><td>'.$pl['name'].'</td></tr>';
						}
					?>
				</table>
			</div>
			<div class="tab-content">
				<?php 
					if ($mtype == 1) 
					{
						$inn = 1;
						$order = 1;
						(include $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/views/scorecard.php') or die("oops");
						echo '<br>';
						$order = 2;
						(include $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/views/scorecard.php') or die("oops");
						echo '<br>';
						$inn = 2;
						$order = 1;
						(include $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/views/scorecard.php') or die("oops");
						echo '<br>';
						$order = 2;
						(include $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/views/scorecard.php') or die("oops");
						echo '<br>';
					}
					else 
					{
						$inn = 0;
						$order = 1;
						(include $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/views/scorecard.php') or die("oops");
						echo '<br>';
						$order = 2;
						(include $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/views/scorecard.php') or die("oops");
						echo '<br>';
					}
				?>
			</div>
			<div class="tab-content">
				<div class="sects">
					<select id="bat-inn-select">
					<?php if($mtype == 1) : ?>
						<option selected value="11"><?php echo $t1.' 1st Innings'; ?></option>
						<option value="21"><?php echo $t2.' 1st Innings'; ?></option>
						<option value="12"><?php echo $t1.' 2nd Innings'; ?></option>
						<option value="22"><?php echo $t2.' 2nd Innings'; ?></option>
					<?php else : ?>
						<option selected value="1"><?php echo $t1.' Innings'; ?></option>
						<option value="2"><?php echo $t2.' Innings'; ?></option>
					<?php endif; ?>
					</select>

					<select id="comm-type-select">
						<option selected value="1">Full commentary</option>
						<option value="2">Wickets</option>
						<option value="3">Boundary</option>
					</select>
				</div>
				<div class="sects" id="comm-input-div"></div>
				<div class="sects" id="comm-show-div"></div>

				<script>
					function loadCommentInput(val) {
						var s_team;
						var s_inn;
						switch (val) {
							case '11':
								s_team = <?php echo json_encode($team1) ?> ;
								s_inn = 1; 
								break;
							case '21':
								s_team = <?php echo json_encode($team2) ?> ;
								s_inn = 1; 
								break;
							case '12':
								s_team = <?php echo json_encode($team1) ?> ;
								s_inn = 2; 
								break;
							case '22':
								s_team = <?php echo json_encode($team2) ?> ;
								s_inn = 2; 
								break;
							case '1':
								s_team = <?php echo json_encode($team1) ?> ;
								s_inn = 0; 
								break;
							case '2':
								s_team = <?php echo json_encode($team2) ?> ;
								s_inn = 0; 
								break;
							default:
								break;
						}
						$("#comm-input-div").load("http://localhost/ESPNcricinfo/views/inputCommentAdmin.php?tid=" + s_team + "&inn=" + s_inn);
						$("#comm-show-div").load("http://localhost/ESPNcricinfo/views/showComment.php?mid="+<?php echo json_encode($mid) ?>+"&tid=" + s_team + "&inn=" + s_inn + "&query=" + $("#comm-type-select").val());
					}


					$(document).ready(loadCommentInput($("#bat-inn-select").val()));
					$("#bat-inn-select").change(function() {
						loadCommentInput($("#bat-inn-select").val());
						$("#comm-input-div").trigger("change");
						$("#comm-show-div").trigger("change");
					});
					$("#comm-type-select").change(function() {
						loadCommentInput($("#bat-inn-select").val());
						$("#comm-input-div").trigger("change");
						$("#comm-show-div").trigger("change");
					});
				</script>

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