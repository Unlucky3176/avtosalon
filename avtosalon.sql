-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Час створення: Гру 01 2024 р., 17:58
-- Версія сервера: 10.4.32-MariaDB
-- Версія PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `avtosalon`
--

-- --------------------------------------------------------

--
-- Структура таблиці `customer`
--

CREATE TABLE `customer` (
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `customer`
--

INSERT INTO `customer` (`customer_id`, `name`, `phone`, `email`) VALUES
(1, 'Tyler', '2885550153', 'paperstreet.soap.co@gmail.com'),
(2, 'Leon', '1234567890', 'leonkennedy@rpd.org'),
(3, 'Ravic', '0001945455', 'e.m.remarque@gmail.com'),
(4, 'John', '3847501926', 'john385@hotmail.com'),
(5, 'James', '8392745610', 'james717@gmail.com'),
(6, 'Simon', '5721903846', 'simon.riley.141@hotmail.com'),
(7, 'Paul', '6819047235', 'paul.walker@gmail.com'),
(8, 'Ryan', '4938751620', 'r.gosling@hotmail.com'),
(9, 'Tony', '2568194370', 'stark.industries@gmail.com'),
(10, 'Gregory', '7048612953', 'gregory.house@gmail.com');

-- --------------------------------------------------------

--
-- Структура таблиці `sale`
--

CREATE TABLE `sale` (
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `sale_date` date NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `sale`
--

INSERT INTO `sale` (`sale_id`, `sale_date`, `customer_id`, `vehicle_id`, `amount`) VALUES
(1, '2024-06-10', 6, 1, 5500),
(2, '2024-08-28', 1, 2, 7000),
(3, '2024-07-26', 9, 10, 200000),
(4, '2024-08-03', 7, 7, 120000),
(5, '2024-06-30', 8, 3, 45000);

-- --------------------------------------------------------

--
-- Структура таблиці `service`
--

CREATE TABLE `service` (
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `service_date` date NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `service`
--

INSERT INTO `service` (`service_id`, `service_date`, `vehicle_id`) VALUES
(1, '2024-06-20', 1),
(2, '2024-10-18', 3);

-- --------------------------------------------------------

--
-- Структура таблиці `vehicle`
--

CREATE TABLE `vehicle` (
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `engine_type` varchar(50) NOT NULL,
  `engine_capacity` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `vehicle`
--

INSERT INTO `vehicle` (`vehicle_id`, `brand`, `model`, `engine_type`, `engine_capacity`) VALUES
(1, 'Yamaha', 'YZF-R6', 'Inline-4', 600),
(2, 'Suzuki', 'GSX-R 1000 K5', 'Inline-4', 1000),
(3, 'Ford', 'Mustang', 'V8', 5000),
(4, 'BMW', 'M3 GTR', 'V8', 4000),
(5, 'Kawasaki', 'Ninja ZX-10R', 'Inline-4', 1000),
(6, 'Ferarri', '458 Italia', 'V8', 4500),
(7, 'Nissan', 'GT-R R34 Skyline', 'Inline-6', 2600),
(8, 'McLaren', 'MP4-12C', 'V8', 3800),
(9, 'Subaru', 'Impreza WRX STI', 'Flat-4', 2500),
(10, 'Audi', 'R8', 'V8', 5200);

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `customer`
--
ALTER TABLE `customer`
  ADD UNIQUE KEY `customer_id` (`customer_id`);

--
-- Індекси таблиці `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`sale_id`),
  ADD UNIQUE KEY `sale_id` (`sale_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `brand_id` (`vehicle_id`);

--
-- Індекси таблиці `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`service_id`),
  ADD UNIQUE KEY `service_id` (`service_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Індекси таблиці `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблиці `sale`
--
ALTER TABLE `sale`
  MODIFY `sale_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблиці `service`
--
ALTER TABLE `service`
  MODIFY `service_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблиці `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `vehicle_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `sale`
--
ALTER TABLE `sale`
  ADD CONSTRAINT `sale_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`vehicle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`vehicle_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
