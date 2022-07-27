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
	$qty = is_numeric($_POST['qty']) ? sanitize_var($_POST['qty']) : null;
	$unit_price = is_numeric($_POST['unit-price']) ? sanitize_var($_POST['unit-price']) : null;
	$total_price = (!empty($unit_price) && !empty($qty)) ? ($qty * $unit_price) : null;
	$marked_price = is_numeric($_POST['marked-price']) ? sanitize_var($_POST['marked-price']) : null;
	$paid_price = is_numeric($_POST['paid-price']) ? sanitize_var($_POST['paid-price']) : null;
	$description = sanitize_var($_POST['description']);
	$remarks = sanitize_var($_POST['remarks']);
	//product image
	$file_name = "default.png";
	if(!empty($_FILES['product-img']["name"])){
		$file_type = explode("/",$_FILES['product-img']["type"])[0];
		if($file_type == "image"){
			$file_extension = pathinfo($_FILES['product-img']["name"])["extension"];
			$file_name = rand(1,999999)."_".rand(1,999999).".".$file_extension;
			move_uploaded_file($_FILES['product-img']["tmp_name"], "../stocks/".$file_name);
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