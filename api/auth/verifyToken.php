<?php

header("Content-Type: application/json");
require_once("../../config/database.php");

$headers = getallheaders();

$token = $headers["Authorization"] ?? "";

if (empty($token)) {
    echo json_encode([
        "success" => false,
        "message" => "Token gönderilmedi"
    ]);
    exit;
}

$sql = "SELECT * FROM users WHERE token = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    echo json_encode([
        "success" => true,
        "message" => "Token geçerli",
        "username" => $user["username"]
    ]);
} else {

    echo json_encode([
        "success" => false,
        "message" => "Token geçersiz"
    ]);
}
