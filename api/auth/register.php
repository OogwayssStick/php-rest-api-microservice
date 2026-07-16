<?php

header("Content-Type: application/json");

require_once("../../config/database.php");

$data = json_decode(file_get_contents("php://input"), true);

$username = trim($data["username"] ?? "");
$password = trim($data["password"] ?? "");

if (empty($username) || empty($password)) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Tüm alanlar zorunludur"
    ]);

    exit;
}

$checkSql = "SELECT id FROM users WHERE username = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("s", $username);
$checkStmt->execute();

if ($checkStmt->get_result()->num_rows > 0) {

    echo json_encode([
        "success" => false,
        "message" => "Bu kullanıcı zaten mevcut"
    ]);

    exit;
}

$sql = "INSERT INTO users(username,password,token,role)
VALUES(?,?,?,'user')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Kullanıcı oluşturuldu"
    ]);
} else {

    echo json_encode([
        "success" => false,
        "message" => "Kayıt başarısız"
    ]);
}

$stmt->close();
$conn->close();
