<?php

header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");

checkToken();

$sale = $conn->query("SELECT COUNT(*) total FROM products WHERE sale_status='Satışta'")
    ->fetch_assoc()["total"];

$notSale = $conn->query("SELECT COUNT(*) total FROM products WHERE sale_status='Satışta Değil'")
    ->fetch_assoc()["total"];

echo json_encode([
    "success" => true,
    "sale" => (int)$sale,
    "notSale" => (int)$notSale
]);

$conn->close();
