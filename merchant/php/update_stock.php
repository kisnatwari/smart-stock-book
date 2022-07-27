<?php
require_once("../../common/db.php");
require_once("../../common/imp_functions.php");
header("Content-type: application/json");
$product_name = (!empty($_POST["product-name"]) && is_numeric((int) $_POST["product-name"]))  ?   (int) sanitize_var($_POST["product-name"])   :  show_error("There's some problem with Product Name");
$qty = (!empty($_POST["qty"]) && is_numeric((int) $_POST["qty"]))  ?   (int) sanitize_var($_POST["qty"])   :  0;
if($qty == 0) show_error("Invalid stock number...");
$unit_price = (!empty($_POST["unit-price"]) && is_numeric((int) $_POST["unit-price"]))  ?   (int) sanitize_var($_POST["unit-price"])   :  show_error("Invalid unit price");
$total_price = $qty * $unit_price;
$paid_price = (!empty($_POST["paid-price"]) && is_numeric((int) $_POST["paid-price"]))  ?   (int) sanitize_var($_POST["paid-price"])   :  0;
$supplier_name = (!empty($_POST["supplier-name"]) && is_numeric((int) $_POST["supplier-name"]))  ?   (int) sanitize_var($_POST["supplier-name"])   :  0;
$marked_price = (!empty($_POST["marked-price"]) && is_numeric((int) $_POST["marked-price"]))  ?   (int) sanitize_var($_POST["marked-price"])   :  show_error("Invalid marked price");
$remarks = !empty($_POST["remarks"])  ?   sanitize_var($_POST["remarks"])   :  "";



if($total_price < $paid_price)
     show_error("Failed.. Paid more than the total price??");

if($unit_price > $marked_price)
     show_error("marked Price cannot be less then unit price");

$merchant_id = $_SESSION["logged_in_merchant"];
$set_supplier = ($supplier_name > 0) ? "SET @supplier = ?;" : "";
$placeholder = [$unit_price, $total_price, $paid_price, $qty, $remarks, $unit_price, $marked_price, $product_name, $merchant_id];
if($supplier_name > 0)
     array_unshift($placeholder, $supplier_name);

$stmt = $pdo -> prepare("$set_supplier SET @unit_price = ?; SET @total_price = ?; SET @paid_price = ?; SET @stock = ?; SET @remarks = ?; SET @update_on = 'purchase'; UPDATE `products` SET `unit_price` = ? , `marked_price` = ?, `available_stock` = `available_stock`+$qty  WHERE id = ? AND merchant_id = ?");
if($stmt -> execute($placeholder))
     show_success("Data updated successfully");

?>