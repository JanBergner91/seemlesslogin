<!doctype html>
<html lang="de">

<?php
	include './functions_sl.php';
	
	global $pdo_sl;
	global $con_sl;
	
	if (isset($_GET['save']) && $_GET['save'] <> '' && $_SESSION['sl_users_name'] === $_GET['ua'])
	{
		$statement = $pdo_sl->prepare("SELECT * FROM sl_users WHERE users_username = '" . $_GET['ua'] . "';");
		$result = $statement->execute();
		$user = $statement->fetch();
		
		foreach ($_POST as $key => $value) {
			$statement = $pdo_sl->prepare("UPDATE sl_profiles SET " . $key . " = '" . $value . "' WHERE profiles_id = " . $user['users_id'] . ";");
			$statement->execute();
		}
	}
	
	
	$statement = $pdo_sl->prepare("SELECT * FROM sl_users WHERE users_username = '" . $_GET['ua'] . "';");
	$result = $statement->execute();
	$user = $statement->fetch();
	$statement = $pdo_sl->prepare("SELECT * FROM sl_profiles WHERE profiles_id = " . $user['users_id'] . ";");
	$result = $statement->execute();
	$userp = $statement->fetch();
	
?>

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
	<title>RS.DEV - Profile</title>
</head>

<body class="bg-theme bg-theme9">
	<!--wrapper-->
	<div class="wrapper">
		<div class="d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container">
				<div class="row row-cols-1 row-cols-lg-1 row-cols-xl-1">
					<div class="col mx-auto">
						<div class="my-4 text-center">
							<img src="assets/images/logo_white.png" width="180" alt="" />
						</div>
						<div class="card">
							<div class="card-body">
								<div class="border p-4 rounded">
									
									<div class="form-body">
										<form class="row g-3" action="<?php echo '?ua=' . $_GET['ua'] . '&save=1'; ?>" method="post">
											<div class="col-12">
												<div class="card radius-15">
													<div class="card-body text-center">
														<?php
															$authurl = 'https://auth.rootserver.dev';
															$callbackurl = 'https://auth.rootserver.dev/callback.php';
															$challenge = bin2hex(openssl_random_pseudo_bytes(16));
															$xurl= '' . $authurl . '/login.php?callback=' . $callbackurl . '&challenge=' . $challenge . '';
														?>
														<div class="p-4 border radius-15">
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
															
														</div>
													</div>
												</div>
											</div>
											
											<!--<div class="col-12">
												<div class="card radius-15">
													<div class="card-body">
														<div class="p-4 border radius-15">
															<ul class="list-group list-group-flush border-warning border-3">
																
															</ul>
														</div>
													</div>
												</div>
											</div>-->
											
											
											<div class="col-12">
												<div class="card radius-15">
													<div class="card-body">
													
														<div class="p-4 border radius-15">
															<h4>Kontakt</h4>
															<ul class="list-group list-group-flush border-warning border-3">
																<?php 
																
																	
																	if (isset($userp['profiles_firstname'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-user"></i></span>
																			<input name="profiles_firstname" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['profiles_firstname'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['profiles_firstname'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-user"></i>| ' . $userp['profiles_firstname'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Homepage</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['profiles_lastname'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-user"></i></span>
																			<input name="profiles_lastname" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['profiles_lastname'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['profiles_lastname'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-user"></i>| ' . $userp['profiles_lastname'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Homepage</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['profiles_homepage'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-website"></i></span>
																			<input name="profiles_homepage" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['profiles_homepage'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['profiles_homepage'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-website"></i>| ' . $userp['profiles_homepage'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Homepage</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['profiles_email'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-envelope"></i></span>
																			<input name="profiles_email" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['profiles_email'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['profiles_email'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-envelope"></i>| ' . $userp['profiles_email'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">E-Mail</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['profiles_dev'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-dev"></i></span>
																			<input name="profiles_dev" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['profiles_dev'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['profiles_dev'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-dev"></i>| ' . $userp['profiles_dev'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">DEV-Profile</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_whatsapp'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-whatsapp"></i></span>
																			<input name="soc_whatsapp" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_whatsapp'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_whatsapp'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-whatsapp"></i>| ' . $userp['soc_whatsapp'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Whatsapp</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_signal'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-pointer"></i></span>
																			<input name="soc_signal" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_signal'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_signal'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-pointer"></i>| ' . $userp['soc_signal'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Signal</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_telegram'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-telegram-original"></i></span>
																			<input name="soc_telegram" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_telegram'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_telegram'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-telegram-original"></i>| ' . $userp['soc_telegram'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Telegram</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_microsoft'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-microsoft"></i></span>
																			<input name="soc_microsoft" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_microsoft'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_microsoft'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-microsoft"></i>| ' . $userp['soc_microsoft'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Microsoft</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_skype'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-skype"></i></span>
																			<input name="soc_skype" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_skype'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_skype'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-skype"></i>| ' . $userp['soc_skype'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Skype</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_linkedin'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-linkedin-original"></i></span>
																			<input name="soc_linkedin" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_linkedin'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_linkedin'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-linkedin-original"></i>| ' . $userp['soc_linkedin'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">LinkedIn</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_xing'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-users"></i></span>
																			<input name="soc_xing" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_xing'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_xing'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-users"></i>| ' . $userp['soc_xing'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">XING</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_discord'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-discord"></i></span>
																			<input name="soc_discord" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_discord'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_discord'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-discord"></i>| ' . $userp['soc_discord'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Discord</span></button></a>
																			</li>';
																		}
																	}
																	
																?>
																
																
															</ul>
														</div>
													</div>
												</div>
											</div>
											
											<div class="col-12">
												<div class="card radius-15">
													<div class="card-body">
														<div class="p-4 border radius-15">
															<h4>Social-Media</h4>
															<ul class="list-group list-group-flush border-warning border-3">
																<?php
																	
																	if (isset($userp['soc_facebook'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3">facebook.com/</span>
																			<input name="soc_facebook" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_facebook'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_facebook'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-facebook-original"></i>| ' . $userp['soc_facebook'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Facebook</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_twitter'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-twitter-original"></i></span>
																			<input name="soc_twitter" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_twitter'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_twitter'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-twitter-original"></i>| ' . $userp['soc_twitter'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Twitter</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_instagram'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-instagram-original"></i></span>
																			<input name="soc_instagram" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_instagram'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_instagram'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-instagram-original"></i>| ' . $userp['soc_instagram'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Instagram</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_snapchat'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-snapchat"></i></span>
																			<input name="soc_snapchat" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_snapchat'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_snapchat'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-snapchat"></i>| ' . $userp['soc_snapchat'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Snapchat</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_tiktok'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-display"></i></span>
																			<input name="soc_tiktok" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_tiktok'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_tiktok'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-display"></i>| ' . $userp['soc_tiktok'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">TikTok</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_reddit'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-reddit"></i></span>
																			<input name="soc_reddit" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_reddit'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_reddit'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-reddit"></i>| ' . $userp['soc_reddit'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Reddit</span></button></a>
																			</li>';
																		}
																	}
																	
																
																?>
																
															</ul>
														</div>
													</div>
												</div>
											</div>
											
											<div class="col-12">
												<div class="card radius-15">
													<div class="card-body">
														<div class="p-4 border radius-15">
															<h4>Media & Streaming</h4>
															<ul class="list-group list-group-flush border-warning border-3">
															
															<?php
																	if (isset($userp['soc_youtube'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-youtube"></i></span>
																			<input name="soc_youtube" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_youtube'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_youtube'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-youtube"></i>| ' . $userp['soc_youtube'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Youtube</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_vimeo'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-vimeo"></i></span>
																			<input name="soc_vimeo" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_vimeo'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_vimeo'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-vimeo"></i>| ' . $userp['soc_vimeo'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Vimeo</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_pinterest'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-pinterest"></i></span>
																			<input name="soc_pinterest" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_pinterest'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_pinterest'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-pinterest"></i>| ' . $userp['soc_pinterest'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Pinterest</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_twitch'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-twitch"></i></span>
																			<input name="soc_twitch" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_twitch'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_twitch'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-twitch"></i>| ' . $userp['soc_twitch'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Twitch</span></button></a>
																			</li>';
																		}
																	}
																	
																	
															?>
																
															</ul>
														</div>
													</div>
												</div>
											</div>
											
											<div class="col-12">
												<div class="card radius-15">
													<div class="card-body">
														<div class="p-4 border radius-15">
															<h4>Gaming</h4>
															<ul class="list-group list-group-flush border-warning border-3">
															
															<?php
																	
																	if (isset($userp['gms_steam'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-steam"></i></span>
																			<input name="gms_steam" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['gms_steam'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['gms_steam'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-steam"></i>| ' . $userp['gms_steam'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Steam</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['gms_origin'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3">Origin:</span>
																			<input name="gms_origin" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['gms_origin'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['gms_origin'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-game"></i>| ' . $userp['gms_origin'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Origin</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['gms_epic'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3">Epic:</span>
																			<input name="gms_epic" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['gms_epic'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['gms_epic'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-game"></i>| ' . $userp['gms_epic'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Epic-Launcher</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['gms_ea'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3">EA:</span>
																			<input name="gms_ea" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['gms_ea'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['gms_ea'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-game"></i>| ' . $userp['gms_ea'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Electronic-Arts</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['gms_ubi'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3">Ubisoft:</span>
																			<input name="gms_ubi" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['gms_ubi'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['gms_ubi'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-game"></i>| ' . $userp['gms_ubi'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">UbiSoft</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['gms_playstation'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3">PlayStation:</span>
																			<input name="gms_playstation" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['gms_playstation'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['gms_playstation'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-game"></i>| ' . $userp['gms_playstation'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">PlayStation</span></button></a>
																			</li>';
																		}
																	}
																	
																	
																	
															?>
																
															</ul>
														</div>
													</div>
												</div>
											</div>
											
											
											<div class="col-12">
												<div class="card radius-15">
													<div class="card-body">
														<div class="p-4 border radius-15">
															<h4>Querschnitt</h4>
															<ul class="list-group list-group-flush border-warning border-3">
															
															<?php
																	if (isset($userp['soc_dropbox'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-dropbox-original"></i></span>
																			<input name="soc_dropbox" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_dropbox'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_dropbox'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-dropbox-original"></i>| ' . $userp['soc_dropbox'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">DropBox</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['soc_gdrive'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-google-drive"></i></span>
																			<input name="soc_gdrive" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['soc_gdrive'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['soc_gdrive'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-google-drive"></i>| ' . $userp['soc_gdrive'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Google-Drive</span></button></a>
																			</li>';
																		}
																	}
																	
																	
																	
															?>
															
															</ul>
														</div>
													</div>
												</div>
											</div>
											
											
											<div class="col-12">
												<div class="card radius-15">
													<div class="card-body">
														<div class="p-4 border radius-15">
															<h4>Zahlung</h4>
															<ul class="list-group list-group-flush border-warning border-3">
															
															<?php
																	
																	if (isset($userp['pay_paypal'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-paypal-original"></i></span>
																			<input name="pay_paypal" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['pay_paypal'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['pay_paypal'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-paypal-original"></i>| ' . $userp['pay_paypal'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">PayPal</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['pay_google'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-google-pay"></i></span>
																			<input name="pay_google" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['pay_google'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['pay_google'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-google-pay"></i>| ' . $userp['pay_google'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Google-Pay</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['pay_amazon'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-amazon-pay"></i></span>
																			<input name="pay_amazon" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['pay_amazon'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['pay_amazon'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-amazon-pay"></i>| ' . $userp['pay_amazon'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Amazon-Pay</span></button></a>
																			</li>';
																		}
																	}
																	
																	if (isset($userp['pay_apple'])) {
																		if ($_SESSION['sl_users_name'] === $_GET['ua']) {
																			echo '<li class="list-group-item"><div class="input-group mb-3"> <span class="input-group-text" id="basic-addon3"><i class="lni lni-apple-pay"></i></span>
																			<input name="pay_apple" type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" value="' . $userp['pay_apple'] . '">
																			</div></li>';
																		} else {
																			echo '<li class="list-group-item"><a href="' . $userp['pay_apple'] . '"><button type="button" class="btn btn-outline-light position-relative me-lg-5">
																			<i class="lni lni-apple-pay"></i>| ' . $userp['pay_apple'] . '
																			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Apple-Pay</span></button></a>
																			</li>';
																		}
																	}
																	
															?>
															
															</ul>
														</div>
													</div>
												</div>
											</div>
											
											<!--<div class="col-12">
												<div class="card radius-15">
													<div class="card-body">
														<div class="p-4 border radius-15">
															<h4>Template</h4>
															<ul class="list-group list-group-flush border-warning border-3">
																
															</ul>
														</div>
													</div>
												</div>
											</div>-->
											
											<div class="col-12">
												<div class="d-grid">
													<button type="submit" class="btn btn-light"><i class='bx bx-user'></i>Speichern</button>
												</div>
											</div>
										</form>
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