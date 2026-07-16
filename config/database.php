<?php

$host = "localhost";
$user = "projectuser";
$password = "123456";
$database = "mircroservice_db"; // Senin oluşturduğun isim

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
