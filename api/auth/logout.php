<?php

header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../config/logger.php");
$headers = getallheaders();
$username = trim($data["username"] ?? "");
$token = $headers["Authorization"] ?? "";

if (empty($token)) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Token gönderilmedi"
    ]);

    exit;
}

$sql = "UPDATE users SET token = NULL WHERE token = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $token);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    writeLog(
        "LOGOUT",
        $username . " çıkış yaptı."
    );
    echo json_encode([
        "success" => true,
        "message" => "Çıkış başarılı"
    ]);
} else {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Geçersiz token"
    ]);
}

$stmt->close();
$conn->close();
