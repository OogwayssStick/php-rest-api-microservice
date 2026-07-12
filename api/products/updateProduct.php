<?php




header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");
require_once("../../config/logger.php");
checkToken();

$data = json_decode(file_get_contents("php://input"), true);

$id = (int)($data["id"] ?? 0);
$productName = trim($data["product_name"] ?? "");
$description = trim($data["description"] ?? "");
$price = (float)($data["price"] ?? 0);
$discount = $data["discount"] ?? "Yok";
$saleStatus = $data["sale_status"] ?? "Satışta";

if (
    $id <= 0 ||
    empty($productName) ||
    empty($description) ||
    $price <= 0
) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Eksik bilgi."
    ]);

    exit;
}

$sql = "UPDATE products
SET
product_name=?,
description=?,
price=?,
discount=?,
sale_status=?
WHERE id=?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssdssi",
    $productName,
    $description,
    $price,
    $discount,
    $saleStatus,
    $id
);

if ($stmt->execute()) {
    writeLog(
        "PRODUCT_UPDATE",
        "ID: " . $id . " güncellendi."
    );
    echo json_encode([
        "success" => true,
        "message" => "Ürün güncellendi."
    ]);
} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Güncelleme başarısız."
    ]);
}

$stmt->close();
$conn->close();
