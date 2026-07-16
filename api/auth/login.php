<?php

header('Content-Type: application/json; charset=utf-8');
header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../config/logger.php");
// JSON verisini al
$data = json_decode(file_get_contents("php://input"), true);

$username = trim($data["username"] ?? "");
$password = trim($data["password"] ?? "");

// Boş alan kontrolü
if (empty($username) || empty($password)) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Kullanıcı adı ve şifre gereklidir"
    ]);
    exit;
}

// Kullanıcıyı bul
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "SQL hazırlama hatası"
    ]);
    exit;
}

$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    // Şifre kontrolü
    if ($password === $user["password"]) {

        // Token oluştur
        $token = bin2hex(random_bytes(32));

        // Token'ı veritabanına kaydet
        $updateSql = "UPDATE users SET token = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);

        $updateStmt->bind_param(
            "si",
            $token,
            $user["id"]
        );

        $updateStmt->execute();

        $updateStmt->close();
        writeLog(
            "LOGIN",
            $username . " giriş yaptı."
        );
        echo json_encode([
            "success" => true,
            "token" => $token,
            "role" => $user["role"],
            "username" => $user["username"]
        ]);
    } else {

        http_response_code(401);

        echo json_encode([
            "success" => false,
            "message" => "Şifre hatalı"
        ]);
    }
} else {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Kullanıcı bulunamadı"
    ]);
}

$stmt->close();
$conn->close();
