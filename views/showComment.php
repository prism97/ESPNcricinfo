<?php 
	session_start();
	$mid = $_GET['mid'];
	require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';
	$s_team = $_GET['tid'];
	$s_inn = $_GET['inn'];
	$s_query = $_GET['query'];

?>

<style>
	.over-end {
	  background-color: #E0E0E0;
	  width: 50%;
	}

	.per-ball {
	  width: 50%;
	  display: flex;
	  flex-direction: row;
	  align-items: center;
	  justify-content: space-between;
	}

	.per-ball h4 {
	  flex: 1;
	}

	.per-ball p {
	  flex: 5;
	}
</style>
<?php
	
	if($s_query == 1)
	{
		$comm1_res = pg_query_params('SELECT *, (ball_no/6 + MOD(ball_no, 6)/10.0)::float AS curr_ball FROM commentary_admin WHERE match_id=$1 AND batting_team_id=$2 AND innings=$3 ORDER BY ball_no ASC', Array($mid, $s_team, $s_inn))  or die(pg_last_error($dbconn));
		$result = pg_fetch_all($comm1_res);
	}
	else if($s_query == 2)
	{
		$comm2_res = pg_query_params('SELECT *, (ball_no/6 + MOD(ball_no, 6)/10.0)::float AS curr_ball FROM commentary_admin WHERE match_id=$1 AND batting_team_id=$2 AND innings=$3 AND wicket_type IS NOT NULL ORDER BY ball_no ASC', Array($mid, $s_team, $s_inn))  or die(pg_last_error($dbconn));
		$result = pg_fetch_all($comm2_res);
	}
	else if($s_query == 3)
	{
		$comm3_res = pg_query_params('SELECT *, (ball_no/6 + MOD(ball_no, 6)/10.0)::float AS curr_ball FROM commentary_admin WHERE match_id=$1 AND batting_team_id=$2 AND innings=$3 AND (fours=1 OR sixes=1) ORDER BY ball_no ASC', Array($mid, $s_team, $s_inn))  or die(pg_last_error($dbconn));
		$result = pg_fetch_all($comm3_res);
	}

	$over_runs = 0;
	$over_wicks = 0;

	foreach ($result as $row) 
	{
		$over_runs += $row['runs'];
		if(!is_null($row['extras'])) { $over_runs++; }
		if($row['fours'] == 1) { $over_runs += 4; }
		if($row['sixes'] == 1) { $over_runs += 6; }
		if (!is_null($row['wicket_type'])) { $over_wicks++; }
		
		if($row['ball_no'] % 6 == 0)
		{
			$r_ball = ($row['ball_no']/6) - 1 + 0.6; 
			echo '<div class="per-ball"><h4>'.$r_ball.'</h4>'.'<p>'.$row['comment'].'</p></div>';
			if(is_null($row['extras']) && $s_query == 1)
			{
				echo '<div class="over-end">END OF OVER: '.$over_runs.' Runs '.$over_wicks.' Wkts</div>';
				$over_runs = 0;
				$over_wicks = 0;
			}
		}
		else 
		{
			$r_ball = $row['curr_ball'];
			echo '<div class="per-ball"><h4>'.$r_ball.'</h4>'.'<p>'.$row['comment'].'</p></div>';
			$search_res = pg_query_params("SELECT * FROM commentary_user WHERE match_id=$1 AND innings=$2 AND team_id=$3 AND ball_no=$4", Array($mid, $s_inn, $s_team, $row['ball_no']));
			$search = pg_fetch_all($search_res);
			foreach ($search as $us) {
				echo '<div style="display: flex; flex-direction: row; align-items: center;"><h5 style="padding-right: 10px;">'.$us['username'].' commented</h5>'.'<p>'.$us['comment'].'</p></div>';
			}
		}
	}

?>