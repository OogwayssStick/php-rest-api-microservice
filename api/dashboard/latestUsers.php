<?php

header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");

checkToken();

$result = $conn->query("

SELECT id,username

FROM users

ORDER BY id DESC

LIMIT 5

");

$data = [];

while ($row = $result->fetch_assoc()) {

    $data[] = $row;
}

echo json_encode([

    "success" => true,

    "users" => $data

]);
