<?php

header("Content-Type: application/json");

require_once("../../middleware/authMiddleware.php");
require_once("../../config/logger.php");
$user = checkToken();

$data = json_decode(file_get_contents("php://input"), true);

$productName = trim($data["product_name"] ?? "");
$description = trim($data["description"] ?? "");
$price = $data["price"] ?? 0;
$discount = $data["discount"] ?? "Yok";
$saleStatus = $data["sale_status"] ?? "Satışta";


// --------- BURASI ADIM 3 ---------

if (
    empty($productName) ||
    empty($description) ||
    $price <= 0
) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Eksik veya hatalı bilgi."
    ]);

    exit;
}


// --------- BURASI ADIM 4 ---------

$sql = "INSERT INTO products
(product_name, description, price, discount, sale_status)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssdss",
    $productName,
    $description,
    $price,
    $discount,
    $saleStatus
);

if ($stmt->execute()) {
    writeLog(
        "PRODUCT_ADD",
        $productName . " eklendi."
    );
    echo json_encode([
        "success" => true,
        "message" => "Ürün başarıyla eklendi."
    ]);
} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Ürün eklenemedi."
    ]);
}

$stmt->close();
$conn->close();
