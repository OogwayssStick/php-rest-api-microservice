<?php

require_once("../config/database.php");

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

header("Content-Type: application/json");
echo json_encode($users);
