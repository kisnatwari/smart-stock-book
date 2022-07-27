<?php 
header("Content-type: application/json");
	require_once "../common/db.php";
	require_once "../common/imp_functions.php";
	$branding = $db -> select_all("branding")[0];
		if(!preg_match("/^[a-zA-Z\s]+$/",$_POST["name"]))
	   show_error("Invalid Name");

	 	//checking if email is valid
	if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))		show_error("Invalid email");

	if(strlen($_POST['message']) < 10)
		show_error("Too short message");
	
	$header = "MIME-Version:1.0\r\nContent-Type: text/html;charset=ISO-8859-1\r\n";

	$message = "
	<h5> New User Message To Admin<h5>
	Sender Details : <br>
	Name = " . $_POST['name']. " <br>" .
	"Email = " . $_POST["email"].	" <br>" .
	"	<br>Message: <br>".
	"<p>".htmlspecialchars($_POST['message'])."</p>";
	
	if(mail($branding["email"], "New User Message", $message,  $header))
		show_success("Sent");
	else
		show_success("Problem on Internet Speed");
 ?>