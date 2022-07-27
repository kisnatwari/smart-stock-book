<?php
	require_once "../common/imp_functions.php";
	require_once "../common/db.php";
	//session_destroy();
	if(!is_logged_in())
		header("Location:$root");
		if($_SESSION['logged_in_role'] != "merchant"){
		header("Location: ./../index.php");
	}
?>
<?php
	//table creation
	if(!$db -> table_exists("suppliers")){
	$db -> create_table("suppliers", [
	"`id` int primary key AUTO_INCREMENT",
	"`name` VARCHAR(150)",
	"`email` VARCHAR(150)",
	"`contact` VARCHAR(150)",
	"`address` VARCHAR(150)",
	"`user_id` INT",
	"`merchant_id` INT",
	"FOREIGN KEY(`user_id`) REFERENCES users(id) ON DELETE CASCADE",
	"FOREIGN KEY(`merchant_id`) REFERENCES merchants(id) ON DELETE CASCADE"
	]);
	}
		if(!$db -> table_exists("customers")){
	$db -> create_table("customers", [
	"`id` int primary key AUTO_INCREMENT",
	"`name` VARCHAR(150)",
	"`email` VARCHAR(150)",
	"`contact` VARCHAR(150)",
	"`address` VARCHAR(150)",
	"`user_id` INT",
	"`merchant_id` INT",
	"FOREIGN KEY(`user_id`) REFERENCES users(id) ON DELETE CASCADE",
	"FOREIGN KEY(`merchant_id`) REFERENCES merchants(id) ON DELETE CASCADE"
	]);
	}
	if(!$db -> table_exists("categories")){
			$db -> create_table("categories",	[
					"id INT PRIMARY KEY AUTO_INCREMENT",
					"category VARCHAR(100)",
				"user_id INT",
				"merchant_id INT",
			"FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE",
			"FOREIGN KEY (merchant_id) REFERENCES merchants(id) ON DELETE CASCADE"
		]);
	}
	if(!$db -> table_exists("brands")){
			$db -> create_table("brands",	[
				"id INT PRIMARY KEY AUTO_INCREMENT",
				"brand VARCHAR(100)",
			"category_id INT NOT NULL",
				"user_id INT",
				"merchant_id INT",
			"FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE",
			"FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE",
				"FOREIGN KEY (merchant_id) REFERENCES merchants(id) ON DELETE CASCADE"]	);
	}
		if(!$db->table_exists("products") || !$db -> table_exists("purchase_history")){
		$product_table = $db -> create_table("products", [
			"id int primary key AUTO_INCREMENT",
			"name VARCHAR(150) NOT NULL",
			"category_id int NOT NULL",
			"brand_id int",
			"available_stock int",
			"photo VARCHAR(150)",
			"unit_price int",
			"marked_price int",
			"user_id int",
			"merchant_id int",
			"description TEXT",
			"FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE CASCADE",
			"FOREIGN KEY(brand_id) REFERENCES brands(id) ON DELETE SET NULL",
			"FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE",
			"FOREIGN KEY(merchant_id) REFERENCES merchants(id) ON DELETE CASCADE"
		]);
		$purchase_table = $db -> create_table("purchase_history", [
			"id INT PRIMARY KEY AUTO_INCREMENT",
			"product_id INT",
			"date TIMESTAMP default current_timestamp()",
			"supplier_id INT",
			"unit_price int",
			"total_price int",
			"paid_price int",
			"stock int",
			"user_id int",
			"merchant_id int",
			"remarks TEXT",
			"FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE CASCADE",
			"FOREIGN KEY(supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL",
			"FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE",
			"FOREIGN KEY(merchant_id) REFERENCES merchants(id) ON DELETE CASCADE"
		]);
//creating triggers on insert and update
		$stmt = $pdo -> prepare("
			CREATE TRIGGER product_insert
			AFTER INSERT ON `products`
			FOR EACH ROW BEGIN
			INSERT INTO purchase_history(`product_id`, `supplier_id`, `unit_price`, `total_price`, `paid_price`, `stock`, `user_id`, `merchant_id`, `remarks`) VALUE(new.id, @supplier, @unit_price, @total_price, @paid_price, @stock, new.user_id, new.merchant_id, @remarks);
			END;
		");
		$stmt -> execute();
		$stmt = $pdo -> prepare("
			CREATE TRIGGER product_update
			AFTER UPDATE ON `products`
			FOR EACH ROW BEGIN
			IF(@update_on = 'purchase') THEN
				INSERT INTO purchase_history(`product_id`, `supplier_id`, `unit_price`, `total_price`, `paid_price`, `stock`, `user_id`, `merchant_id`, `remarks`) VALUE(new.id, @supplier, @unit_price, @total_price, @paid_price, @stock, new.user_id, new.merchant_id, @remarks);
			END IF;
			END;
		");
		if(!$stmt -> execute()) show_error($stmt);
	}
	if(!$db -> table_exists("sales_history")){
		$sales_table = $db -> create_table("sales_history", [
			"id int PRIMARY KEY AUTO_INCREMENT",
			"invoice_no INT NOT NULL",
			"`date` DATE",
			"customer_id int",
			"unsaved_customer JSON",
			"product_details JSON",
			"paid_price VARCHAR(20)",
			"remarks VARCHAR(6000)",
			"user_id int",
			"merchant_id int",
			"FOREIGN KEY(customer_id) REFERENCES customers(id) ON DELETE SET NULL",
			"FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE",
			"FOREIGN KEY(merchant_id) REFERENCES merchants(id) ON DELETE CASCADE"
		]);
		$user_id = $_SESSION['logged_in_id'];
		$merchant_id = $_SESSION['logged_in_merchant'];
		$stmt = $pdo->prepare("
			CREATE TRIGGER save_customer_on_sale
			AFTER INSERT on `customers`
			FOR EACH ROW BEGIN
			IF(@insert_on = 'sale') THEN
			INSERT INTO `sales_history`(`invoice_no`, `date`, `customer_id`, `product_details`, `paid_price`, `remarks`, `user_id`, `merchant_id`) VALUE(@invoice_no, @bill_date, new.id, @product_details, @paid, @remarks, new.user_id, new.merchant_id);
			END IF;
			END;
			");
		$stmt -> execute();
		$stmt  = $pdo -> prepare("
			CREATE TRIGGER update_voucher_num
			AFTER INSERT ON `sales_history`
			FOR EACH ROW BEGIN
			UPDATE `merchants` SET `last_invoice_no` = new.invoice_no WHERE `id` = new.merchant_id;
			END;
			");
		$stmt -> execute();
	}
	$merchant_info = $pdo -> prepare("SELECT * FROM merchants WHERE id = ?");
	$merchant_info -> execute([$_SESSION['logged_in_merchant']]);
	$merchant_info = $merchant_info -> fetch();
	$merchant_logo = $merchant_info["logo"];
	if(!empty($merchant_logo))
		$merchant_logo = $root."merchant/logos/".$merchant_logo;
	else
		$merchant_logo = $root."images/logo.png";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Smartstockbook</title>
		<link rel="stylesheet" href="../style/bootstrap.css">
		<link rel="stylesheet" href="style/style.css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Neonderthaw&family=Poppins:ital,wght@0,400;1,900&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Neonderthaw&family=Poppins:ital,wght@0,400;1,900&family=Righteous&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
		<script src="../script/bootstrap.bundle.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script>var root = "<?php echo $root ?>" ;</script>
		<?php require_once "../common/common_css.php" ?>
	</head>
	<body class="w-100 h-100" style="background-color: #efefef;">
		<div class="w-100 h-100 inner-body">
			<div class="main-content w-100 d-flex justify-content-center align-items-center" style="height: 100vh;">
				<section class="position-relative pe-1" data-sidebar-hide="false" data-sidebar-size="max">
					<aside class="sidebar py-5 text-center position-absolute">
						<?php
						$logo = $pdo -> prepare("SELECT logo FROM branding");
						$logo -> execute();
						$logo = $logo -> fetch()["logo"];
						?>
						<img src="<?php echo "data:image/png;base64,".base64_encode($logo) ?>" alt="" class="my-2 sidebar-logo">
						<div class="brand-name text-1 mb-2 sidebar-max-text px-1 py-2"><b>smartstockbook</b></div>
						<hr class="text-2" style="height: 5px;">
						<nav class="option">
							<ul class="nav nav-tabs flex-column position-relative sidebar-nav">
								<li class="nav-item text-start mx-2 rounded dashboard-button active" title="Dashboard" data-url="dashboard_design">
									<a class="nav-link border-0 text-1" href="javascript:void(0)"> <i class="mdi mdi-view-dashboard-outline"></i> <span class="sidebar-max-text">Dashboard</span></a>
								</li>
								<li class="nav-item text-start mx-2 rounded" title="Categories" data-url="categories_design">
									<a class="nav-link border-0 text-1" href="javascript:void(0)"><i class="mdi mdi-sitemap-outline"></i> <span class="sidebar-max-text">Categories</span></a>
								</li>
								<li class="nav-item text-start mx-2 rounded" title="Brands" data-url="brands_design">
									<a class="nav-link border-0 text-1" href="javascript:void(0)"><i class="mdi mdi-star-outline"></i> <span class="sidebar-max-text">Brands</span></a>
								</li>
								<li class="nav-item text-start mx-2 rounded products-button" title="Products" data-url="products_design">
									<a class="nav-link border-0 text-1" href="javascript:void(0)"><i class="mdi mdi-basket-outline"></i> <span class="sidebar-max-text">Products</span></a>
								</li>
								<li class="nav-item text-start mx-2 rounded purchase-btn" title="Purchases" data-url="purchases_design">
									<a class="nav-link border-0 text-1" href="javascript:void(0)"><i class="mdi mdi-home-import-outline"></i> <span class="sidebar-max-text">Purchase</span></a>
								</li>
								<li class="nav-item text-start mx-2 rounded sale-btn" title="Purchases" data-url="sales_design">
									<a class="nav-link border-0 text-1" href="javascript:void(0)"><i class="mdi mdi-home-export-outline"></i> <span class="sidebar-max-text">Sale</span></a>
								</li>
								<li class="nav-item text-start mx-2 rounded history-btn" title="History" data-url="history_design">
									<a class="nav-link border-0 text-1" href="javascript:void(0)"><i class="mdi mdi-history"></i> <span class="sidebar-max-text">History</span></a>
								</li>
								<li class="nav-item text-start mx-2 rounded employee-btn" title="Employees" data-url="employees_design">
									<a class="nav-link border-0 text-1" href="javascript:void(0)"><i class="mdi mdi-account-outline"></i> <span class="sidebar-max-text">Employees</span></a>
								</li>
							</ul>
						</nav>
						<div class="aside-footer">
							<a href="<?php echo $root?>/php/logout.php" class="btn light-btn px-2 py-1 mt-4 mb-2 border-0 text-1"><b> <span class="sidebar-max-text">Log Out</span><i class="mdi mdi-logout-variant"></i> </b></a>
						</div>
					</aside>
					<div class="content px-4 py-2 position-relative">
						<div class="row mb-2 top-content">
							<div class="col-12 d-flex flex-wrap justify-content-between py-1 align-items-center rounded-0 bg-white sh">
								<img src="./sidebar.svg" class="cp sidebar-toggle" width="25" title="toggle sidebar" data-bs-placement="left">
								<div class="account-content dropdown justify-content-center align-items-center cp" style="user-select: none;">
									<div class="mx-2 p-0 m-0 d-flex justify-content-between align-items-center text-center" data-bs-toggle="dropdown">
										<div class="d-inline-block sh me-3 rounded-circle my-2" style="width: 35px; height: 35px; background-image: url(<?php echo $merchant_logo ?>); background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
										<b>
										<?php
										//getting name of user merchant
												$user_id = $_SESSION['logged_in_id']	;
											$stmt = $pdo -> prepare("SELECT users.name FROM users INNER JOIN merchants ON merchants.id = users.merchant_id WHERE users.id = $user_id");
											$stmt -> execute();
											echo($stmt -> fetch()["name"]);
										?>
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
			<div class="external-content">
			</div>
		</div>
		<div class="dialog-content"></div>
	</body>
	<script src="script/script.js"></script>
</html>