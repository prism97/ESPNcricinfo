<?php 
	require 'navbar.php';
	require $_SERVER['DOCUMENT_ROOT'].'/ESPNcricinfo/includes/dbConnection.php';
	$mtype = ['Test', 'ODI', 'T20'];

	$series_res = pg_query("SELECT * FROM series");
	$series = pg_fetch_all($series_res);

	$test_res = pg_query("SELECT match.match_id AS mid, team_1, team_2, series.name AS sname FROM match, matches_in_series, series WHERE matches_in_series.match_id = match.match_id AND series.series_id = matches_in_series.series_id AND match.type = 1 ORDER BY series.series_id ASC, match.date ASC");
	$test = pg_fetch_all($test_res);
	$odi_res = pg_query("SELECT match.match_id AS mid, team_1, team_2, series.name AS sname FROM match, matches_in_series, series WHERE matches_in_series.match_id = match.match_id AND series.series_id = matches_in_series.series_id AND match.type = 2 ORDER BY series.series_id ASC, match.date ASC");
	$odi = pg_fetch_all($odi_res);
	$t20_res = pg_query("SELECT match.match_id AS mid, team_1, team_2, series.name AS sname FROM match, matches_in_series, series WHERE matches_in_series.match_id = match.match_id AND series.series_id = matches_in_series.series_id AND match.type = 3 ORDER BY series.series_id ASC, match.date ASC");
	$t20 = pg_fetch_all($t20_res);

	$matches_res = pg_query("SELECT * FROM match ORDER BY match.date DESC");
	$matches = pg_fetch_all($matches_res);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://localhost/ESPNcricinfo/stylesheets/homepage.css"> 
</head>
<body>
	<div id="container">
		<div class="divs" id="left">
			<span>Recent Series</span>
			<hr style="background-color: #E0E0E0; height: 1px; border: 0;">
			<ul style="list-style-type: none;">
			<?php 
				foreach ($series as $x) {
					echo '<li><a href="'.htmlspecialchars("series.view.php?sid=" .
        urlencode($x['series_id'])).'">'.$x['name'].'</a></li>';
				}
			?>
			</ul>
		</div>
		<div class="divs" id="middle">
			<div class="button-container">
				<button class="tab-btn" onclick="showTab(0)" style="border-top-left-radius: 10px;">TEST</button>
				<button class="tab-btn" onclick="showTab(1)">ODI</button>
				<button class="tab-btn" onclick="showTab(2)" style="border-top-right-radius: 10px;">T20</button>
			</div>
			<hr style="background-color: #E0E0E0; height: 1px; border: 0; margin: 0;">
			<div class="tab-content">
				<ul style="list-style-type: none;">
				<?php 
					$s = 1;
					$curr_series = $test[0]['sname'];
					foreach ($test as $m) {
						if($m['sname'] != $curr_series)
						{
							$s = 1;
							$curr_series = $m['sname'];
						}
						$t1_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($m['team_1']));
						$t1 = pg_fetch_result($t1_res, 0, 0);
						$t2_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($m['team_2']));
						$t2 = pg_fetch_result($t2_res, 0, 0);
						echo '<li><a href="'.htmlspecialchars("match.view.php?mid=" .
        urlencode($m['mid'])).'">'.$t1.' vs '.$t2.' '.$m['sname'].' Test '.$s.'</a></li>';
						$s++;
					}
				?>
				</ul> 
			</div>
			<div class="tab-content">
				<ul style="list-style-type: none;">
				<?php 
					$s = 1;
					$curr_series = $odi[0]['sname'];
					foreach ($odi as $m) {
						if($m['sname'] != $curr_series)
						{
							$s = 1;
							$curr_series = $m['sname'];
						}
						$t1_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($m['team_1']));
						$t1 = pg_fetch_result($t1_res, 0, 0);
						$t2_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($m['team_2']));
						$t2 = pg_fetch_result($t2_res, 0, 0);
						echo '<li><a href="'.htmlspecialchars("match.view.php?mid=" .
        urlencode($m['mid'])).'">'.$t1.' vs '.$t2.' '.$m['sname'].' ODI '.$s.'</a></li>';
						$s++;
					}
				?>
				</ul>
			</div>
			<div class="tab-content">
				<ul style="list-style-type: none;">
				<?php 
					$s = 1;
					$curr_series = $t20[0]['sname'];
					foreach ($t20 as $m) {
						if($m['sname'] != $curr_series)
						{
							$s = 1;
							$curr_series = $m['sname'];
						}
						$t1_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($m['team_1']));
						$t1 = pg_fetch_result($t1_res, 0, 0);
						$t2_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($m['team_2']));
						$t2 = pg_fetch_result($t2_res, 0, 0);
						echo '<li><a href="'.htmlspecialchars("match.view.php?mid=" .
        urlencode($m['mid'])).'">'.$t1.' vs '.$t2.' '.$m['sname'].' T20 '.$s.'</a></li>';
						$s++;
					}
				?>
				</ul>
			</div>
		</div>
		<div class="divs" id="right">
			<span>Recent Matches</span>
			<hr style="background-color: #E0E0E0; height: 1px; border: 0;">
			<ul style="list-style-type: none;">
			<?php 
				foreach ($matches as $x) {
					$t1_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($x['team_1']));
					$t1 = pg_fetch_result($t1_res, 0, 0);
					$t2_res = pg_query_params("SELECT name FROM team WHERE team_id=$1", Array($x['team_2']));
					$t2 = pg_fetch_result($t2_res, 0, 0);
					echo '<li><a href="'.htmlspecialchars("match.view.php?mid=" .
        urlencode($x['match_id'])).'">'.$t1.' vs '.$t2.' '.$mtype[$x['type']-1].'</a></li>';
				}
			?>
			</ul>
		</div>
	</div>

	<script>
		var tabButtons = document.querySelectorAll(".divs .button-container .tab-btn");
		var tabContents = document.querySelectorAll(".divs .tab-content");
		function showTab(tabIndex) {
			tabContents.forEach(function(node) {
				node.style.display = "none";
			});
			tabContents[tabIndex].style.display = "flex";
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