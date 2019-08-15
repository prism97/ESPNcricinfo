<?php

	$dbconn = pg_connect("host=localhost port=5432 dbname=ESPNcricinfo user=postgres password=pg123");
	echo pg_last_error($dbconn);
	
?>