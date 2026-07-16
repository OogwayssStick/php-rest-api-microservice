<?php

header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");

checkToken();

/* Toplam ürün */

$productResult = $conn->query("SELECT COUNT(*) total FROM products");

$productCount = $productResult->fetch_assoc()["total"];

/* Toplam kullanıcı */

$userResult = $conn->query("SELECT COUNT(*) total FROM users");

$userCount = $userResult->fetch_assoc()["total"];

/* İndirimli */

$discountResult = $conn->query("SELECT COUNT(*) total
FROM products
WHERE discount='Var'");

$discountCount = $discountResult->fetch_assoc()["total"];

/* Satışta */

$saleResult = $conn->query("SELECT COUNT(*) total
FROM products
WHERE sale_status='Satışta'");

$saleCount = $saleResult->fetch_assoc()["total"];

echo json_encode([

    "success" => true,

    "products" => $productCount,

    "users" => $userCount,

    "discount" => $discountCount,

    "active" => $saleCount

]);

$conn->close();
