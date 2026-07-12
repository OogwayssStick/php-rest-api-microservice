<?php

header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");
require_once("../../config/logger.php");

checkToken();

$data = json_decode(file_get_contents("php://input"), true);

$id = (int)($data["id"] ?? 0);

if ($id <= 0) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Geçersiz ürün ID."
    ]);

    exit;
}

$sql = "DELETE FROM products WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    writeLog(
        "PRODUCT_DELETE",
        "ID: " . $id . " silindi."
    );
    echo json_encode([
        "success" => true,
        "message" => "Ürün silindi."
    ]);
} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Silme işlemi başarısız."
    ]);
}

$stmt->close();
$conn->close();
