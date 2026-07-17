<?php

$host = "db";
$username = "admin";
$password = "123456";
$database = "microservice_db";

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
