<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type:application/json");
	if(!isset($_POST['categories']))
		show_error("No category Found");
	$categories = $_POST['categories'];
	$categories = sanitize_var($categories);
	$categories = array_intersect_key($categories, array_unique(array_map('strtolower', $categories)));
	/*checking pre existance of categories*/
	foreach($categories as $c){
		$q = $pdo -> prepare("SELECT * FROM `categories` WHERE merchant_id = ? and `category` = ?");
		$q -> execute([$_SESSION["logged_in_merchant"], $c]);
		if($q -> rowCount() > 0)
			show_error("$c already exists. Please Try again.");

		if(strlen($c) < 3)
			show_error("Minimum Length of each categories is 3 characters");

		if(strlen($c) > 20)
			show_error("Maximum Length of each categories is 20 characters");
	}

	//query creation starts here
	$i = 0;
	$query = "INSERT INTO categories(`category`, `user_id`, `merchant_id`) VALUES";
	$values = [];
	//generating query according to the number of categories. Example:
	//insert into categories('category', 'user_id') values(?, ?), (?, ?), (?, ?);

	foreach($categories as $category){
		if(!preg_match("/^[a-zA-Z\s]+$/",$category)){
		    show_error("Something went wrong. Only Alphabets are allowed");
		}
		$i++;
		$query = $query." ( ?, ?, ?)" ;
		if($i != count($categories)) 
			$query = $query."," ;
		array_push($values, $category);
		array_push($values, $_SESSION['logged_in_id']);
		array_push($values, $_SESSION['logged_in_merchant']);
	}




	$stmt = $pdo -> prepare($query);
	if(!$stmt -> execute($values))
		show_error("Something went wrong!! Please wait");
	$stmt = $pdo -> prepare("SELECT `id`, `category` FROM `categories` WHERE `merchant_id` = ?");
	$stmt -> execute([$_SESSION['logged_in_merchant']]);
	if($all_categories = $stmt -> fetchAll())
		show_message("data-updated", json_encode($all_categories));
 ?>