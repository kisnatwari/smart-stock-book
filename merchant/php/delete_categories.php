<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	if(isset($_POST["id"])){
		$data = sanitize_var([$_POST['id']]);
		$merchant_id = $_SESSION['logged_in_merchant'];
		$stmt = $pdo -> prepare("DELETE FROM `categories` WHERE `merchant_id` = ? AND `id`= ? ");
		($stmt -> execute([$merchant_id, $data[0]]))	?		show_success("<p class='p-0 px-2 m-0 text-1'> <b><i class='fa fa-check'></i> category deleted successfully </b></p>") 	:	show_error("<p class='p-0 px-2 m-0 text-1'> <i class='fa fa-times'></i> <b> failed to delete category </b></p>");
	}
 ?>