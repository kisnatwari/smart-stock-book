<?php 

	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	//header("Content-type: application/json");

	$data = [];
	$data["bill_date"] = !empty($_POST['bill-date']) ? sanitize_var($_POST['bill-date']) : show_error_string("Invalid bill date");
	$data["name"] = !empty($_POST['customer-name']) ? sanitize_var($_POST['customer-name']) : null;
	$data["address"] = !empty($_POST['customer-address']) ? sanitize_var($_POST['customer-address']) : null;
	$data["email"] = !empty($_POST['customer-email']) ? sanitize_var($_POST['customer-email']) : null;
	$data["contact"] = !empty($_POST['customer-phone']) ? sanitize_var($_POST['customer-phone']) : null;
	$data["remarks"] = !empty($_POST['remarks']) ? sanitize_var($_POST['remarks']) : null;
	$data["paid"] = is_numeric($_POST["paid-amt"]) ? $_POST['paid-amt'] : show_error_string("Invalid paid amount");
	$data["save_customer"] = !empty($_POST["save-customer"]) ? true : null;
	$data["saved"] = !empty($_POST["saved-customer"]) &&  ($_POST["saved-customer"] != "null")? sanitize_var($_POST["saved-customer"]) : null;
	$data["products_data"] = json_decode($_POST["products_data"], true);
	$products = array();
	$products_details = array();//stores product details from database
	$counter = 1;
	$total_price = 0;
	//assigning products in a json
	foreach($data["products_data"] as $product_data){
		$product_id = $product_data["product"];
		$product_qty = $product_data["qty"];
		$product_disc = $product_data["disc"];
		$stmt = $pdo -> prepare("SELECT * FROM products WHERE id= ? AND merchant_id = ?");
		$stmt -> execute([$product_id, $_SESSION["logged_in_merchant"]]);
		if($stmt -> rowCount() == 0)
			continue;
		$product = $stmt -> fetch();
		if(empty($products_details[$product_id]))
			$products_details[$product_id] = $product;
		$product_qty = is_numeric($product_qty) ? $product_qty : show_error_string("Invalid Quantity in the list.");
		$product_disc = is_numeric($product_disc) ? $product_disc : show_error_string("Invalid Discount Amount in the list.");
		$product_rate = $product["marked_price"];
		$price = intval($product_qty) * intval($product_rate);
		$price < $product_disc ? show_error_string("Discount limit exceeded for ".$product["name"]) : null;
		$product_qty = ($product_qty <1) ? show_error_string("provide at least 1 quantity to each products") : (($product_qty > $product["available_stock"]) ? show_error_string("Stock limit reached") : $product_qty);
		$product_disc = ($product_disc < 0) ? show_error_string("Discount amount should not be lesser than 0.") : (($product_disc > $product["marked_price"]) ? show_error_string("Discount amount should not be greater than the marked price of the product") : $product_disc);
		$products[$counter++] = array("product" => $product_id, "qty" => $product_qty, "disc" => $product_disc);
		$total_price +=  ($product_qty * $product_rate - $product_disc);
	}
	//checking product similarity and validating stock limit reached
	$stocks = [];
	foreach($products as $product){
		if(empty($stocks[$product["product"]]))
			$stocks[$product["product"]] = $product["qty"];
		else
			$stocks[$product["product"]] += $product["qty"];
		if($stocks[$product["product"]] > $products_details[$product["product"]]["available_stock"])
			show_error_string("Quantity to sold is more than the stocks available for ".$products_details[$product["product"]]["name"]);
	}
	if(count($products) < 1)
		show_error_string("Please provide valid products");
	if($data["paid"] > $total_price)
		show_error_string("Paid Price should not be greater than total price");
	$products = json_encode($products);
	//products assigned in a json


	$status = "";

	//generating logo
	$logo = $pdo -> prepare("SELECT logo FROM branding");
	$logo -> execute();
	$logo = $logo -> fetch()["logo"];


	//generating invoice number
	$stmt = $pdo -> prepare("SELECT `last_invoice_no` FROM `merchants` WHERE id =?");
	$stmt -> execute([ $_SESSION['logged_in_merchant']]);
	$invoice_no = intval($stmt -> fetch()["last_invoice_no"]) + 1;
	//new invoice number generated
	$customer = null;
	if(!empty($data["saved"])){
		$check_customer = $pdo -> prepare("SELECT * FROM customers WHERE id = ? AND merchant_id = ?");
		$check_customer -> execute([$data["saved"], $_SESSION['logged_in_merchant']]);
		if($check_customer -> rowCount() < 1)
			show_error_string("Invalid saved customer");
		$customer = $check_customer -> fetch();
		$stmt = $pdo -> prepare("INSERT INTO `sales_history`(`invoice_no`, `date`, `customer_id`, `product_details`, `paid_price`, `remarks`, `user_id`, `merchant_id`) VALUE(?, ?, ?, ?, ?, ?, ?, ?) ");
		if(!($stmt -> execute([$invoice_no, $data["bill_date"], $data["saved"], $products, $data["paid"], $data["remarks"], $_SESSION['logged_in_id'], $_SESSION["logged_in_merchant"]])))
			show_error_string("Something went wrong");
		$status = "success";
	}
	else if($data["save_customer"]){
		if(empty($data["name"]))
			show_error_string("Name is compulsory to save the customer");
		if(empty($data["email"]) && empty($data["contact"]))
			show_error_string("At least contact or email is required to save a customer");
		$stmt = $pdo -> prepare("SET @insert_on = 'sale'; SET @invoice_no = ?; SET @bill_date = ?; SET @product_details = ?; SET @paid = ?; SET @remarks = ?; INSERT INTO `customers`(`name`,  `email`, `contact`, `address`,	`user_id`, `merchant_id`) VALUE(?, ?, ?, ?, ?, ?)");
		if(!$stmt -> execute([$invoice_no, $data["bill_date"], $products, $data["paid"], $data["remarks"],
			$data["name"], $data["email"], $data["contact"], $data["address"], $_SESSION['logged_in_id'], $_SESSION['logged_in_merchant']
		]))
			show_error_string("Something Went Wrong");
		$status = "success";
	}
	else{
		$unsaved_customer = json_encode(array("name" => $data["name"], "contact" => $data["contact"], "email" => $data["email"], "address" => $data["address"]));
		$stmt = $pdo -> prepare("INSERT INTO `sales_history`(`invoice_no`, `date`, `unsaved_customer`, `product_details`, `paid_price`, `remarks`, `user_id`, `merchant_id`) VALUE(?, ?, ?, ?, ?, ?, ?, ?)");
		if(!$stmt -> execute([$invoice_no, $data["bill_date"], $unsaved_customer, $products, $data["paid"], $data["remarks"], $_SESSION["logged_in_id"], $_SESSION["logged_in_merchant"]]))
			show_error_string("Something Went Wrong");
		$status = "success";
	}
	if($status == "success"){
		/*deleting items from stock*/
		$products = json_decode($products, true);
		foreach($products as $product){
			$qty = $product["qty"];
			$stmt = $pdo -> prepare("UPDATE products SET available_stock = available_stock-$qty WHERE id = ? AND merchant_id = ?");
			$stmt -> execute([$product["product"], $_SESSION["logged_in_merchant"]]);
		}
	}
	$stmt = $pdo -> prepare("SELECT * FROM merchants WHERE id = ?");
	$stmt -> execute([$_SESSION['logged_in_merchant']]);
	$merchant_data = $stmt -> fetch();


	if(!empty($customer) && is_array($customer)){
		$data["name"] = $customer["name"];
		$data["contact"] = $customer["contact"];
		$data["email"] = $customer["email"];
		$data["address"] = $customer["address"];
	}
	?>


			<div id="sales-voucher-form">
			<div class="row">
				<div class="col-2 my-2 text-center">
					<img src="<?php echo (empty($merchant_data["logo"])) ? "data:image/png;base64,".base64_encode($logo) : $root.'merchant/logos/'.$merchant_data["logo"] ?>" alt=""  style="max-width: 70px; max-height: 70px;">
				</div>
				<div class="col-1"></div>
				<div class="col-6">
					<h3 class="text-center"><?php echo $merchant_data["name"] ?></h3>
					<p class="text-center"><?php echo $merchant_data["phone"] ?> <br> <?php echo $merchant_data["address"] ?></p>
				</div>
				<div class="col-3">
					 <div class="position-relative">
					 	Date: <span class="bill-date"><?php echo date("Y-m-d") ?></span> <input type="date" name="bill-date" max="<?php echo date("Y-m-d") ?>" min="2022-01-01" value="<?php echo date("Y-m-d") ?>"  class="date d-none form-control position-absolute px-1" style="top: 0; width: 100%; "> <br>
					 	Voucher No:. <?php echo $invoice_no; ?>
					 	</div>
				</div>
			</div>

			<div class="customer-info mb-3">
				<h5>Customer Name: <?php echo $data["name"] ?> </h5>
				<div class="row">
					<div class="col-sm-6 my-1">
						<h6>Phone: <?php echo $data["contact"]; ?></h6>
					</div>
					<div class="col-sm-6 my-1">
						<h6>Email: <?php echo $data["email"]; ?></h6>
					</div>
				</div>
				<div class="input-group my-1">
					<h6>Address: <?php echo $data["address"]; ?></h6>
				</div>
			</div>
			<table class="w-100 table text-center" cellpadding="5" cellspacing="0" id="products-table">
				<thead class="w-100">
					<tr class="w-100">
						<th width="50px" class="p-0">S.No</th>
						<th width="300px" class="p-0 text-start">Product Name</th>
						<th width="50px" class="p-0">Qty</th>
						<th width="90px" class="p-0">Rate</th>
						<th width="65px" class="disc-col p-0">Disc</th>
						<th width="120px" class="p-0">Amount</th>
					</tr>
				</thead>

				<!-- Voucher table body starts here -->
				<tbody class="voucher-table-body">
					<?php $counter = 1;
					foreach ($products as $product) { 
						$stmt = $pdo -> prepare("SELECT * FROM products WHERE id= ? AND merchant_id = ?");
						$stmt -> execute([$product["product"], $_SESSION["logged_in_merchant"]]);
						$product_data = $stmt -> fetch();
						?>
					<tr class="p-0 pt-1">
						<td class="text-center p-0" valign="bottom"><?php echo $counter++ ?></td>
						<td class="p-0" valign="bottom">
							<h6><?php echo $product_data["name"]; ?></h6>
						</td>
						<td class="p-0 pt-1 text-start" valign="bottom">
							<h6><?php echo $product["qty"]; ?></h6>
						</td>
						<td class="p-0 pt-1" valign="bottom">
							<h6><?php echo $product_data["marked_price"]; ?></h6>
						</td>

						<!-- discount column -->
						<td class="disc-col p-0 pt-1" valign="bottom">
							<h6><?php echo $product["disc"]; ?></h6>
						</td>
						<!-- discount column ends -->

						<td class="p-0 pt-1" valign="bottom">
							<h6><?php echo (intval($product["qty"]) * intval($product_data["marked_price"]) - intval($product["disc"])) ?></h6>
						</td>
					</tr>
				<?php
					}
			 	?>
				</tbody>
				<!-- voucher table body ends here -->
			</table>
			<div class="row">
				<div class="col-sm-6">
					<?php echo "<pre>".$data["remarks"]."</pre>" ?>
				</div>
				<div class="col-sm-6">
					<table class="table table-borderless footer-table ms-auto" style="width: fit-content; max-width: 90%;">
						<tr>
							<td width="130px">Total Amount</td>
							<td>	<h6 class="form-control border-0 total_amt_field p-0 m-0">Rs <?php echo $total_price; ?></h6></td>
			 			</tr>
						<tr>
							<td>Paid Amount</td>
							<td><h6 class="form-control border-0 total_amt_field p-0 m-0">Rs <?php echo $data["paid"]; ?></h6></td>
						</tr>
						<?php if($total_price > $data["paid"]) { ?>
							<tr>
							<td>Due Amount</td>
							<td><h6 class="form-control border-0 total_amt_field p-0 m-0">Rs <?php echo($total_price - $data["paid"]) ?></h6></td>
						</tr>
						<?php } ?>
					</table>
				</div>
			</div>
			<h6 class=" border-top pt-2 border-dark text-center mt-2 b-0 bill-footer"><sub><big>*****</big></sub> Thank You for visiting. Hope to see you soon <sub><big>*****</big></sub></h6>
			</div>