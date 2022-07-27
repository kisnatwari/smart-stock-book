<?php 
	require_once "../../common/db.php";
	require_once "../../common/imp_functions.php";
	header("Content-type: application/json");
	if(empty($_FILES['logo']["name"]))
		show_error($_FILES);
	$file_type = explode("/",$_FILES['logo']["type"])[0];
	if($file_type != "image")
		show_error("Please upload only image file");

	$file_extension = pathinfo($_FILES['logo']["name"])["extension"];
	$file_name = rand(1,999999)."_logo_".rand(1,999999).".".$file_extension;
	if(move_uploaded_file($_FILES['logo']["tmp_name"], "../logos/".$file_name)){

		// deleting previous logo
		$stmt = $pdo -> prepare("SELECT * FROM merchants WHERE id = ?");
		$stmt -> execute([$_SESSION['logged_in_merchant']]);
		$logo = $stmt -> fetch()["logo"];
		if(!empty(trim($logo)))
			unlink("../logos/".$logo);
		//previous logo deleted

		$stmt = $pdo -> prepare('UPDATE merchants set logo = ? WHERE id = ?');
		if($stmt -> execute([$file_name, $_SESSION["logged_in_merchant"]]))
			show_success("Logo uploaded successfully. Changes will be seen on page reload");
		else
			show_error("Failed to upload logo. Something went wrong");
	}
 ?>