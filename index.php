<?php
	require_once "./common/db.php";
	require_once "./common/imp_functions.php";
		if(!$db -> table_exists("branding")){
		$db -> create_table("branding", [
			"id int primary key AUTO_INCREMENT",
			"brand_name VARCHAR(50)",
			"domain_name VARCHAR(50)",
			"email VARCHAR(30)",
			"phone VARCHAR(20)",
			"address VARCHAR(100)",
			"fb VARCHAR(150)",
			"twitter VARCHAR(150)",
			"logo MEDIUMBLOB",
			"about_us MEDIUMTEXT",
			"privacy_policy MEDIUMTEXT",
			"cookies_Policy MEDIUMTEXT",
			"terms_conditions MEDIUMTEXT"
		]);
	}


	$role = is_logged_in();
	if($role){
		header("Location:".$root.$role."/");
	}
	
	//checking table exists or not, if not creating a tables and triggers 
	if(!$db->table_exists('users') || !$db->table_exists('merchants')){
		$cols = [" id int primary key AUTO_INCREMENT", "name varchar(100)", "logo varchar(100)", "address VARCHAR(100)",  "phone varchar(17)", "last_invoice_no INT default 0"];
		$db -> create_table("merchants", $cols);

		$cols = [" id int primary key AUTO_INCREMENT", "name varchar(100)", "email varchar(100)", "password varchar(255)", "role VARCHAR(20) default 'merchant', status varchar(20) default 'unverified'", "merchant_id int", "FOREIGN KEY (merchant_id) REFERENCES merchants(id) ON DELETE CASCADE"];
		$db -> create_table("users", $cols);

		$default_pw = password_hash("Password@45", PASSWORD_DEFAULT);
		$stmt = $pdo -> prepare("INSERT INTO users(`name`, `email`, `password`, `role`) VALUE('Super Admin', 'admin@krishnat.com.np', '$default_pw', 'admin');");
		$stmt -> execute();

		$stmt = $pdo -> prepare(" 
			CREATE TRIGGER merchant_user
			AFTER INSERT ON `merchants`
			FOR EACH ROW BEGIN 
			INSERT INTO users(`name`, `email`, `password`, `merchant_id`) VALUE(new.name, @email, @password, new.id);
			END;
		 ");
		if(!$stmt -> execute()){
			die(json_encode(array("status" => "error", "message" => "Something went absolutely wrong!!")));
		}
	}

	if(!$db->table_exists('employees')){
		$cols= [" id int primary key AUTO_INCREMENT", "user_id int", "phone VARCHAR(20)", "address VARCHAR(255)" ,"FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE"];
		$db -> create_table("employees", $cols);
		$stmt = $pdo -> prepare("
			CREATE TRIGGER employee_user
			AFTER INSERT ON `users`
			FOR EACH ROW BEGIN
				IF(new.role = 'employee') THEN
					INSERT INTO employees(`user_id`, `phone`, `address`) VALUE(new.id, @phone, @address);
				END IF;
			END;
		");
		$stmt -> execute();
	}
	$branding = $db -> select_all("branding")[0];

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="Description" content="Best Inventory management platform available on the internet">
		<title><?php echo $branding["brand_name"];?></title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Neonderthaw&family=Poppins:ital,wght@0,400;1,900&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css">
		<link rel="stylesheet" href="style/bootstrap.css">
		<link rel="stylesheet" href="style/style.css">
		<link rel="icon" href="<?php echo "data:image/png;base64,".base64_encode($branding["logo"]) ?> ">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
		<script src="script/bootstrap.bundle.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script>var root = "<?php echo $root ?>" ;</script>
		<?php require_once 'common/common_css.php'; ?>
	</head>
	<body class="w-100 h-100 p-0 m-0 bg-pink" data-bs-spy="scroll" data-bs-target=".my-nav" data-bs-offset="400">
		<div class="main-content w-100">
			<div class="nav-container w-100 position-fixed bg-white" style="z-index: 1000;">
				<nav id="" class="container d-flex justify-content-between p-2">
					<a class="navbar-brand text-1" href="javascript:void(0)">
						<img src=<?php echo "data:image/png;base64,".base64_encode($branding["logo"]) ?> width=40px>
						<span class="brand_name"><?php echo $branding["brand_name"];?></span>
					</a>
					<div class="nav-con">
						
					</div>
					<span class="mobile-nav-open mdi mdi-menu d-lg-none cp" style="font-size: 25px;"></span>
				</nav>
			</div>
			<br>
			<br>
			<br>
			<div class="container mb-5">
				<div class="row">
					<div id="home" class="col-md-6 pt-5 d-flex flex-column justify-content-center">
						<span class="text-1 mb-4" style="font-size: calc(0.7rem + 1.5vw); ">The Best <br> inventory Management Platform available in the Internet</span>
						<p style="font-size: 18px;"><span class="pe-4 text-0 font-weight-bold" style="border-right: solid 3px ;font-weight: bold">Wholesellers</span>
						<span class="ps-4 text-0" style="font-weight: bold;">Retailers</span></p>
						<b class="d-block py-3 text-1 display-7">Record your inventory details in our digital stock book. <br><br>
						<span class="text-0">Organize</span> your stock in the internet securely<br>
						<button class="mt-4 mb-5  border  bor-4 bg-4 text-white px-4 py-1" href="#" style="border-radius: 20px;">Explore More !</button>
						</b>
					</div>
					<div class="col-md-6">
						<img src="./images/header.jpg" alt="" class="w-100">
					</div>
				</div>
			</div>
			<div class="container" id="service">
				<h3 class="text-0 text-center">Platform For Everyone</h3>
				<h5 class="text-center text-1">Professional Management of your Inventory system</h5>
				<p class="text-center text-4">Out perform your roles at smartstockbook</p>
				<div class="row mb-5">
					<div class="col-lg-2">
					</div>
					<div class="col-md-6 col-lg-4 text-start py-4">
						<div class="card bg-transparent border-0 bor-0 d-inline-block pe-4 border-start rounded-0 shadow" style="border-width: 5px !important;">
							<div class="card-header border-0 bg-transparent">
								<b class="text-3 border-bottom bor-3 px-2">Admin Account</b>
							</div>
							<div class="card-body text-start p-1">
								<ul type="circle">
									<li>Register First</li>
									<li>Manage Employees</li>
									<li>Manage Suppliers</li>
									<li>Manage Customers</li>
									<li>Manage transactions</li>
									<li>Edit organization details</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-4 text-end py-4 mb-4">
						<div class="card h-100 bg-transparent border-0 bor-0 d-inline-block pe-4 border-end rounded-0 shadow" style="border-width: 5px !important;">
							<div class="card-header border-0 bg-transparent">
								<b class="text-3 border-bottom bor-3 px-2">Employee Account</b>
							</div>
							<div class="card-body p-1 text-start">
								<ul type="circle">
									<li>Work under Admin</li>
									<li>Manage Suppliers</li>
									<li>Manage Customers</li>
									<li>Manage purchases</li>
									<li>Manage sales</li>
									<li>Manage transactions</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-lg-2"></div>
				</div>
			</div>
			<div class="container my-3 py-3" id="feature">
				<h3 class="text-center text-2">What will you achieve</h3>
				<p class="text-center text-1">Get various service out of your business</p>
				<div class="row">
					<div class="col-lg-6">
						<img src="images/officeteam.svg" alt="" class="w-100">
					</div>
					<div class="col-lg-6 my-2">
						<!-- Multi users -->
						<div class="text-end my-3">
							<div class="card d-inline-block text-start border-0 border-end bor-3 shadow" style="max-width: 60%; border-width: 5px !important;">
								<div class="card-header bg-white border-0  py-0">
									<b class="text-1 border-bottom bor-1 px-2">Multiple Users</b>
								</div>
								<div class="card-body bg-white ">Create multiple users for different employees inside single business </div>
							</div>
						</div>
						<!-- Multi stores -->
						<div class="text-start my-3">
							<div class="card d-inline-block text-start border-0 border-start bor-3 shadow" style="max-width: 60%; border-width: 5px !important;">
								<div class="card-header bg-white border-0  py-0">
									<b class="text-1 border-bottom bor-1 px-2">Category -> Brands -> Products</b>
								</div>
								<div class="card-body bg-white ">Products have to be recorded under 3 steps.
								First Categories, Second Brands and Last Products</div>
							</div>
						</div>
						<!-- Vendors -->
						<div class="text-end my-3">
							<div class="card d-inline-block text-start border-0 border-end bor-3 shadow" style="max-width: 60%; border-width: 5px !important;">
								<div class="card-header bg-white border-0  py-0">
									<b class="text-1 border-bottom bor-1 px-2">Vendors and Clients</b>
								</div>
								<div class="card-body bg-white ">Record each suppliers and customers as per your wish</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6 my-5">
						<!-- Multi users -->
						<div class="text-start my-3">
							<div class="card d-inline-block text-start border-0 border-start bor-3 shadow" style="max-width: 60%; border-width: 5px !important;">
								<div class="card-header bg-white border-0  py-0">
									<b class="text-1 border-bottom bor-1 px-2">Easy Product Filter</b>
								</div>
								<div class="card-body bg-white ">Products can be filtered on price basis and  </div>
							</div>
						</div>
						<!-- Multi stores -->
						<div class="text-end my-3">
							<div class="card d-inline-block text-start border-0 border-end bor-3 shadow" style="max-width: 60%; border-width: 5px !important;">
								<div class="card-header bg-white border-0  py-0">
									<b class="text-1 border-bottom bor-1 px-2">Safe History</b>
								</div>
								<div class="card-body bg-white ">History Records of purchases and sales can be accessed even a year later.</div>
							</div>
						</div>
						<!-- Vendors -->
						<div class="text-start my-3">
							<div class="card d-inline-block text-start border-0 border-start bor-3 shadow" style="max-width: 60%; border-width: 5px !important;">
								<div class="card-header bg-white border-0  py-0">
									<b class="text-1 border-bottom bor-1 px-2">Easy Customer Voucher</b>
								</div>
								<div class="card-body bg-white ">Customer bills can be generated and printed easily after the sales being made</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<img src="./images/feature.png" alt="" class="w-100">
					</div>
				</div>
			</div>
			<footer id="footer" class="py-2 px-3  text-white" style="background-image: radial-gradient( #6D5BDD, #0C0061);">
				
				<div class="container row mx-auto">
					<div class="col-md-4 text-center">
						<div class="policies p-3 h-100 mx-auto text-start" style="width: fit-content;">
							<h4 class="mb-3"><u>Our Policies</u></h4>
							<p class="text-white cp" onclick="policy('terms')">Terms And Conditions</p>
							<p class="text-white cp" onclick="policy('about')">About Us</p>
							<p class="text-white cp" onclick="policy('privacy')">Privacy Policy</p>
							<p class="text-white cp" onclick="policy('cookies')">Cookies Policy</p>
						</div>
					</div>
					<div class="col-md-4 text-center p-3 p-md-1 p-lg-3">
						<form id="contact-form">
							<input type="text" placeholder="your name" class="form-control my-2" name="name" required>
							<input type="email" placeholder="email" class="form-control my-2" required name="email">
							<textarea rows="3" class="form-control my-2" placeholder="Your Message to Us" required name="message"></textarea>
							<button class="form-control" id="contact-submit">Send Mail To Admin</button>
						</form>
					</div>
					<div class="col-md-4 text-start p-3">
						<div class="contact-details p-3 h-100 mx-auto text-start" style="width: fit-content;">
							<h4 class="mb-3"><u>Contact Details</u></h4>
							<h6>Email : <?php echo $branding["email"] ?></h6>
							<h6>Contact: <?php echo $branding["phone"] ?></h6>
							<hr>
							<h5 class="text-decoration-underline">Controller's Address</h5>
							<address><?php echo $branding["address"] ?></address>
						</div>
					</div>
					<div class="col-12 border-top">
						<div class="footer-section text-center py-3">
							copyright &copy; <?php echo date("Y") ?>  Krishna Tiwari<br>
							Powered By <a href="https://krishnat.com.np" target="_blank_" class="text-white"> krishnat.com.np</a> 
						</div>
					</div>
				</div>
			</footer>
		</div>
		<div class="external-content"></div>
	</body>
	<script src="./script/script.js"></script>
</html>