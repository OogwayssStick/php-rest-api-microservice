<?php
header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");
$user = checkToken();

if ($user["role"] != "admin") {

    http_response_code(403);

    echo json_encode([
        "success" => false,
        "message" => "Bu işlem için admin yetkisi gereklidir."
    ]);

    exit;
}


// Kullanıcılar ve ürün sayıları
$sql = "
SELECT

u.id,

u.username,

u.role,

u.image,

COUNT(p.id) product_count

FROM users u

LEFT JOIN products p

ON u.id=p.user_id

GROUP BY
u.id,
u.username,
u.role,
u.image
";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode([
        "success" => false,
        "message" => $conn->error
    ]);
    exit;
}

$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode([
    "success" => true,
    "users" => $users
]);

$conn->close();
