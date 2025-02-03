-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el9
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Фев 03 2025 г., 08:46
-- Версия сервера: 10.5.22-MariaDB
-- Версия PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `dev`
--

-- --------------------------------------------------------

--
-- Структура таблицы `possibility_boards`
--

CREATE TABLE `possibility_boards` (
  `id` bigint(10) NOT NULL,
  `customer_id` bigint(10) DEFAULT NULL,
  `board` text NOT NULL,
  `possibility` tinyint(1) NOT NULL DEFAULT 0,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Таблица плат которые мы можем или не можем делать';

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `possibility_boards`
--
ALTER TABLE `possibility_boards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `boards_uniq` (`board`) USING HASH;

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `possibility_boards`
--
ALTER TABLE `possibility_boards`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
