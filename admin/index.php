<?php
	require_once "../common/imp_functions.php";
	require_once "../common/db.php";
	//session_destroy();
	if(!is_logged_in())
		header("Location:$root");
	if($_SESSION['logged_in_role'] != "admin"){
		header("Location: ./../index.php");
	}

?>
<?php
	$logo = $pdo -> prepare("SELECT logo FROM branding");
	$logo -> execute();
	$logo = $logo -> fetch()["logo"];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Smart stock book</title>
		<link rel="stylesheet" href="../style/bootstrap.css">
		<link rel="stylesheet" href="style/style.css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Neonderthaw&family=Poppins:ital,wght@0,400;1,900&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
		<script src="../script/bootstrap.bundle.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script>var root = "<?php echo $root ?>" ;</script>
		<?php require_once "../common/common_css.php" ?>
	</head>
	<body class="w-100 h-100" style="background-color: #efefef;">
		<div class="inner-body">
			<div class="main-content w-100 d-flex justify-content-center align-items-center" style="height: 100vh;">
				<section class="position-relative pe-1" data-sidebar-hide="false" data-sidebar-size="max">
					<aside class="sidebar py-5 text-center position-absolute">
						<img src="<?php echo "data:image/png;base64,".base64_encode($logo) ?>" alt="" class="my-2 sidebar-logo">
						<div class="brand-name text-1 mb-2 sidebar-max-text px-1 py-2"><b>smartstockbook</b></div>
						<hr class="text-2" style="height: 5px;">
						<nav class="option">
							<ul class="nav nav-tabs flex-column position-relative sidebar-nav">
								<li class="nav-item mx-2 text-start rounded active" title="Branding" data-url="branding_design">
									<a class="nav-link border-0 text-1" href="javascript:void(0)"><i class="mdi mdi-flare"></i> <span class="sidebar-max-text">Branding</span></a>
								</li>
								<li class="nav-item text-start mx-2 rounded" title="merchants" data-url="merchants_design">
									<a class="nav-link border-0 text-1" href="javascript:void(0)"><i class="mdi mdi-account-group-outline"></i> <span class="sidebar-max-text">Merchants</span></a>
								</li>
							</ul>
						</nav>
						<div class="aside-footer">
							<a href="<?php echo $root."php/logout.php" ?>" class="btn light-btn px-3 py-1 mt-4 mb-2 border-0 text-1"><b> <span class="sidebar-max-text">Log Out</span><i class="mdi mdi-logout-variant"></i> </b></a>
						</div>
					</aside>
					<div class="content px-4 py-2 position-relative">
							<div class="row mb-2 top-content">
								<div class="col-12 d-flex flex-wrap justify-content-between py-1 align-items-center rounded-0 bg-white sh">
									<img src="<?php echo $root."merchant/sidebar.svg" ?>" class="cp sidebar-toggle" width="25" title="toggle sidebar" data-bs-placement="left">
									<div class="account-content dropdown justify-content-center align-items-center cp" style="user-select: none;">
										<div class="mx-2 p-0 m-0 d-flex justify-content-between align-items-center text-center" data-bs-toggle="dropdown">
											<div class="d-inline-block sh me-3 rounded-circle my-2" style="width: 35px; height: 35px; background-image: url(<?php echo "data:image/png;base64,".base64_encode($logo) ?>); background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
											<b>
											<?php
											//getting name of user merchant
												$user_id = $_SESSION['logged_in_id']	;
												$stmt = $pdo -> prepare("SELECT name FROM users WHERE id = $user_id");
												$stmt -> execute();
												echo($stmt -> fetch()["name"]);
											?>
											&nbsp;
											</b>
											<i class="mdi mdi-account" style="font-size: 20px;"></i>
										</div>
										<ul class="dropdown-menu" style="transition-duration: 0s;">
											<li class="profile-btn"><a class="dropdown-item"> <i class="fa fa-user-circle"></i> &nbsp; My Profile</a></li>
											<li class="logout-btn"><a href="<?php echo $root."php/logout.php" ?>" class="dropdown-item"><i class="fa fa-sign-out-alt"></i> &nbsp; Logout</a></li>
										</ul>
										
									</div>
								</div>
							</div>
						<div class="inner-content">
							
						</div>
					</div>
				</section>
			</div>
		<div class="external-content"></div>
	</div>

	</body>
	<script src="script/script.js"></script>
</html>