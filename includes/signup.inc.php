<?php
	if(isset($_POST['signup-submit']))
	{
		require 'dbConnection.php';
		$username = $_POST['su-name'];
		$password = $_POST['spwd'];
		$pass_repeat = $_POST['spwd-repeat'];
		
		if(empty($username) || empty($password) || empty($pass_repeat))
		{
			header("Location: http://localhost/ESPNcricinfo/views/signup.php?error=emptyfields");
			exit();
		}
		elseif ($password !== $pass_repeat) 
		{
			header("Location: http://localhost/ESPNcricinfo/views/signup.php?error=passwordcheck");
			exit();
		}
		else
		{
			$result1 = pg_query_params($dbconn, "SELECT COUNT(*) FROM database_user WHERE username = $1", Array($username));

			if(!$result1)
			{
				echo "query did not execute";
			}
			else 
			{
				$val = pg_fetch_result($result1, 0, 0);
				if($val == 0)
				{
					$result2 = pg_query_params($dbconn, "INSERT INTO database_user (username, password, type) VALUES ($1, $2, 'general')", Array($username, $password));
			     	if(!$result2) 
			     	{
			     		echo "could not insert";
			     	}
			     	else 
			     	{
			     		header("Location: http://localhost/ESPNcricinfo/views/homepage.view.php?signup=success");
			     		exit();
			     	}
				}
				else 
			    {
			    	header("Location: http://localhost/ESPNcricinfo/views/signup.php?error=usernametaken");
			    	exit();
			    }
			}
		}
	}
	else 
	{
		header("Location: http://localhost/ESPNcricinfo/views/signup.php");
		exit();
	}