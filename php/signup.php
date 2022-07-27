<?php 
	require_once '../common/db.php';
	require_once '../common/imp_functions.php';
	header("Content-type:application/json");

	//check if all data are available
	die_on_post_miss(array("Name" => "name", "Phone Number" => "phone", "Email address" => "email", "Password field" => "pw", "Password confirmation field"=>"c_pw", "User's Address"=>"address"));

	//checking password and confirm password similarity
	if($_POST["pw"] != $_POST['c_pw'])		show_error("Password and confirm password are not same");



	//sanitizing variables
	$data = sanitize_var([$_POST['name'], $_POST['phone'], $_POST['email'], $_POST["pw"], $_POST['address']]);

	//validating name
	if(!preg_match("/^[a-zA-Z\s]+$/",$data[0]))
	   show_error("Some of the characters used in name are considered invalid");

	 	//checking if email is valid
		if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
			show_error("Invalid email");
	 	if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $data[2]))
	 		show_error("Invalid Email Format");

	//validating phone number
	if(!preg_match('/^\+?[ -]?(?:(?:(?:98|97)-?\d{8})|(?:01-?\d{7}))$/', $data[1]))
		show_error("Mobile number should be of 10 digits starting with 98 or 97");
	
	//validating address
	if(!preg_match("/^[a-zA-Z0-9\s,.+-]+$/",$data[4]))
	    show_error("Invalid characters in address");
	

	//hashing password
	$data[3] = password_hash($data[3], PASSWORD_DEFAULT);
	//checking user existance with provided email in database
	$stmt = $pdo -> prepare("SELECT * FROM users WHERE `email`=? ");
	$stmt -> execute([$data[2]]);
	if($stmt -> rowCount() > 0)
		show_error("user with this email already exists");

		$file_name = null;
	if(!empty($_FILES['logo-img']["name"])){
		$file_type = explode("/",$_FILES['logo-img']["type"])[0];
		if($file_type == "image"){
			$file_extension = pathinfo($_FILES['logo-img']["name"])["extension"];
			$file_name = rand(1,999999)."_logo_".rand(1,999999).".".$file_extension;
			move_uploaded_file($_FILES['logo-img']["tmp_name"], "../merchant/logos/".$file_name);
		}
		else{
			show_error("Image type not supported");
		}
	}

		//inserting data and sending mail for user email verification
	$stmt = $pdo -> prepare(" SET @email = ?; SET @password = ?; INSERT INTO merchants(`name`, `logo`, `address`, `phone`) VALUES(?, ?, ?, ?);");
	if($stmt -> execute([$data[2], $data[3], $data[0], $file_name, $data[4], $data[1]])){
		$rand_code = rand(100000, 999999);
		$_SESSION['email'] = $data[2];
		$_SESSION['code'] = $rand_code;
		$data = array("email" => $data[2], "code" => $rand_code);
		$url = $root."pages/verification_form.php";
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		show_message("registered",curl_exec($curl));
		curl_close($curl);
	}
	else
		show_error("something went wrong! Please try again later");
 ?>