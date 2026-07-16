<?php

header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");
require_once("../../config/logger.php");

$user = checkToken();

if ($user["role"] != "admin") {

    http_response_code(403);

    echo json_encode([
        "success" => false,
        "message" => "Bu işlem için admin olmalısınız."
    ]);

    exit;
}

$id = (int)($_POST["id"] ?? 0);
$productName = trim($_POST["product_name"] ?? "");
$description = trim($_POST["description"] ?? "");
$price = (float)($_POST["price"] ?? 0);
$discount = trim($_POST["discount"] ?? "Yok");
$saleStatus = trim($_POST["sale_status"] ?? "Satışta");

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

/*
|----------------------------------------
| Eski resmi al
|----------------------------------------
*/

$stmt = $conn->prepare("SELECT image FROM products WHERE id=?");

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

$product = $result->fetch_assoc();

$image = $product["image"];

$stmt->close();

/*
|----------------------------------------
| Yeni resim yüklendiyse
|----------------------------------------
*/

if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

    $allowed = ["jpg", "jpeg", "png"];

    $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {

        echo json_encode([
            "success" => false,
            "message" => "Sadece JPG, JPEG ve PNG yüklenebilir."
        ]);

        exit;
    }

    // Eski resmi sil
    if (
        !empty($image) &&
        $image != "uploads/products/default.png" &&
        file_exists("../../" . $image)
    ) {

        unlink("../../" . $image);
    }

    $fileName = uniqid("product_") . "." . $ext;

    move_uploaded_file(
        $_FILES["image"]["tmp_name"],
        "../../uploads/products/" . $fileName
    );

    $image = "uploads/products/" . $fileName;
}

/*
|----------------------------------------
| Güncelle
|----------------------------------------
*/

$sql = "UPDATE products SET

product_name=?,
description=?,
price=?,
discount=?,
sale_status=?,
image=?

WHERE id=?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(

    "ssdsssi",

    $productName,
    $description,
    $price,
    $discount,
    $saleStatus,
    $image,
    $id

);

if ($stmt->execute()) {

    writeLog(
        "PRODUCT_UPDATE",
        "ID: " . $id . " güncellendi."
    );

    echo json_encode([
        "success" => true,
        "message" => "Ürün başarıyla güncellendi."
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
