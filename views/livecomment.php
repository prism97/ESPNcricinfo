<?php 
	session_start();
	require 'navbar.php';
	require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';
	$mid = $_GET['mid'];
	$teams_res = pg_query_params("SELECT *, to_char(date, 'Mon DD YYYY') AS mdate FROM match WHERE match_id=$1", Array($mid));
	$teams_list = pg_fetch_assoc($teams_res);
	$team1 = $teams_list['team_1'];
	$team2 = $teams_list['team_2'];
	$mtype = $teams_list['type'];
	$mres = $teams_list['result'];
	$t1_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($team1));
	$t1 = pg_fetch_result($t1_res, 0, 0);
	$t2_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($team2));
	$t2 = pg_fetch_result($t2_res, 0, 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<style>
		#container {
			background-color: white;
			margin: 20px;
			padding: 10px;
		}
		form {
			display: flex;
			flex-direction: column;
			align-items: center;
		}
	</style>
</head>
<body>
	<div id="container">
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

	<div class="sects" id="comm-show-div"></div>
	<?php if($_SESSION['curr_user_type'] == 'general') : ?>
		<div class="sects">
			<form action="http://localhost/ESPNcricinfo/includes/addUserComment.php" method="post">
				<input type="hidden" name="mid" id="mid">
				<input type="hidden" name="tid" id="tid">
				<input type="hidden" name="inn" id="inn">
				<textarea name="comm-box" id="comm-box" cols="30" rows="10"></textarea>
				<button type="submit" name="post-comm" id="post-comm">Post Comment</button>
			</form>
		</div>
	<?php endif; ?>
	</div>

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
			$("#comm-show-div").load("http://localhost/ESPNcricinfo/views/showComment.php?mid="+<?php echo json_encode($mid) ?>+"&tid=" + s_team + "&inn=" + s_inn + "&query=" + $("#comm-type-select").val());
			$("#tid").val(s_team);
			$("#inn").val(s_inn);
			$("#mid").val(<?php echo json_encode($mid) ?>);
		}


		$(document).ready(loadCommentInput($("#bat-inn-select").val()));
		$("#bat-inn-select").change(function() {
			loadCommentInput($("#bat-inn-select").val());
			$("#comm-show-div").trigger("change");
			$("#tid").trigger("change");
			$("#inn").trigger("change");
		});
		$("#comm-type-select").change(function() {
			loadCommentInput($("#bat-inn-select").val());
			$("#comm-show-div").trigger("change");
			$("#tid").trigger("change");
			$("#inn").trigger("change");
		});
	</script>
</body>
</html>