<?php 
	require_once '../common/db.php';
	session_start();
	$branding = $db -> select_all("branding")[0];
	if($branding){
		$_SESSION['brand_name'] = $branding["brand_name"];
		$_SESSION['domain_name'] = $branding["domain_name"];
	}
 ?>