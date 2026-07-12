-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 12 Tem 2026, 03:00:22
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
--
-- Veritabanı: `microservice_db`
--

-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10, 2) NOT NULL,
  `discount` enum('Var', 'Yok') DEFAULT 'Yok',
  `sale_status` enum('Satışta', 'Satışta Değil') DEFAULT 'Satışta',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Tablo döküm verisi `products`
--

INSERT INTO `products` (
    `id`,
    `product_name`,
    `description`,
    `price`,
    `discount`,
    `sale_status`,
    `created_at`
  )
VALUES (
    1,
    'iPhone 16',
    '256 GB Mavi',
    80000.00,
    'Var',
    'Satışta',
    '2026-07-11 23:04:06'
  ),
  (
    5,
    'iPhone 11',
    '128 GB',
    30000.00,
    'Var',
    'Satışta Değil',
    '2026-07-12 00:41:20'
  ),
  (
    6,
    'ihpne 22',
    'mfawkf',
    323422.00,
    'Var',
    'Satışta Değil',
    '2026-07-12 00:45:25'
  );
-- --------------------------------------------------------
--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `token` varchar(255) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (
    `id`,
    `username`,
    `password`,
    `created_at`,
    `token`
  )
VALUES (
    1,
    'admin',
    '123456',
    '2026-07-11 21:06:34',
    NULL
  ),
  (
    2,
    'admin',
    '123456',
    '2026-07-11 21:15:21',
    NULL
  ),
  (
    3,
    'baran',
    '123456',
    '2026-07-11 22:01:33',
    NULL
  );
--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `products`
--
ALTER TABLE `products`
ADD PRIMARY KEY (`id`);
--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
ADD PRIMARY KEY (`id`);
--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `products`
--
ALTER TABLE `products`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 8;
--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 4;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;