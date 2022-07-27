<?php
require_once "../common/imp_functions.php";
session_start();
session_destroy();
header("Location: $root");
?>