<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	if(!isset($_POST["product"]) || !is_numeric($_POST["product"]))
		show_error("No Product Found");
	$product_id = sanitize_var($_POST['product']);
	$merchant_id = $_SESSION['logged_in_merchant'];
	$stmt = $pdo -> prepare("
		SELECT products.category_id, products.brand_id, products.photo, products.unit_price, products.marked_price, products.description, 
		purchase_history.supplier_id
		FROM products
		INNER JOIN purchase_history ON products.id = purchase_history.product_id
		WHERE products.merchant_id = ? AND products.id = ?
		 ");
	$stmt -> execute([$merchant_id, $product_id]);
	$product = $stmt -> fetch();
	$stmt -> rowCount() > 0 ? show_success($product) : show_error("No Product Found");
 ?>