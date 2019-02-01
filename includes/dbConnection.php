<?php

	$dbconn = pg_connect("host=localhost port=5432 dbname=ESPNcricinfo user=postgres password=pg123");
	//var_dump($dbconn); 
	//echo "kop";
	echo pg_last_error($dbconn);

	// try {
	// 	$dbuser = 'postgres';
	// 	$dbpass = 'pg123';
		
	// 	$connec = new PDO('pgsql:host=localhost;dbname=ESPNcricinfo', $dbuser, $dbpass);
			
	// }catch (PDOException $e) {
	// 	echo "Error : " . $e->getMessage() . "<br/>";
	// 	die();
	// }