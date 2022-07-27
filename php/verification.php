<?php
include_once '../common/db.php';
include_once '../common/imp_functions.php';
header("Content-type: application/json");
$code = $_SESSION['code'];
$user_code = $_POST['code'];
if($code == $user_code){
	$stmt = $pdo -> prepare(" UPDATE `users` SET status = 'verified' WHERE email = ?");
	if($stmt -> execute([$_SESSION['email']])){
		show_success("<span class='text-2'><i class='fa fa-check'></i> &nbsp; Account verified successfully</span>");
	}
	else
		show_error("<span class='text-3'><i class='fa fa-close'></i> &nbsp; Account verification failed</span>");
}
else{
	echo show_error("unable to verify user... Wrong verification code ");
}
?>