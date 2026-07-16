<?php

header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");

$user = checkToken();
$user = checkToken();

if ($user["role"] == "admin") {

    $sql = "SELECT * FROM products";
} else {

    $sql = "SELECT * FROM products WHERE user_id=?";
}
$userId = $user["id"];

// Sayfalama
$page = isset($_GET["page"]) ? max(1, (int)$_GET["page"]) : 1;
$limit = isset($_GET["limit"]) ? max(1, (int)$_GET["limit"]) : 5;

$offset = ($page - 1) * $limit;

// Toplam kayıt
$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM products
    WHERE user_id = ?
");

$stmt->bind_param("i", $userId);
$stmt->execute();

$total = (int)$stmt->get_result()->fetch_assoc()["total"];

// Ürünleri getir
$stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE user_id = ?
    ORDER BY id DESC
    LIMIT ?, ?
");

$stmt->bind_param("iii", $userId, $offset, $limit);

$stmt->execute();

$result = $stmt->get_result();

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

$stmt->close();
$conn->close();
