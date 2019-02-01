<?php
	session_start();
	session_unset();
	session_destroy();
	header("Location: http://localhost/ESPNcricinfo/views/homepage.view.php");
	exit();