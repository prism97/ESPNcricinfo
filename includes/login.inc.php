<?php
	if(isset($_POST['login-submit']))
	{
		require 'dbConnection.php';
		$username = $_POST['lu-name'];
		$password = $_POST['lpwd'];

		if(empty($username) || empty($password))
		{
			header("Location: http://localhost/ESPNcricinfo/views/login.php?error=emptyfields");
			exit();
		}
		else 
		{
			$result = pg_query_params("SELECT * FROM database_user WHERE username = $1", Array($username));
			if(!$result)
			{
				echo "query did not execute";
			}
			else if(pg_num_rows($result) == 1)
			{
				$row = pg_fetch_assoc($result);
				
				if($row['password'] == $password)
				{
					echo "login successful";
					session_start();
					$_SESSION['curr_user_name'] = $row['username'];
					$_SESSION['curr_user_pass'] = $row['password'];
					$_SESSION['curr_user_type'] = $row['type'];
					$_SESSION['curr_user_id'] = $row['user_id'];
					header("Location: http://localhost/ESPNcricinfo/views/homepage.view.php?login=success");
					exit();
				}
				else 
				{
					header("Location: http://localhost/ESPNcricinfo/views/login.php?error=wrongpassword");
					exit();
				}
			}
		}
	}
	else 
	{
		header("Location: http://localhost/ESPNcricinfo/views/login.php");
		exit();
	}