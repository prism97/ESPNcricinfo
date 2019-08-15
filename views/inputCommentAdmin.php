<?php 
	session_start();
	$mid = $_SESSION['mid'];
	require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';
	$s_team = $_GET['tid'];
	$s_inn = $_GET['inn'];
	$_SESSION['s_team'] = $s_team;
	$_SESSION['s_inn'] = $s_inn;

	$checkWick_query = pg_query_params("SELECT wickets, match_order FROM team_score WHERE match_id=$1 AND team_id=$2 AND innings=$3", Array($mid, $s_team, $s_inn));
	$checkWick_res = pg_fetch_assoc($checkWick_query);
	$checkWick = $checkWick_res['wickets'];
	$checkOrder = $checkWick_res['match_order'];
	if(empty($checkWick)) { $checkWick = 0; }
	$_SESSION['checkOrder'] = $checkOrder;

	$wicket_types = ['bowled','caught','caught behind','leg before wicket','run out','stumped','non-strike batsman out'];
	$extras_types = ['no ball','bye','legbye','wide'];

	$tn_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($s_team)) or die('error');
	$tn = pg_fetch_result($tn_res, 0, 0);

	$teams_res2 = pg_query_params("SELECT team_1, team_2, type, result FROM match WHERE match_id=$1", Array($mid));
	$teams_list2 = pg_fetch_assoc($teams_res2);
	$teamid1 = $teams_list2['team_1'];
	$teamid2 = $teams_list2['team_2'];
	$mtype = $teams_list2['type'];
	$mresult = $teams_list2['result'];
	$_SESSION['mtype'] = $mtype;
	$_SESSION['mresult'] = $mresult;

	if($mtype == 1)
	{
		$b_const = 10000;
	}
	else if($mtype == 2)
	{
		$b_const = 300;
	}
	else if($mtype == 3)
	{
		$b_const = 120;
	}

	if($teamid1 == $s_team)
	{
		$b_team = $teamid2;
	}
	else 
	{
		$b_team = $teamid1;
	}
	$_SESSION['b_const'] = $b_const;
	$_SESSION['b_team'] = $b_team;

	$ball_res = pg_query_params("SELECT (MAX(ball_no)+1) AS ballnum FROM commentary_admin WHERE match_id=$1 AND batting_team_id=$2 AND innings=$3 AND (extras IS NULL OR extras=2 OR extras=3)", Array($mid, $s_team, $s_inn));
	$ball = pg_fetch_result($ball_res, 0, 0);
	if(empty($ball))
	{
		$ball = 1;
	}
?>

<?php if($_SESSION['curr_user_type'] == 'admin' && $ball <= $b_const && $checkWick < 10 && is_null($mresult)) : ?>
<form action="http://localhost/ESPNcricinfo/includes/addComment.php" id="commentary" method="post">
	<div class="parts">
	<div id="ball"><label for="ballno">Ball No.</label>
	<input readonly type="number" name="ball-no" id="ballno" value="<?php echo $ball; ?>"></div>

	<div id="bat-team"><label for="team-choice">Batting Team</label>
	<select readonly name="team-choice" id="team-choice">
  		<option selected value="<?php echo $s_team; ?>"><?php echo $tn; ?></option>
	</select></div>

	<div id="innings"><label for="inn">Innings</label>
		<select readonly id="inn" name="innings">
			<option selected value="<?php echo $s_inn; ?>"><?php echo $s_inn; ?></option>
		</select>
	</div>
	</div>

	<div class="parts">
	<div id="strike"><label for="strike-bat-choice">On-strike batsman</label>
	<select id="strike-bat-choice" name="strike-bat-choice"></select></div>

	<div id="non-strike"><label for="non-strike-bat-choice">Non-strike batsman</label>
	<select id="non-strike-bat-choice" name="non-strike-bat-choice"></select></div>

	<div id="bowl"><label for="bowler">Bowler</label>
	<select id="bowler" name="bowler"></select></div>

	<div id="field"><label for="fielder">Fielder</label>
	<select id="fielder" name="fielder"></select></div>
	</div>

	<div class="parts">
	<div class="choice">
		<input type="radio" name="chooseone" value="Runs">
		<label for="Runs">Runs</label>
		<select id="no-runs" name="no-runs">
			<option selected value=""></option>
			<option value="0">0</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
		</select>
	</div>
	<div class="choice"><input type="radio" name="chooseone" value="FOUR!"><label for="FOUR!">Four</label></div>
	<div class="choice"><input type="radio" name="chooseone" value="SIX!"><label for="SIX!">Six</label></div>
	
	<div><label for="extras-choice">Extras</label>
	<select id="extras-choice" name="extras-choice">
  		<option selected value="0"></option>
  		<?php 
  			for($a = 0; $a <= 3; $a++) 
  			{
  				echo '<option value="'. ($a+1) .'">'. $extras_types[$a] . '</option>';
  			}
  		?>
	</select></div>
	<div><label for="wicket-type">Wicket</label>
	<select id="wicket-type" name="wicket-type">
  		<option selected value="0"></option>
  		<?php 
  			for($a = 0; $a <= 6; $a++) 
  			{
  				echo '<option value="'. ($a+1) .'">'. $wicket_types[$a] . '</option>';
  			}
  		?>
	</select></div>
	</div>

	<div class="parts"><textarea id="comment" name="comm-text" cols="10" rows="5"></textarea></div>

	<div class="parts"><button type="submit" id="submit-button" name="add-comm">Add comment</button></div>

</form>



<script>
	function showPlayers() {
		var bowlTeam = "<?php echo $b_team; ?>";
		$("#strike-bat-choice").load("http://localhost/ESPNcricinfo/includes/fetchPlayers.php?tname=" + $("#team-choice").val());
		$("#non-strike-bat-choice").load("http://localhost/ESPNcricinfo/includes/fetchPlayers.php?tname=" + $("#team-choice").val());
		$("#bowler").load("http://localhost/ESPNcricinfo/includes/fetchPlayers.php?tname=" + bowlTeam);
		$("#fielder").load("http://localhost/ESPNcricinfo/includes/fetchPlayers.php?tname=" + bowlTeam);
	}

	$(document).ready(showPlayers);

	$("#strike-bat-choice").change(function() {
		var text = $("#bowler").val() + ' to ' + $("#strike-bat-choice").val();
		$("#comment").val(text);
		$("#comment").trigger("change");
	});

	$("#bowler").change(function() {
		var text = $("#bowler").val() + ' to ' + $("#strike-bat-choice").val();
		$("#comment").val(text);
		$("#comment").trigger("change");
	});

	$('.choice').change(function() {
		var selected_value = $("input[name='chooseone']:checked").val();
		var curr_comm = $("#bowler").val() + ' to ' + $("#strike-bat-choice").val();
		if(selected_value == 'Runs')
		{
			var nruns = $('#no-runs').val();
			if(nruns == 0)
			{
				$("#comment").val(curr_comm+', no run. ');
			}
			else if(nruns == 1)
			{
				$("#comment").val(curr_comm+', '+nruns+' run. ');
			}
			else 
			{
				$("#comment").val(curr_comm+', '+nruns+' runs. ');
			}
		}
		else 
		{
			$('#comment').val(curr_comm+', '+selected_value+' ');
		}
		$("#comment").trigger("change");
	});

	$('#wicket-type').change(function() {
		var wick = $('#wicket-type').val();
		var curr_comm = $("#bowler").val() + ' to ' + $("#strike-bat-choice").val();
		if(wick != "") 
		{
			$('#comment').val(curr_comm+', OUT! ');
		}
		$("#comment").trigger("change");
	});

	$('#extras-choice').change(function() {
		var extc = $('#extras-choice').val();
		var curr_comm = $("#bowler").val() + ' to ' + $("#strike-bat-choice").val();
		var ext_arr = ['no ball','bye','legbye','wide'];
		if(extc != "") 
		{
			$('#comment').val(curr_comm+', '+ext_arr[extc-1]+'. ');
		}
		$("#comment").trigger("change");
	});
</script> 
<?php endif; ?>