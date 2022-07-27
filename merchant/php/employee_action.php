<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	if(isset($_POST['action']) && $_POST['action'] == "edit"){
		$merchant_id = $_SESSION['logged_in_merchant'];
		$id = is_numeric($_POST['id']) ? $_POST['id'] : show_error("Something went wrong.. Try again later");
		$employee_name = !empty($_POST['employee_name']) ? sanitize_var($_POST['employee_name']) : show_error("No name found");
		$employee_contact = !empty($_POST['employee_contact']) ? sanitize_var($_POST['employee_contact']) : null;
		$employee_address = !empty(trim($_POST['employee_address'])) ? sanitize_var($_POST['employee_address']) : null;

		//validating name
		if(!preg_match("/^[a-zA-Z\s]+$/",$employee_name))
		   show_error("The name you've provided is invalid");

		//validating phone number
		if($employee_contact != null){
			if(!preg_match("/^\+?[ -]?(?:(?:(?:98|97)-?\d{8})|(?:01-?\d{7}))$/", $employee_contact))
				show_error("Invalid contact number of employee..");
		}

		//validating address
		if($employee_address != null){
			if(!preg_match("/^[a-zA-Z0-9\s,.+-]+$/",$employee_address))
			    show_error("Invalid characters in address");
		}

		$stmt = $pdo -> prepare("UPDATE users SET name = ? WHERE id = ? AND merchant_id = ?; UPDATE employees SET phone = ?, address = ? WHERE user_id = ?");
		$stmt -> execute([$employee_name, $id, $merchant_id, $employee_contact, $employee_address, $id]) ? show_success("updated successfully") : show_error("failed");
	}

		if(isset($_POST['action']) && $_POST['action'] == "delete"){
			$merchant_id = $_SESSION["logged_in_merchant"];
			$id = is_numeric($_POST['id']) ? $_POST['id'] : show_error("Something went wrong.. Try again later");
			$stmt = $pdo -> prepare("UPDATE users SET email = null, merchant_id = null WHERE id = ? AND merchant_id = ?");
			($stmt -> execute([$id, $merchant_id])) ? show_success("deleted") : show_error("failed");
		}


		if(isset($_POST['action']) && $_POST['action'] == "deactivate"){
			$merchant_id = $_SESSION["logged_in_merchant"];
			$id = is_numeric($_POST['id']) ? $_POST['id'] : show_error("Something went wrong.. Try again later");
			$stmt = $pdo -> prepare("UPDATE users SET status = 'deactivated' WHERE id = ? AND merchant_id = ?");
			($stmt -> execute([$id, $merchant_id])) ? show_success("deactivated") : show_error("failed");
		}


		if(isset($_POST['action']) && $_POST['action'] == "activate"){
			$merchant_id = $_SESSION["logged_in_merchant"];
			$id = is_numeric($_POST['id']) ? $_POST['id'] : show_error("Something went wrong.. Try again later");
			$stmt = $pdo -> prepare("UPDATE users SET status = 'activated' WHERE id = ? AND merchant_id = ?");
			($stmt -> execute([$id, $merchant_id])) ? show_success("activated") : show_error("failed");
		}
 ?>