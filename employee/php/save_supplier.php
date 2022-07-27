<?php

require_once "../../common/db.php";
require_once "../../common/imp_functions.php";
header("Content-type: application/json");
if (!isset($_POST["supplier_name"]) || empty(trim($_POST["supplier_name"]))) {
    show_error("supplier's name is compulsory");
}

$supplier_name = isset($_POST['supplier_name']) ? sanitize_var($_POST['supplier_name']) : null;
$supplier_email = isset($_POST['supplier_email']) ? sanitize_var($_POST['supplier_email']) : null;
$supplier_contact = isset($_POST['supplier_contact']) ? sanitize_var($_POST['supplier_contact']) : null;
$supplier_address = isset($_POST['supplier_address']) ? sanitize_var($_POST['supplier_address']) : null;

$stmt = $pdo->prepare("INSERT INTO suppliers(name, email, contact, address, user_id, merchant_id) VALUE(?,?,?,?,?,?)");
if ($stmt->execute([$supplier_name, $supplier_email, $supplier_contact, $supplier_address, $_SESSION['logged_in_id'], $_SESSION['logged_in_merchant']])) {
    show_success("Supplier added successfully");
} else {
    show_error("Failed.. Something went wrong");
}
