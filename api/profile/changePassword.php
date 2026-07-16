<?php

header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");

$user = checkToken();

$userId = $user["id"];

$data = json_decode(file_get_contents("php://input"), true);

$oldPassword = trim($data["oldPassword"] ?? "");
$newPassword = trim($data["newPassword"] ?? "");
$newPassword2 = trim($data["newPassword2"] ?? "");

if (
    empty($oldPassword) ||
    empty($newPassword) ||
    empty($newPassword2)
) {

    echo json_encode([
        "success" => false,
        "message" => "Tüm alanları doldurun."
    ]);
    exit;
}

if ($newPassword != $newPassword2) {

    echo json_encode([
        "success" => false,
        "message" => "Yeni şifreler uyuşmuyor."
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();

$userData = $stmt->get_result()->fetch_assoc();

if ($userData["password"] != $oldPassword) {

    echo json_encode([
        "success" => false,
        "message" => "Eski şifre yanlış."
    ]);
    exit;
}

$stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->bind_param("si", $newPassword, $userId);
$stmt->execute();

echo json_encode([
    "success" => true,
    "message" => "Şifre başarıyla güncellendi."
]);

$conn->close();
