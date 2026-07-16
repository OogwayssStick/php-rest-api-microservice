<?php

header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");

$user = checkToken();
$userId = $user["id"];

$stmt = $conn->prepare("
SELECT id, product_name, price
FROM products
WHERE user_id = ?
ORDER BY id DESC
LIMIT 5
");

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode([
    "success" => true,
    "products" => $products
]);

$stmt->close();
$conn->close();
