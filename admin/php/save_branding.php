<?php
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	if(isset($_POST)){
		$id = 1;
		$brand_name = $_POST['brand-name']     ?      sanitize_var($_POST['brand-name'])   :  null;
		$domain_name = $_POST['domain-name']     ?      sanitize_var($_POST['domain-name'])   :  null;
		$email = $_POST['email']     ?      sanitize_var($_POST['email'])   :  null;
		$phone = $_POST['phone']     ?      sanitize_var($_POST['phone'])   :  null;
		$address = $_POST['address']     ?      sanitize_var($_POST['address'])   :  null;
		$fb = $_POST['fb']     ?      sanitize_var($_POST['fb'])   :  null;
		$twitter = $_POST['twitter']     ?      sanitize_var($_POST['twitter'])   :  null;
		$about_us =  $_POST['about-us']     ?      $_POST['about-us']   :  null;
		$privacy_policy =  $_POST['privacy-policy']     ?      $_POST['privacy-policy']   :  null;
		$cookies_Policy =  $_POST['cookies_Policy']     ?      $_POST['cookies_Policy']   :  null;
		$terms_conditions =  $_POST['terms-conditions']     ?      $_POST['terms-conditions']   :  null;
		$logo = ($_FILES["logo"]["name"]) ? (file_get_contents($_FILES['logo']["tmp_name"])) : "";
		$logo = ($_FILES["logo"]["name"] && ($_FILES["logo"]["size"])/1024 <= 200) ? $logo : "";    //measuring logo size
		$table_existance = $db -> select_all("branding");
		if(is_array($table_existance) && count($table_existance) == 0){
			 echo $db -> insert_data("branding",compact("id", "brand_name", "domain_name", "email", "phone", "address", "fb", "twitter", "about_us", "privacy_policy", "cookies_Policy", "terms_conditions", "logo")) ? "success" : "failed";
		}
		else{
			$data = [];

			if($brand_name)	 $data["brand_name"] = $brand_name;

			if($domain_name)	 $data["domain_name"] = $domain_name;

			if($email)	 $data["email"] = $email;

			if($phone)	 $data["phone"] = $phone;

			if($address)	 $data["address"] = $address;

			if($fb)	 $data["fb"] = $fb;

			if($twitter)	 $data["twitter"] = $twitter;

			if($about_us)	 $data["about_us"] = $about_us;

			if($privacy_policy)	 $data["privacy_policy"] = $privacy_policy;

			if($cookies_Policy)	 $data["cookies_Policy"] = $cookies_Policy;

			if($terms_conditions)	 $data["terms_conditions"] = $terms_conditions;

			if($logo)	 $data["logo"] = $logo;

			echo $db -> update("branding", 1, $data) ? "success" : "failed";
		}
	}
 ?>