<?php

header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");

checkToken();

// Sayfalama
$page = isset($_GET["page"]) ? max(1, (int)$_GET["page"]) : 1;
$limit = isset($_GET["limit"]) ? max(1, (int)$_GET["limit"]) : 5;

$offset = ($page - 1) * $limit;

// Toplam kayıt
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM products");
$total = (int)$totalResult->fetch_assoc()["total"];

// Ürünleri getir
$sql = "SELECT * FROM products
        ORDER BY id DESC
        LIMIT $offset, $limit";

$result = $conn->query($sql);

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode([
    "success" => true,
    "page" => $page,
    "limit" => $limit,
    "total" => $total,
    "products" => $products
]);

$conn->close();
