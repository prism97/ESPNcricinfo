<?php 
	session_start();
	require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';
	if(isset($_POST['add-comm']))
	{
		
		if($_POST['chooseone'] == 'Runs')
		{
			$runs = (int)$_POST['no-runs'];
			$fours = 0;
			$sixes = 0;
		}
		else if($_POST['chooseone'] == 'FOUR!')
		{
			$fours = 1;
			$runs = 0;
			$sixes = 0;
		}
		else if($_POST['chooseone'] == 'SIX!')
		{
			$sixes = 1;
			$runs = 0;
			$fours = 0;
		}
		else 
		{
			$runs = 0;
			$fours = 0;
			$sixes = 0;
		}

		if($_POST['innings'] == "")
		{
			$inn = 0;
		}
		else 
		{
			$inn = (int)$_POST['innings'];
		}
		

		$id_gen = pg_prepare($dbconn, "id_query", 'SELECT player_id FROM player WHERE name = $1');

		$s_bat_res = pg_execute($dbconn, "id_query", Array($_POST['strike-bat-choice']));
		$s_bat = pg_fetch_result($s_bat_res, 0, 0);

		$ns_bat_res = pg_execute($dbconn, "id_query", Array($_POST['non-strike-bat-choice']));
		$ns_bat = pg_fetch_result($ns_bat_res, 0, 0);

		$bowl_res = pg_execute($dbconn, "id_query", Array($_POST['bowler']));
		$bowl = pg_fetch_result($bowl_res, 0, 0);

		$field_res = pg_execute($dbconn, "id_query", Array($_POST['fielder']));
		$field = pg_fetch_result($field_res, 0, 0);
		if(empty($field)) { $field = 0; }


		$comment_res = 'INSERT INTO commentary_admin(
	ball_no, match_id, batting_team_id, innings, batsman_strike, batsman_non_strike, bowler, fielder, wicket_type, extras, runs, fours, sixes,  comment)
	VALUES ('.$_POST['ball-no'].','.$_SESSION['mid'].','.$_POST['team-choice'].','.$inn.','.$s_bat.','.$ns_bat.','.$bowl.','."NULLIF(".$field.",0)".','.'NULLIF('.(int)$_POST['wicket-type'].',0)'.','.'NULLIF('.(int)$_POST['extras-choice'].',0)'.','.$runs.','.$fours.','.$sixes.','."'".$_POST['comm-text']."'".')';

		$result = pg_query($comment_res) or die(pg_last_error($dbconn));


		//update result section
		$mid = $_SESSION['mid'];
		$s_team = $_SESSION['s_team'];
		$s_inn = $_SESSION['s_inn'];
		$checkOrder = $_SESSION['checkOrder'];
		$b_const = $_SESSION['b_const'];
		$b_team = $_SESSION['b_team'];
		$ext = (int)$_POST['extras-choice'];
		$mtype = $_SESSION['mtype'];
		$mresult = $_SESSION['mresult'];

		$checkWick_res = pg_query_params("SELECT wickets FROM team_score WHERE match_id=$1 AND team_id=$2 AND innings=$3", Array($mid, $s_team, $s_inn));
		$checkWick = pg_fetch_result($checkWick_res, 0, 0);

		$run_gen = pg_prepare($dbconn, "run_query", "SELECT runs FROM team_score WHERE match_id=$1 AND team_id=$2 AND innings=$3");

		if(is_null($mresult)) 
		{
			if(($s_inn == 0)&&($checkOrder == 2))
			{
				if((($_POST['ball-no'] == $b_const)&&($ext != 1)&&($ext != 4))||($checkWick >= 10))
				{
					$t1_runs_res = pg_query_params("SELECT runs FROM team_score WHERE match_id=$1 AND team_id=$2 AND innings=$3", Array($mid, $b_team, $s_inn));
					$t1_runs = pg_fetch_result($t1_runs_res, 0, 0);
					$t2_runs_res = pg_query_params("SELECT runs FROM team_score WHERE match_id=$1 AND team_id=$2 AND innings=$3", Array($mid, $s_team, $s_inn));
					$t2_runs = pg_fetch_result($t2_runs_res, 0, 0);
					if($t2_runs > $t1_runs)
					{
						$w_team = $s_team;
					}
					else if($t2_runs < $t1_runs)
					{
						$w_team = $b_team;
					}
					else 
					{
						$w_team = 0;
					}
					$result_query = pg_query_params("UPDATE match SET result=$1 WHERE match_id=$2", Array($w_team, $mid)) or die(pg_last_error($dbconn));
				}
			}
			else if(($mtype == 1)&&($s_inn == 2))
			{
				$s_team_runs1_res = pg_execute($dbconn, "run_query", Array($mid, $s_team, 1));
				$s_team_runs1 = pg_fetch_result($s_team_runs1_res, 0, 0);
				$b_team_runs1_res = pg_execute($dbconn, "run_query", Array($mid, $b_team, 1));
				$b_team_runs1 = pg_fetch_result($b_team_runs1_res, 0, 0);
				$s_team_runs2_res = pg_execute($dbconn, "run_query", Array($mid, $s_team, 2));
				$s_team_runs2 = pg_fetch_result($s_team_runs2_res, 0, 0);

				if($checkOrder == 2)
				{
					$b_team_runs2_res = pg_execute($dbconn, "run_query", Array($mid, $b_team, 2));
					$b_team_runs2 = pg_fetch_result($b_team_runs2_res, 0, 0);
					if(($s_team_runs1+$s_team_runs2) > ($b_team_runs1+$b_team_runs2))
					{
						$result_query = pg_query_params("UPDATE match SET result=$1 WHERE match_id=$2", Array($s_team, $mid)) or die(pg_last_error($dbconn));
					}
					else if($checkWick >= 10)
					{
						if(($s_team_runs1+$s_team_runs2) == ($b_team_runs1+$b_team_runs2))
						{
							$result_query = pg_query_params("UPDATE match SET result=$1 WHERE match_id=$2", Array(0, $mid)) or die(pg_last_error($dbconn));
						}
						else 
						{
							$result_query = pg_query_params("UPDATE match SET result=$1 WHERE match_id=$2", Array($b_team, $mid)) or die(pg_last_error($dbconn));
						}
					}
				}
				else if($checkOrder == 1)
				{
					if(($checkWick >= 10)&&(($s_team_runs1+$s_team_runs2) < $b_team_runs1))
					{
						$result_query = pg_query_params("UPDATE match SET result=$1 WHERE match_id=$2", Array($b_team, $mid)) or die(pg_last_error($dbconn));
					}
				}
			}
		}

		header('Location: http://localhost/ESPNcricinfo/views/match.view.php?mid='.$_SESSION['mid']);
	}


?>