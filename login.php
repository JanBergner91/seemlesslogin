<!doctype html>


<?php
	
	$formular;
	include './functions_sl.php';
	$error=0;
	if (isset($_GET['callback']) && $_GET['callback'] <> '' && isset($_GET['challenge']) && $_GET['challenge'] <> '' && !isset($_POST['aktion']))
	{
		$action = 'try';
		$formular = '<form class="row g-3" action="';
		$formular .= 'login.php?callback=' . $_GET['callback'] . '&challenge=' . $_GET['challenge'];
		$formular .=  '" method="post">
			<div class="col-12">
			<label for="inputEmailAddress" class="form-label">Benutzername</label>
			<input name="username" type="text" class="form-control" id="inputEmailAddress" placeholder="Benutzername">';
		$formular .= '<input name="aktion" type="hidden" value="' . $action . '">';
		$formular .= '</div>
			<div class="col-12">
			<label for="inputChoosePassword" class="form-label">Passwort</label>
			<div class="input-group" id="show_hide_password">
			<input name="password" type="password" class="form-control border-end-0" id="inputChoosePassword" placeholder="Passwort"> <a href="javascript:;" class="input-group-text bg-transparent">';
		$formular .= "<i class='bx bx-hide'></i></a>";
		$formular .= '</div>
			</div>
			<div class="col-12">
			<label for="inputSelectCountry" class="form-label">Mandant</label>
			<select class="form-select" id="inputSelectCountry" aria-label="Default select example">
			<option selected>RS.DEV</option>
			<option value="1">RS.DEV - Testnetwork</option>
			</select>
			</div>
			<div class="login-separater text-center mb-4"> <span>Sitzung</span>
			<hr/>
			</div>
			<div>
			</div>
			<div class="col-md-6">
			<div class="form-check form-switch">
			<input name="offsso" class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" 0checked>
			<label class="form-check-label" for="flexSwitchCheckChecked">Windows Zugang verwenden</label>
			</div>
			</div>
			<div class="col-md-6 text-end">	<a href="">Passwort vergessen?</a>
			</div>
			<div class="col-12">
			<div class="d-grid">
			<button type="submit" class="btn btn-light"><i class="bx bxs-lock-open"></i>Anmelden</button>
			</div>
			</div>
			</form>';
	
	}
	elseif (isset($_POST['aktion']) && $_POST['aktion'] === 'try')
	{
		if ((isset($_POST['username']) && $_POST['username'] <> '')
		&& (isset($_POST['password']) && $_POST['password'] <> '')
		&& (isset($_GET['callback']) && $_GET['callback'] <> '')
		|| (isset($_POST['sso']))
		)
		{
			if (isset($_POST['sso'])) {
				$ergebnis = sso();
				if(isset ($_SERVER['REMOTE_USER']) & ($_SERVER['REMOTE_USER'] !=''))
				{
					if (strpos($_SERVER['REMOTE_USER'], '@') !== false)
					{
						// format: username@domain
						list($username, $domain) = explode('@', $_SERVER['REMOTE_USER'], 2);
					}
					else
					{
						// format: domain\username
						list($domain, $username) = explode("\\", $_SERVER['REMOTE_USER'], 2);
					}
				}
				
			}
			else { $ergebnis = anmelden($_POST['username'],$_POST['password']); $username = $_POST['username']; }
			
			//echo $ergebnis;
			if ($ergebnis === 1)
			{
				$challengeresponse = strrev($_GET['challenge']);
				$action = 'login';
				//Daten aus Datenbank abfragen
				
				global $pdo_sl;
				global $con_sl;
				$statement = $pdo_sl->prepare("SELECT * FROM sl_users WHERE users_username = '" . $username . "';");
				$result = $statement->execute();
				$user = $statement->fetch();
				
				if (!$user)
				{
					global $pdo_sl;
					global $con;
					
					$statement = $pdo_sl->prepare("INSERT INTO sl_users (users_sid,users_username) VALUES ('" . $username . "','" . $username . "');");
					$result = $statement->execute();
					$statement = $pdo_sl->prepare("SELECT * FROM sl_users WHERE users_username = '" . $username . "';");
					$result = $statement->execute();
					$user = $statement->fetch();
					$statement = $pdo_sl->prepare("INSERT INTO sl_profiles (profiles_id) VALUES (" . $user['users_id'] . ");");
					$result = $statement->execute();
						
				}
				
				//$statement = $pdo_sl->prepare("SELECT profiles_firstname, profiles_lastname FROM sl_profiles WHERE profiles_id = " . $user['users_id'] . ";");
				$statement = $pdo_sl->prepare("SELECT * FROM sl_profiles WHERE profiles_id = " . $user['users_id'] . ";");
				$result = $statement->execute();
				$userp = $statement->fetch();
				$ser_result = serialize($userp);
				
				$statement = $pdo_sl->prepare("SELECT * FROM sl_profiles_fields WHERE fields_user = " . $user['users_id'] . ";");
				$result = $statement->execute();
				$xuserp = array();
				while($row = $statement->fetch()) {
					$art = array($row['fields_name'], $row['fields_value']);
					array_push($xuserp, $art);
				}
				
				//$xuserp = $statement->fetch();
				
				$xser_result = serialize($xuserp);
				
				//echo $xser_result;
				//Formular bauen mit POST-Daten
				
				$formular = '<form class="row g-3" action="';
				if(isset($_GET['callback']) && $_GET['callback'] <> ''){ $formular.= $_GET['callback']; }
				else { $formular .= 'login.php'; } 
				$formular .= '" method="post">';
				$formular .= '<input name="aktion" type="hidden" value="' . $action . '">';
				$formular .= '<input name="username" type="hidden" value="' . $username . '">';
				$formular .= '<input name="challenge" type="hidden" value="' . $_GET['challenge'] . '">';
				$formular .= '<input name="challengeresponse" type="hidden" value="' . $challengeresponse . '">';
				$formular .= '<input name="userdata" type="hidden" value="' . htmlspecialchars($ser_result) . '">';
				$formular .= '<input name="xuserdata" type="hidden" value="' . htmlspecialchars($xser_result) . '">';
				$formular .= '<div class="col-12"><div class="d-grid"><div align=center><h3>Anmeldung erfolgreich!</h3></div><div align=left>Sie geben Ihre Informationen für die anfordernde Webseite frei.</div></div></div>';
				$formular .= '<div class="col-12"><div class="d-grid"><button type="submit" class="btn btn-light"><i class="bx bxs-key"></i>Fortsetzen</button></div></div>';
				
				
			}
			else
			{
				$error=1;
				$formular = '<div class="login-separater text-center mb-4"> <span>Fehlerhafte Anmeldedaten!</span><hr/></div>';
				$formular .= '<form class="row g-3" action="';
				if(isset($_GET['callback']) && $_GET['callback'] <> ''){ $formular.= 'login.php?callback=' . $_GET['callback'] . '&challenge=' . $_GET['challenge']; }
				else { $formular .= 'login.php'; } 
				$formular .= '" method="post">';
				$formular .= '<div class="col-12"><div class="d-grid"><button type="submit" class="btn btn-light"><i class="bx bxs-lock-open"></i>Erneut versuchen</button></div></div>';
			}
		}
	}
	else
	{
		$formular = '<div class="login-separater text-center mb-4"> <span>Ungültiger Vorgang</span><hr/></div>';
		$formular .= '<div class="col-12"><div class="d-grid"><div align=center>Es wurden keine gültigen Daten übermittelt!<br>Der Verifizierungsvorgang kann nicht abgeschlossen werden!</div></div></div>';
	}
	
	//session_start();
	
	
	if (isset($_GET['logout']) && $_GET['logout'] <> '')
	{
		abmelden();
	}
	
	
	
	
?>


<html lang="de">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/favicon.ico" type="image/png" />
	<!--plugins-->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<title><?php echo $app_logo; ?> - Login</title>
</head>

<body class="bg-theme <?php echo $app_style; ?>">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container-fluid">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="my-4 text-center">
							<img src="<?php echo $app_logo; ?>" width="180" alt="" />
						</div>
						<div class="card">
							<div class="card-body">
								<div class="border p-4 rounded">
									<div class="text-center">
										<h3 class="">Anmelden</h3>
										<p>Gesichertes System</p>
									</div>
													
									<div class="d-grid">
										<!-- Content1 -->
									</div>
									
									
									
									<div class="form-body">
										<?php echo $formular; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>

</html>