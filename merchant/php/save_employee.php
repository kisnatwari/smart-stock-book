<?php 
	require '../../common/db.php';
	require '../../common/imp_functions.php';
	header("Content-type: application/json");
	$name = empty($_POST['name']) ? show_error("Please provide name") : sanitize_var($_POST['name']);
	$email = empty($_POST['name']) ? show_error("Please provide Email") : (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) ? sanitize_var($_POST['email']) : show_error("Invalid Email"));
	$o_password = sanitize_var($_POST["password"]);
	$password = password_hash(sanitize_var($_POST["password"]), PASSWORD_DEFAULT);
	$address = empty($_POST['address']) ? show_error("Please provide address..") : sanitize_var($_POST["address"]);
	$phone = empty($_POST['phone']) ? null : sanitize_var($_POST["phone"]);


		//validating name
		if(!preg_match("/^[a-zA-Z\s]+$/",$name))
		   show_error("The name you've provided is invalid");

		//validating phone number
		if($phone != null){
			if(!preg_match("/^\+?[ -]?(?:(?:(?:98|97)-?\d{8})|(?:01-?\d{7}))$/", $phone))
				show_error("Invalid contact number of employee..");
		}

		//validating address
		if($address != null){
			if(!preg_match("/^[a-zA-Z0-9\s,.+-]+$/",$address))
			    show_error("Invalid characters in address");
		}


	$status = (!empty($_POST['activation']) && $_POST['activation'] == "yes") ? "activated" : "deactivated";
	//checking user existance with provided email in database
	$stmt = $pdo -> prepare("SELECT * FROM users WHERE `email`=? ");
	$stmt -> execute([$email]);
	if($stmt -> rowCount() > 0)
		show_error("user with this email already exists");
	
	$stmt = $pdo -> prepare("SET @phone = ?; SET @address = ?; INSERT INTO users(name, email, password, role, status, merchant_id) VALUES(?, ?, ?, ?, ?, ?)");
	if(!$stmt -> execute([$phone, $address, $name, $email, $password, "employee", $status, $_SESSION["logged_in_merchant"]]))
		show_error("Failed to create an employee");

	//show_error("SELECT name FROM merchants WHERE id = ".$_SESSION['logged_in_merchant']);
	$stmt = $pdo -> prepare("SELECT name FROM merchants WHERE id = ?");
	$stmt -> execute([$_SESSION['logged_in_merchant']]);
	$merchant_name = $stmt -> fetch()["name"];
	$header = "MIME-Version:1.0\r\nContent-Type: text/html;charset=ISO-8859-1\r\n";
	$message = '<html>
    <head>
    <body style="display: flex; align-items: center; justify-content: center; padding: 10px;">
        <div style="padding: 10px; margin: 10px; box-shadow: 0 0 5px 5px #ccc; border-radius: 5px;">
            <strong style="font-family: cursive; font-size: 17px;">Hi! '.$name.',<br> You are now being registered in <span style="color: #1F00F2">smartstockbook</span> as an employee of '.$merchant_name.' ... </strong>
            <br>
            <strong>Your initial password is: '.$o_password.'</strong><br>
            <strong><a href="'.$root.'">Click here</a> to get into the system</strong>
        </div>
    </body>
    </html>';
     if(mail($email, $merchant_name." Employee creation", $message, $header))
		show_success("Employee created successfully");
	else
		show_error("Employee account is created but mail has not been sent to the employee");
?>