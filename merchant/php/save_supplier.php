<?php

require_once "../../common/db.php";
require_once "../../common/imp_functions.php";
header("Content-type: application/json");
if (!isset($_POST["supplier_name"]) || empty(trim($_POST["supplier_name"]))) {
    show_error("supplier's name is compulsory");
}




$supplier_name = !empty(sanitize_var($_POST['supplier_name'])) ? sanitize_var($_POST['supplier_name']) : null;
$supplier_email = !empty(sanitize_var($_POST['supplier_email'])) ? sanitize_var($_POST['supplier_email']) : null;
$supplier_contact = !empty(sanitize_var($_POST['supplier_contact'])) ? sanitize_var($_POST['supplier_contact']) : null;
$supplier_address = !empty(sanitize_var($_POST['supplier_address'])) ? sanitize_var($_POST['supplier_address']) : null;

    //validating name
    if(!preg_match("/^[a-zA-Z\s]+$/",$supplier_name))
       show_error("Invalid name");

    if($supplier_email != null){
        //checking if email is valid
        if(!filter_var($supplier_email, FILTER_VALIDATE_EMAIL))     show_error("Invalid email");
        $check_email = $pdo -> prepare("SELECT * FROM suppliers WHERE merchant_id = ? AND email = ?");
        $check_email -> execute([$_SESSION["logged_in_merchant"], $supplier_email]);
        if($check_email -> rowCount() > 0)
            show_error("Another supplier with the provided email address already exists in your supplier's list");
    }

    //validating phone number
    if($supplier_contact != null){
        if(!preg_match("/^\+?[ -]?(?:(?:(?:98|97)-?\d{8})|(?:01-?\d{7}))$/", $supplier_contact))
            show_error("Unsupported contact number of supplier..");
        $check_contact = $pdo -> prepare("SELECT * FROM suppliers WHERE merchant_id = ? AND contact = ?");
        $check_contact -> execute([$_SESSION["logged_in_merchant"], $supplier_contact]);
        if($check_contact -> rowCount() > 0)
            show_error("Another supplier with the provided contact number already exists in your supplier's list. Please try changing the contact number");
    }

    if($supplier_address != null){
        if(!preg_match("/^[a-zA-Z0-9\s,.+-]+$/",$supplier_address)){
            for($i=0; $i<4; $i++){
                show_error("Invalid characters in address");
            }
        }
    }
    

    if(empty($supplier_contact) && empty($supplier_email))
        show_error("At least email or contact number is required to save a supplier");


$stmt = $pdo->prepare("INSERT INTO suppliers(name, email, contact, address, user_id, merchant_id) VALUE(?,?,?,?,?,?)");
if ($stmt->execute([$supplier_name, $supplier_email, $supplier_contact, $supplier_address, $_SESSION['logged_in_id'], $_SESSION['logged_in_merchant']])) 
    show_success("Supplier added successfully");
 else 
    show_error("Failed.. Something went wrong");

