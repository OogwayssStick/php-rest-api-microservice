<?php



require_once(__DIR__ . "/../config/database.php");

function checkToken()
{
    $headers = getallheaders();

    $token = $headers["Authorization"] ?? "";

    if (empty($token)) {

        http_response_code(401);

        echo json_encode([
            "success" => false,
            "message" => "Token gönderilmedi."
        ]);

        exit;
    }

    global $conn;

    $sql = "SELECT * FROM users WHERE token = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $token);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {

        http_response_code(401);

        echo json_encode([
            "success" => false,
            "message" => "Geçersiz token."
        ]);

        exit;
    }

    return $result->fetch_assoc();
}
