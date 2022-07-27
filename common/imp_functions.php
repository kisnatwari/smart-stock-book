<?php
    session_start();
    $root = "http://localhost/ssb/"; 

	function sanitize_var($data){
		if(is_array($data)){
			$sanitized_data = [];
			foreach($data as $val){
				array_push($sanitized_data, sanitize_var($val));
			}
			return $sanitized_data;
		}
		$data = htmlspecialchars(trim($data));
		return $data;
	}


	function die_on_post_miss($data){
		/*
			array("Email" => "email")
			Email is missing if $_POST["email"] is not set
		*/
		if(is_array($data))
			foreach($data as $var => $val){
				if(!isset($_POST[$val]) || empty(trim($_POST[$val])))	die($var. " is missing");
			}
		else
			if(!isset($_POST[$data]) || empty(trim($_POST[$data])))	 die($data." is missing");
	}

	function show_error($error){
		die(json_encode(array("status" => "error", "message" => $error)));
	}

	function show_success($message){
		die(json_encode(array("status" => "success", "message" => $message)));
	}

	function show_message($status, $message){
		die(json_encode(array("status" => $status, "message" => $message)));
	}

	function is_logged_in(){
		return isset($_SESSION['logged_in'])? $_SESSION['logged_in_role'] : false;
	}

	function show_error_string($msg){
		die("Error: ".$msg);
	}
 ?>

