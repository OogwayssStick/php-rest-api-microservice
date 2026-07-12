<?php

$host = "db";
$user = "admin";
$pass = "123456";
$db   = "microservice_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
