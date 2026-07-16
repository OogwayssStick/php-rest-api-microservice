<?php

header("Content-Type: application/json");

require_once("../../config/database.php");
require_once("../../middleware/authMiddleware.php");

$user = checkToken();

echo json_encode([

    "success" => true,

    "id" => $user["id"],

    "username" => $user["username"]

]);

$conn->close();
