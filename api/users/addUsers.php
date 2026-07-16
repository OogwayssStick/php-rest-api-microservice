<?php

header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");

$user = checkToken();

if ($user["role"] != "admin") {

    echo json_encode([
        "success" => false,
        "message" => "Yetkisiz işlem."
    ]);

    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$username = trim($data["username"]);
$password = trim($data["password"]);
$role = trim($data["role"]);

if (
    empty($username) ||
    empty($password)
) {

    echo json_encode([
        "success" => false,
        "message" => "Boş alan bırakılamaz."
    ]);

    exit;
}

$stmt = $conn->prepare("
INSERT INTO users
(username,password,role)
VALUES(?,?,?)
");

$stmt->bind_param(
    "sss",
    $username,
    $password,
    $role
);

$stmt->execute();

echo json_encode([

    "success" => true,

    "message" => "Kullanıcı eklendi."

]);

$stmt->close();

$conn->close();
