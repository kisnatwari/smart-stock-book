<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	if(!isset($_POST['category']))
		show_error("No category Found");
	if(!isset($_POST['brands']))
		show_error("No Brands Found");
	$brands = $_POST['brands'];
	$brands = sanitize_var($brands);
	$category_id = $_POST['category'];
	$merchant_id = $_SESSION["logged_in_merchant"];
	$query = "INSERT INTO brands(`brand`, `category_id`, `user_id`, `merchant_id`) VALUES";
	$values = [];
	$i=0;
	$brands = array_intersect_key($brands, array_unique(array_map('strtolower', $brands)));;
	foreach($brands as $brand){
		$q = $pdo -> prepare("SELECT * FROM `brands` WHERE merchant_id = ? AND `category_id` = ? AND `brand` = ?");
		$q -> execute([$_SESSION["logged_in_merchant"], $category_id, $brand]);
		if($q -> rowCount() > 0)
			show_error("$brand already exists in this category. Please Try again.");

		$query = $query." (?, ?, ?, ?) " ;
		$i++;
		if($i != count($brands))
			$query = $query."," ;
		array_push($values, $brand);
		array_push($values, $category_id);
		array_push($values, $_SESSION['logged_in_id']);
		array_push($values, $_SESSION['logged_in_merchant']);
	}
	//query is ready now
	$stmt = $pdo -> prepare($query);
	($stmt -> execute($values)) ? show_success("Brands saved successfully...") : show_error("Failed to save brands...");
 ?>