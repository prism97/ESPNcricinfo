<?php 
	session_start();
	require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';
	
	if(isset($_POST['post-comm']))
	{	
		$ball_res = pg_query_params("SELECT MAX(ball_no) FROM commentary_admin WHERE match_id=$1 AND innings=$2 AND batting_team_id=$3", Array($_POST['mid'], $_POST['inn'], $_POST['tid']));
		$ball = pg_fetch_result($ball_res, 0, 0);

		$insert_query = pg_query_params("INSERT INTO commentary_user(match_id, team_id, innings, ball_no, comment, user_id, username) VALUES($1, $2, $3, $4, $5, $6, $7)", Array($_POST['mid'], $_POST['tid'], $_POST['inn'], $ball, $_POST['comm-box'], $_SESSION['curr_user_id'], $_SESSION['curr_user_name']))  or die(pg_last_error($dbconn));
		header('Location: http://localhost/ESPNcricinfo/views/livecomment.php?mid='.$_POST['mid']);
	}
?>