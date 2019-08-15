<?php 
	session_start();
	$mid = $_SESSION['mid'];
	require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';
	$tname = $_GET['tname'];
	$player_res = pg_query_params("SELECT name FROM player WHERE player_id IN (SELECT player_id FROM squad WHERE team_id = $1 AND match_id = $2)", Array($tname, $mid));
	$player_list = pg_fetch_all($player_res);
	echo '<option selected value=""></option>';
	foreach ($player_list as $player) {
		echo "<option>".$player['name']."</option>";
	}
?>

