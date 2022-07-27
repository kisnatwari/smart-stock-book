<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	$product_name = !empty($_POST['product-name']) ? sanitize_var($_POST['product-name']) : show_error("Product name missing");

	//supplier name validation
	$supplier_name = null;
	if($_POST['supplier-name'] && $_POST['supplier-name'] != "random"){
		$stmt = $pdo -> prepare("SELECT * FROM suppliers WHERE `merchant_id` = ? AND `id` = ?");
		$stmt -> execute([$_SESSION['logged_in_merchant'], $_POST['supplier-name']]);
		if($stmt -> rowCount() == 1)
			$supplier_name = $_POST['supplier-name'];
	}

	if(!preg_match("/^[a-zA-Z0-9\s(_')+-]+$/",$product_name))
	    show_error("Only alphanumeric characters and space with given special characters are allowed <b>()'_+-</b>");

	//check if product already exists
	$stmt = $pdo -> prepare("SELECT `name` FROM `products` WHERE `merchant_id` = ? AND `name` = ?");
	$stmt -> execute([$_SESSION['logged_in_merchant'], $product_name]);
	if($stmt -> rowCount() > 0)
		show_error("Product with name '$product_name' already exists.. Please use different name of a product");

	//category and brand validation
	$category = null;
	$brand = null;
	if(is_numeric($_POST['category'])){
		$stmt = $pdo -> prepare("SELECT * FROM categories WHERE `merchant_id` = ? AND id = ?");
		$stmt -> execute([$_SESSION["logged_in_merchant"], $_POST['category']]);
		if($stmt -> rowCount() == 1){
			$category = $_POST['category'];
			if($_POST['brand'] != "default"){
				$stmt = $pdo -> prepare("SELECT * FROM brands WHERE merchant_id = ? AND category_id = ? AND id = ?");
				$stmt -> execute([$_SESSION['logged_in_merchant'], $category, $_POST['brand']]);
				if($stmt -> rowCount() == 1)
					$brand = $_POST['brand'];
			}
		}
	}
	if(!$category)		show_error("invalid category");

	//validating and assigning other details 
	$qty = is_numeric($_POST['qty']) ? sanitize_var($_POST['qty']) : 0;
	$unit_price = is_numeric($_POST['unit-price']) ? sanitize_var($_POST['unit-price']) : show_error("Invalid unit price");
	$total_price = (!empty($unit_price) && !empty($qty)) ? ($qty * $unit_price) : null;
	$marked_price = is_numeric($_POST['marked-price']) ? sanitize_var($_POST['marked-price']) : show_error("Invalid marked price");
	$paid_price = is_numeric($_POST['paid-price']) ? sanitize_var($_POST['paid-price']) : null;
	$description = sanitize_var($_POST['description']);
	$remarks = sanitize_var($_POST['remarks']);
	

	if($qty < 1)
		show_error("Invalid Quantity");

	if($qty > 1500)
		show_error("You cannot assign more than 1500 products at once. Better to record purchases partially");

	if($unit_price < 1)
		show_error("Invalid cost price");

	if($unit_price > 10000000)
		show_error("Products having unit price more than 10000000 are not accepted");

		if($marked_price > 30000000)
		show_error("Products having marked price more than 30000000 are not accepted");

	if($paid_price < 0)
		show_error("Invalid paid price");

	if($total_price < $paid_price)
		show_error("Failed.. Paid more than the total price??");

	if($unit_price > $marked_price)
		show_error("marked Price cannot be less then unit price");
	
	//product image
	$file_name = "default.png";
	if(!empty($_FILES['product-img']["name"])){
		$file_type = explode("/",$_FILES['product-img']["type"])[0];
		if($file_type == "image"){
			$file_extension = pathinfo($_FILES['product-img']["name"])["extension"];
			$file_name = rand(1,999999)."_".rand(1,999999).".".$file_extension;
			move_uploaded_file($_FILES['product-img']["tmp_name"], "../stocks/".$file_name);
		}
		else{
			show_error("Image type not supported");
		}
	}




	$stmt = $pdo -> prepare(" SET @supplier = ?; SET @unit_price = ?; SET @total_price = ?; SET @paid_price = ?; SET @stock = ?; SET @remarks = ?;
	INSERT INTO products(name, category_id, brand_id, available_stock, photo, unit_price, marked_price, user_id, merchant_id, description) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	if($stmt -> execute([
		$supplier_name, $unit_price, $total_price, $paid_price, $qty, $remarks,
		$product_name, $category, $brand, $qty, $file_name, $unit_price, $marked_price, $_SESSION["logged_in_id"], $_SESSION["logged_in_merchant"], $description
	]))
		show_success("product-added-successfully");

 ?>