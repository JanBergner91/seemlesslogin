<!doctype html>


<?php
	
	include './functions_sl.php';
	
	if (isset($_GET['logout']) && $_GET['logout'] <> '')
	{
		abmelden();
	}
	
	global $pdo_sl;
	global $con_sl;
	
	$statement = $pdo_sl->prepare("SELECT * FROM sl_users WHERE users_username = '" . $_SESSION['sl_users_name'] . "';");
	$result = $statement->execute();
	$user = $statement->fetch();
	$statement = $pdo_sl->prepare("SELECT * FROM sl_profiles WHERE profiles_id = " . $user['users_id'] . ";");
	$result = $statement->execute();
	$userp = $statement->fetch();
	
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
	<title>RS.DEV - Warnung</title>
</head>

<body class="bg-theme bg-theme9">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container-fluid">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="my-4 text-center">
							<img src="assets/images/logo_white.png" width="180" alt="" />
						</div>
						<div class="card">
							<div class="card-body">
								<div class="border p-4 rounded">
								
									<div class="text-center">
										<?php
											$authurl = 'https://auth.rootserver.dev';
											$callbackurl = 'https://auth.rootserver.dev/callback.php';
											$challenge = bin2hex(openssl_random_pseudo_bytes(16));
											$xurl= '' . $authurl . '/login.php?callback=' . $callbackurl . '&challenge=' . $challenge . '';
										?>
										<!--<img src="https://via.placeholder.com/110x110" width="110" height="110" class="rounded-circle shadow" alt="">-->
										<h5 class="mb-0 mt-5"><?php echo $userp['profiles_firstname'] . ' ' . $userp['profiles_lastname']; ?></h5>
										<p class="mb-3"><?php echo $userp['profiles_email']; ?></p>
										<div class="list-inline contacts-social mt-3 mb-3">
											
											<?php
											if (isset($_SESSION['sl_users_name']))
											{ 
												echo '<a href="https://auth.rootserver.dev/profile.php?ua=' . $user['users_username'] . '" class="list-inline-item bg-info text-white border-0"><i class="lni lni-user"></i></a>';
												echo '<a href="./index.php?logout=1" class="list-inline-item bg-danger text-white border-0"><i class="lni lni-cross-circle"></i></a>';
											}
											else
											{
												echo '<a href="' . $xurl . '" class="list-inline-item bg-success text-white border-0"><i class="lni lni-protection"></i></a>';
												echo '<a href="./register.php?logout=1" class="list-inline-item bg-warning text-white border-0"><i class="lni lni-key"></i></a>';
											}
											?>
											
										</div>
										
										<div class="list-inline mt-3 mb-3">
											
											<?php
											
												$intranet = 'https://auth.rootserver.dev/login.php?callback=https://auth.rootserver.dev/callback.php&challenge=' . $challenge . '';
												
												echo '<a href="' . $intranet . '"><button class="btn btn-success">Profil-Manager</button></a>';
												
											
											?>
											
										</div>
									
											<?php if (!isset($_SESSION['sl_users_name']))
											{ 
												echo '<h3 class="">GESICHERTES SYSTEM</h3><p>Sie versuchen ein gesichertes System zu betreten!<br>Verlassen Sie umgehend diese Webseite.<br></p>';
											}
											?>
										
									</div>
													
									<div class="d-grid">
										<!-- Content1 -->
									</div>
									
									
									
									<div class="form-body">
										
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