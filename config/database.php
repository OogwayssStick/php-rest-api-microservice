<?php

$host = getenv("DB_HOST") ?: "db";
$username = getenv("DB_USER") ?: "admin";
$password = getenv("DB_PASSWORD") ?: "";
$database = getenv("DB_NAME") ?: "microservice_db";

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);

if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
