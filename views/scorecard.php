<?php 
	session_start();
	$mid = $_SESSION['mid'];
	require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';

	$o1_res = pg_execute($dbconn, "score_query", Array($mid, $inn, $order));
	$o1 = pg_fetch_assoc($o1_res, 0);
	if(!is_null($o1['team_id'])) {
	if($o1['team_id'] == $team1)
	{
		$o1_team_name = $t1;
		$o2_team = $team2;
		$o2_team_name = $t2;
		$o1_cap_res = pg_query_params("SELECT t1_captain FROM match WHERE match_id=$1", Array($mid));
	}
	else if($o1['team_id'] == $team2)
	{
		$o1_team_name = $t2;
		$o2_team = $team1;
		$o2_team_name = $t1;
		$o1_cap_res = pg_query_params("SELECT t2_captain FROM match WHERE match_id=$1", Array($mid));
	}
	$inn1_bat_res = pg_query_params("SELECT * FROM player_score WHERE match_id=$1 AND type=1 AND player_id IN (SELECT player_id FROM player WHERE team_id=$2)", Array($mid, $o1['team_id']));
	$inn1_bat = pg_fetch_all($inn1_bat_res);
	$inn1_bowl_res = pg_query_params("SELECT * FROM player_score WHERE match_id=$1 AND type=2 AND player_id IN (SELECT player_id FROM player WHERE team_id=$2)", Array($mid, $o2_team));
	$inn1_bowl = pg_fetch_all($inn1_bowl_res);
	$o1_cap = pg_fetch_result($o1_cap_res, 0, 0);
	$teamdata_res = pg_execute($dbconn, "team_score_query", Array($mid, $inn, $o1['team_id']));
	$teamdata = pg_fetch_assoc($teamdata_res, 0);

	if($inn == 1)
	{
		$innstr = ' 1st Innings';
	}
	else if($inn == 2)
	{
		$innstr = ' 2nd Innings';
	}
	else 
	{
		$innstr = ' Innings';
	}
?>

<!-- batting section -->
<div>
	<h5><?php echo $o1_team_name.$innstr; ?></h5>
	<table>
		<tr>
		    <th>BATSMEN</th>
		    <th>Wicket Information</th> 
		    <th>R</th>
		    <th>B</th>
			<th>4s</th>
			<th>6s</th>
			<th>SR</th>
		 </tr>
		<?php foreach ($inn1_bat as $pl) {
			$plnm_res = pg_execute($dbconn, "player_name_query", Array($pl['player_id'])); 
			$plnm = pg_fetch_result($plnm_res, 0, 0);
			if($pl['player_id'] == $o1_cap)
			{
				$plnm = $plnm.' (c)';
			}
			if(is_null($pl['wicket_type']))
			{
				$wickstr = 'not out';
			}
			else
			{
				$bowler_res = pg_execute($dbconn, "player_name_query", Array($pl['bowler'])); 
				$bowler = pg_fetch_result($bowler_res, 0, 0);
				$fielder_res = pg_execute($dbconn, "player_name_query", Array($pl['fielder'])); 
				$fielder = pg_fetch_result($fielder_res, 0, 0);
				switch ($pl['wicket_type']) {
					case 1:
						$wickstr = 'b '.$bowler;
						break;
					case 2:
						$wickstr = 'c '.$fielder.' b '.$bowler;
						break;
					case 3:
						$wickstr = 'cb '.$fielder.' b '.$bowler;
						break;
					case 4:
						$wickstr = 'lbw b '.$bowler;
						break;
					case 5:
						$wickstr = 'run out '.$fielder;
						break;
					case 6:
						$wickstr = 'st '.$fielder.' b '.$bowler;
						break;
					case 7:
						$wickstr = 'run out '.$fielder;
						break;
					default:
						break;
				}
			}
		?>
		<tr>
			<td><?php echo $plnm; ?></td>
			<td><?php echo $wickstr; ?></td>
			<td><?php echo $pl['runs']; ?></td>
			<td><?php echo $pl['balls']; ?></td>
			<td><?php echo $pl['fours']; ?></td>
			<td><?php echo $pl['sixes']; ?></td>
			<td><?php echo number_format(100*($pl['runs']/$pl['balls']), 2, '.', ''); ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td>Extras</td><td></td><td></td><td></td><td></td><td></td>
			<td><?php echo $teamdata['extras']; ?></td>
		</tr>
		<tr>
			<td><b>TOTAL</b></td><td></td><td></td><td></td><td></td><td></td>
			<td><?php echo $teamdata['runs'].'/'.$teamdata['wickets']; ?></td>
		</tr> 
	</table>
</div>

<br>
<!-- bowling section -->
<div>
	<table>
		<tr>
		    <th>BOWLING</th>
		    <th>O</th> 
		    <th>R</th>
		    <th>W</th>
		    <th>ECON</th>
			<th>4s</th>
			<th>6s</th>
			<th>EXTRAS</th>
		 </tr>
		<?php foreach ($inn1_bowl as $pl) {
			$plnm_res = pg_execute($dbconn, "player_name_query", Array($pl['player_id'])); 
			$plnm = pg_fetch_result($plnm_res, 0, 0);
			$overs = floor(($pl['balls']-$pl['extras'])/6) + (($pl['balls']-$pl['extras']) % 6)/10.0;
		?>
		<tr>
			<td><?php echo $plnm; ?></td>
			<td><?php echo $overs; ?></td>
			<td><?php echo $pl['runs']; ?></td>
			<td><?php echo $pl['wickets']; ?></td>
			<td><?php echo number_format(($pl['runs']/$overs), 2, '.', ''); ?></td>
			<td><?php echo $pl['fours']; ?></td>
			<td><?php echo $pl['sixes']; ?></td>
			<td><?php echo $pl['extras']; ?></td>
		</tr>
		<?php } ?> 
	</table>
</div>
<?php } ?>