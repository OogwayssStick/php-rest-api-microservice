<?php
header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");
require_once("../../config/logger.php");

$user = checkToken();
$productName = trim($_POST["product_name"] ?? "");
$description = trim($_POST["description"] ?? "");
$price = (float)($_POST["price"] ?? 0);
$discount = trim($_POST["discount"] ?? "Yok");
$saleStatus = trim($_POST["sale_status"] ?? "Satışta");

// Kontroller
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

// Varsayılan resim
$image = "uploads/products/default.png";

// Resim yüklendiyse
if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

    $allowed = ["jpg", "jpeg", "png"];

    $extension = strtolower(
        pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION)
    );

    if (!in_array($extension, $allowed)) {

        http_response_code(400);

        echo json_encode([
            "success" => false,
            "message" => "Sadece JPG, JPEG ve PNG yükleyebilirsiniz."
        ]);

        exit;
    }

    // uploads/products klasörü yoksa oluştur
    if (!is_dir("../../uploads/products")) {

        mkdir("../../uploads/products", 0777, true);
    }

    $fileName = uniqid("product_") . "." . $extension;

    $target = "../../uploads/products/" . $fileName;

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target)) {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Resim yüklenemedi."
        ]);

        exit;
    }

    $image = "uploads/products/" . $fileName;
}

// Kullanıcı ID
$userId = $user["id"];

// Veritabanına ekle
$sql = "INSERT INTO products
(
    user_id,
    product_name,
    description,
    price,
    discount,
    sale_status,
    image
)
VALUES
(
    ?,?,?,?,?,?,?
)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "issdsss",
    $userId,
    $productName,
    $description,
    $price,
    $discount,
    $saleStatus,
    $image
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
        "message" => "Veritabanına kayıt yapılamadı."
    ]);
}

$stmt->close();
$conn->close();
