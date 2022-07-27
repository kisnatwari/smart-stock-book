<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	if(!isset($_POST['category']))
		show_error("No category Found");
	if(!isset($_POST['brands']))
		show_error("No Brands Found");
	$brands = $_POST['brands'];
	$category_id = $_POST['category'];
	$merchant_id = $_SESSION["logged_in_merchant"];

	$query = "INSERT INTO brands(`brand`, `category_id`, `user_id`, `merchant_id`) VALUES";
	$values = [];
	$i=0;
	foreach($brands as $brand){
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