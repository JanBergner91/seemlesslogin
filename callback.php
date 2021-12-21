<?php
	
	include './functions_sl.php';
	if (isset($_POST['aktion']) && $_POST['aktion'] === 'login')
	{
		if ((isset($_POST['challenge']) && $_POST['challenge'] <> '')
		&& (isset($_POST['challengeresponse']) && $_POST['challengeresponse'] <> '')
		&& (isset($_POST['username']) && $_POST['username'] <> '')
		&& ($_POST['challengeresponse'] === strrev($_POST['challenge']))
		)
		{
			$_SESSION['sl_users_name'] = $_POST['username'];
			
			if (isset($_POST['userdata'])) 
			{
				$userdataarray = unserialize($_POST['userdata']);
				foreach ($userdataarray as $key => $value) {
					$_SESSION[$key] = $value;
				} 
			}
			
			if (isset($_POST['xuserdata'])) 
			{
				$xuserdataarray = unserialize($_POST['xuserdata']);
				foreach ($xuserdataarray as $key => $value) {
					$_SESSION[$value[0]] = $value[1];
				} 
			}
			
			header('Location: ./index.php');
		}
	}
	
	if (isset($_SESSION['sl_users_name']) && $_SESSION['sl_users_name'] <> '')
	{
		
	}
	else
	{
		header('Location: login.php');
	}
	
?>