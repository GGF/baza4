-- phpMyAdmin SQL Dump
-- version 3.4.0
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 20 2011 г., 14:53
-- Версия сервера: 5.1.56
-- Версия PHP: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `zaompp`
--

-- --------------------------------------------------------

--
-- Структура таблицы `rights`
--
-- Создание: Май 20 2011 г., 09:56
--

CREATE TABLE IF NOT EXISTS `rights` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `u_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `type_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `rtype_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `right` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_users` (`u_id`),
  KEY `fk_rtype` (`rtype_id`),
  KEY `fk_type` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Таблица прав доступа к управлению базой' AUTO_INCREMENT=6167 ;

--
-- СВЯЗИ ТАБЛИЦЫ `rights`:
--   `type_id`
--       `rtypes` -> `id`
--   `rtype_id`
--       `rrtypes` -> `id`
--   `u_id`
--       `users` -> `id`
--

--
-- Дамп данных таблицы `rights`
--

INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(555, 18, 1, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(557, 18, 3, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(558, 18, 4, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(559, 18, 5, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(560, 18, 6, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(561, 18, 7, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(562, 18, 8, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(563, 18, 17, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(564, 18, 19, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(565, 19, 1, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(567, 19, 3, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(568, 19, 4, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(569, 19, 5, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(570, 19, 6, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(571, 19, 7, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(572, 19, 8, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(573, 19, 17, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(574, 19, 19, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(690, 20, 23, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(691, 20, 26, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(692, 20, 30, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5009, 14, 31, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5010, 14, 31, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5011, 14, 31, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5012, 14, 80, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5013, 14, 80, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5014, 14, 80, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5015, 14, 35, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5016, 14, 35, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5017, 14, 35, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5018, 14, 24, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5019, 14, 24, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5020, 14, 24, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5021, 14, 20, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5022, 14, 20, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5023, 14, 20, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5024, 14, 22, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5025, 14, 22, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5026, 14, 22, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5027, 14, 25, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5028, 14, 25, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5029, 14, 25, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5030, 14, 27, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5031, 14, 27, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5032, 14, 27, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5033, 14, 21, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5034, 14, 21, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5035, 14, 21, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5036, 14, 32, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5037, 14, 32, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5038, 14, 32, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5039, 14, 26, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5040, 14, 26, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5041, 14, 26, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5042, 14, 30, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5043, 14, 30, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5044, 14, 30, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5045, 14, 29, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5046, 14, 29, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5047, 14, 29, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5048, 14, 83, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5049, 14, 83, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5050, 14, 83, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5051, 14, 85, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5052, 14, 85, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5053, 14, 85, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5054, 14, 87, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5055, 14, 87, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5056, 14, 87, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5057, 14, 86, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5058, 14, 86, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5059, 14, 86, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5060, 14, 84, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5061, 14, 84, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5062, 14, 84, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5063, 14, 28, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5064, 14, 28, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5065, 14, 28, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5066, 14, 23, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5067, 14, 23, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5068, 14, 23, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5069, 14, 33, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5070, 14, 33, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5071, 14, 33, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5072, 14, 34, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5073, 14, 34, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5074, 14, 34, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5185, 15, 77, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5186, 15, 77, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5187, 15, 77, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5188, 15, 78, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5189, 15, 80, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5190, 15, 80, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5191, 15, 80, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5192, 15, 19, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5193, 15, 44, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5194, 15, 67, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5195, 15, 68, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5196, 15, 65, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5197, 15, 81, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5198, 15, 72, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5199, 15, 72, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5200, 15, 72, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5201, 15, 66, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5202, 15, 5, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5203, 15, 73, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5204, 15, 74, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5205, 15, 82, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5206, 15, 76, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5207, 15, 83, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5208, 15, 85, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5209, 15, 87, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5210, 15, 86, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5211, 15, 84, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5212, 15, 6, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5213, 15, 6, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5214, 15, 17, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5215, 15, 17, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5216, 15, 17, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5217, 3, 77, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5218, 3, 19, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5219, 3, 19, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5220, 3, 19, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5221, 3, 67, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5222, 3, 67, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5223, 3, 67, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5224, 3, 68, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5225, 3, 68, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5226, 3, 68, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5227, 3, 65, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5228, 3, 65, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5229, 3, 65, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5230, 3, 81, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5231, 3, 81, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5232, 3, 81, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5233, 3, 72, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5234, 3, 66, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5235, 3, 66, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5236, 3, 66, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5237, 3, 7, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5238, 3, 5, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5239, 3, 6, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5446, 17, 77, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5447, 17, 72, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5448, 17, 72, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5449, 17, 72, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5450, 17, 17, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5451, 17, 17, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5452, 17, 17, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5550, 1, 1, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5551, 1, 1, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5552, 1, 1, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5553, 1, 3, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5554, 1, 3, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5555, 1, 3, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5556, 1, 4, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5557, 1, 4, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5558, 1, 4, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5559, 1, 5, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5560, 1, 5, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5561, 1, 5, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5562, 1, 6, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5563, 1, 6, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5564, 1, 6, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5565, 1, 7, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5566, 1, 7, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5567, 1, 7, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5568, 1, 8, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5569, 1, 8, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5570, 1, 8, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5571, 1, 9, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5572, 1, 9, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5573, 1, 9, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5574, 1, 10, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5575, 1, 10, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5576, 1, 10, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5577, 1, 16, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5578, 1, 16, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5579, 1, 16, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5580, 1, 15, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5581, 1, 15, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5582, 1, 15, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5583, 1, 17, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5584, 1, 17, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5585, 1, 17, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5586, 1, 20, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5587, 1, 20, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5588, 1, 20, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5589, 1, 19, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5590, 1, 19, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5591, 1, 19, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5592, 1, 21, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5593, 1, 21, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5594, 1, 21, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5595, 1, 22, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5596, 1, 22, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5597, 1, 22, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5598, 1, 23, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5599, 1, 23, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5600, 1, 23, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5601, 1, 24, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5602, 1, 24, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5603, 1, 24, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5604, 1, 25, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5605, 1, 25, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5606, 1, 25, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5607, 1, 26, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5608, 1, 26, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5609, 1, 26, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5610, 1, 27, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5611, 1, 27, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5612, 1, 27, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5613, 1, 28, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5614, 1, 28, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5615, 1, 28, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5616, 1, 29, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5617, 1, 29, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5618, 1, 29, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5619, 1, 30, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5620, 1, 30, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5621, 1, 30, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5622, 1, 31, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5623, 1, 31, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5624, 1, 31, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5625, 1, 32, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5626, 1, 32, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5627, 1, 32, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5628, 1, 33, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5629, 1, 33, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5630, 1, 33, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5631, 1, 34, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5632, 1, 34, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5633, 1, 34, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5634, 1, 35, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5635, 1, 35, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5636, 1, 35, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5637, 1, 38, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5638, 1, 38, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5639, 1, 38, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5640, 1, 44, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5641, 1, 44, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5642, 1, 44, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5643, 1, 46, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5644, 1, 46, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5645, 1, 46, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5646, 1, 66, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5647, 1, 66, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5648, 1, 66, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5649, 1, 65, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5650, 1, 65, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5651, 1, 65, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5652, 1, 58, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5653, 1, 58, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5654, 1, 58, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5655, 1, 59, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5656, 1, 59, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5657, 1, 59, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5658, 1, 60, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5659, 1, 60, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5660, 1, 60, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5661, 1, 67, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5662, 1, 67, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5663, 1, 67, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5664, 1, 62, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5665, 1, 62, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5666, 1, 62, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5667, 1, 63, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5668, 1, 63, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5669, 1, 63, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5670, 1, 64, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5671, 1, 64, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5672, 1, 64, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5673, 1, 68, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5674, 1, 68, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5675, 1, 68, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5676, 1, 69, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5677, 1, 69, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5678, 1, 69, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5679, 1, 70, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5680, 1, 70, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5681, 1, 70, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5682, 1, 71, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5683, 1, 71, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5684, 1, 71, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5685, 1, 72, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5686, 1, 72, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5687, 1, 72, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5688, 1, 73, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5689, 1, 73, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5690, 1, 73, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5691, 1, 74, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5692, 1, 74, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5693, 1, 74, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5694, 1, 75, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5695, 1, 75, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5696, 1, 75, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5697, 1, 76, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5698, 1, 76, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5699, 1, 76, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5700, 1, 77, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5701, 1, 77, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5702, 1, 77, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5703, 1, 78, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5704, 1, 78, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5705, 1, 78, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5706, 1, 79, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5707, 1, 79, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5708, 1, 79, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5709, 1, 80, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5710, 1, 80, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5711, 1, 80, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5712, 1, 81, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5713, 1, 81, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5714, 1, 81, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5715, 1, 82, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5716, 1, 82, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5717, 1, 82, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5718, 1, 83, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5719, 1, 83, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5720, 1, 83, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5721, 1, 84, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5722, 1, 84, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5723, 1, 84, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5724, 1, 85, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5725, 1, 85, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5726, 1, 85, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5727, 1, 86, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5728, 1, 86, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5729, 1, 86, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5730, 1, 87, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5731, 1, 87, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5732, 1, 87, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5733, 1, 88, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5734, 1, 88, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5735, 1, 88, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5736, 1, 89, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5737, 1, 89, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5738, 1, 89, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5739, 2, 1, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5740, 2, 1, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5741, 2, 1, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5742, 2, 3, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5743, 2, 3, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5744, 2, 3, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5745, 2, 4, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5746, 2, 4, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5747, 2, 4, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5748, 2, 5, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5749, 2, 5, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5750, 2, 5, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5751, 2, 6, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5752, 2, 6, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5753, 2, 6, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5754, 2, 7, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5755, 2, 7, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5756, 2, 7, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5757, 2, 8, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5758, 2, 8, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5759, 2, 8, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5760, 2, 16, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5761, 2, 16, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5762, 2, 16, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5763, 2, 17, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5764, 2, 17, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5765, 2, 17, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5766, 2, 20, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5767, 2, 20, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5768, 2, 20, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5769, 2, 19, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5770, 2, 19, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5771, 2, 19, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5772, 2, 21, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5773, 2, 21, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5774, 2, 21, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5775, 2, 22, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5776, 2, 22, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5777, 2, 22, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5778, 2, 23, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5779, 2, 23, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5780, 2, 23, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5781, 2, 24, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5782, 2, 24, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5783, 2, 24, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5784, 2, 25, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5785, 2, 25, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5786, 2, 25, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5787, 2, 26, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5788, 2, 26, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5789, 2, 26, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5790, 2, 27, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5791, 2, 27, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5792, 2, 27, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5793, 2, 28, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5794, 2, 28, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5795, 2, 28, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5796, 2, 29, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5797, 2, 29, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5798, 2, 29, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5799, 2, 30, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5800, 2, 30, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5801, 2, 30, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5802, 2, 31, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5803, 2, 31, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5804, 2, 31, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5805, 2, 32, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5806, 2, 32, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5807, 2, 32, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5808, 2, 33, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5809, 2, 33, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5810, 2, 33, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5811, 2, 34, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5812, 2, 34, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5813, 2, 34, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5814, 2, 35, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5815, 2, 35, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5816, 2, 35, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5817, 2, 38, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5818, 2, 38, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5819, 2, 38, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5820, 2, 44, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5821, 2, 44, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5822, 2, 44, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5823, 2, 46, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5824, 2, 46, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5825, 2, 46, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5826, 2, 66, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5827, 2, 66, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5828, 2, 66, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5829, 2, 65, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5830, 2, 65, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5831, 2, 65, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5832, 2, 58, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5833, 2, 58, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5834, 2, 58, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5835, 2, 59, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5836, 2, 59, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5837, 2, 59, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5838, 2, 60, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5839, 2, 60, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5840, 2, 60, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5841, 2, 67, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5842, 2, 67, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5843, 2, 67, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5844, 2, 62, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5845, 2, 62, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5846, 2, 62, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5847, 2, 63, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5848, 2, 63, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5849, 2, 63, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5850, 2, 64, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5851, 2, 64, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5852, 2, 64, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5853, 2, 68, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5854, 2, 68, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5855, 2, 68, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5856, 2, 72, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5857, 2, 72, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5858, 2, 72, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5859, 2, 73, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5860, 2, 73, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5861, 2, 73, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5862, 2, 74, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5863, 2, 74, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5864, 2, 74, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5865, 2, 75, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5866, 2, 75, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5867, 2, 75, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5868, 2, 76, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5869, 2, 76, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5870, 2, 76, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5871, 2, 77, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5872, 2, 77, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5873, 2, 77, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5874, 2, 78, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5875, 2, 78, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5876, 2, 78, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5877, 2, 80, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5878, 2, 80, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5879, 2, 80, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5880, 2, 81, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5881, 2, 81, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5882, 2, 81, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5883, 2, 82, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5884, 2, 82, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5885, 2, 82, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5886, 2, 83, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5887, 2, 83, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5888, 2, 83, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5889, 2, 84, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5890, 2, 84, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5891, 2, 84, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5892, 2, 85, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5893, 2, 85, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5894, 2, 85, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5895, 2, 86, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5896, 2, 86, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5897, 2, 86, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5898, 2, 87, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5899, 2, 87, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5900, 2, 87, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5901, 2, 88, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5902, 2, 88, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5903, 2, 88, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5904, 2, 89, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5905, 2, 89, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5906, 2, 89, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5907, 21, 1, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5908, 21, 3, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5909, 21, 4, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5910, 21, 5, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5911, 21, 6, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5912, 21, 7, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5913, 21, 8, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5914, 21, 17, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5915, 21, 17, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5916, 21, 20, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5917, 21, 20, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5918, 21, 19, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5919, 21, 19, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5920, 21, 21, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5921, 21, 21, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5922, 21, 22, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5923, 21, 22, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5924, 21, 23, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5925, 21, 23, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5926, 21, 24, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5927, 21, 24, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5928, 21, 25, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5929, 21, 25, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5930, 21, 26, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5931, 21, 26, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5932, 21, 27, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5933, 21, 27, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5934, 21, 28, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5935, 21, 28, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5936, 21, 29, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5937, 21, 29, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5938, 21, 30, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5939, 21, 30, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5940, 21, 31, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5941, 21, 31, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5942, 21, 32, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5943, 21, 32, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5944, 21, 33, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5945, 21, 33, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5946, 21, 34, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5947, 21, 34, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5948, 21, 35, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5949, 21, 35, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5950, 21, 66, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5951, 21, 65, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5952, 21, 67, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5953, 21, 68, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5954, 21, 72, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5955, 21, 72, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5956, 21, 72, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5957, 21, 73, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5958, 21, 74, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5959, 21, 76, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5960, 21, 77, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5961, 21, 78, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5962, 21, 80, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5963, 21, 81, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5964, 21, 82, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5965, 21, 83, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5966, 21, 84, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5967, 21, 85, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5968, 21, 86, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5969, 21, 87, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5970, 21, 88, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5971, 21, 88, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5972, 21, 88, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5973, 21, 89, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5974, 21, 89, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5975, 21, 89, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5976, 13, 1, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5977, 13, 5, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5978, 13, 6, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5979, 13, 6, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5980, 13, 17, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5981, 13, 17, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5982, 13, 17, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5983, 13, 20, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5984, 13, 20, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5985, 13, 20, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5986, 13, 19, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5987, 13, 21, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5988, 13, 21, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5989, 13, 21, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5990, 13, 22, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5991, 13, 22, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5992, 13, 22, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5993, 13, 23, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5994, 13, 23, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5995, 13, 23, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5996, 13, 24, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5997, 13, 24, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5998, 13, 24, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(5999, 13, 25, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6000, 13, 25, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6001, 13, 25, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6002, 13, 26, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6003, 13, 26, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6004, 13, 26, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6005, 13, 27, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6006, 13, 27, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6007, 13, 27, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6008, 13, 28, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6009, 13, 28, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6010, 13, 28, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6011, 13, 29, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6012, 13, 29, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6013, 13, 29, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6014, 13, 30, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6015, 13, 30, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6016, 13, 30, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6017, 13, 31, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6018, 13, 31, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6019, 13, 31, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6020, 13, 32, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6021, 13, 32, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6022, 13, 32, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6023, 13, 33, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6024, 13, 33, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6025, 13, 33, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6026, 13, 34, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6027, 13, 34, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6028, 13, 34, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6029, 13, 35, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6030, 13, 35, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6031, 13, 35, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6032, 13, 66, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6033, 13, 65, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6034, 13, 67, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6035, 13, 68, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6036, 13, 72, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6037, 13, 72, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6038, 13, 72, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6039, 13, 77, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6040, 13, 78, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6041, 13, 80, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6042, 13, 81, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6043, 13, 83, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6044, 13, 83, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6045, 13, 83, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6046, 13, 84, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6047, 13, 84, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6048, 13, 84, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6049, 13, 85, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6050, 13, 85, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6051, 13, 85, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6052, 13, 86, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6053, 13, 86, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6054, 13, 86, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6055, 13, 87, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6056, 13, 87, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6057, 13, 87, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6058, 13, 88, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6059, 13, 88, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6060, 13, 88, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6061, 13, 89, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6062, 13, 89, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6063, 13, 89, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6064, 4, 1, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6065, 4, 1, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6066, 4, 1, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6067, 4, 3, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6068, 4, 3, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6069, 4, 3, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6070, 4, 4, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6071, 4, 4, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6072, 4, 4, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6073, 4, 5, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6074, 4, 5, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6075, 4, 5, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6076, 4, 6, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6077, 4, 6, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6078, 4, 6, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6079, 4, 7, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6080, 4, 7, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6081, 4, 7, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6082, 4, 8, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6083, 4, 8, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6084, 4, 8, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6085, 4, 9, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6086, 4, 9, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6087, 4, 9, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6088, 4, 10, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6089, 4, 10, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6090, 4, 10, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6091, 4, 16, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6092, 4, 16, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6093, 4, 16, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6094, 4, 15, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6095, 4, 15, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6096, 4, 15, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6097, 4, 17, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6098, 4, 17, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6099, 4, 17, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6100, 4, 19, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6101, 4, 19, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6102, 4, 19, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6103, 4, 66, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6104, 4, 66, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6105, 4, 66, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6106, 4, 65, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6107, 4, 65, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6108, 4, 65, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6109, 4, 67, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6110, 4, 67, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6111, 4, 67, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6112, 4, 68, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6113, 4, 68, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6114, 4, 68, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6115, 4, 69, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6116, 4, 69, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6117, 4, 69, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6118, 4, 70, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6119, 4, 70, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6120, 4, 70, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6121, 4, 71, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6122, 4, 71, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6123, 4, 71, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6124, 4, 72, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6125, 4, 72, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6126, 4, 72, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6127, 4, 73, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6128, 4, 73, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6129, 4, 73, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6130, 4, 74, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6131, 4, 74, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6132, 4, 74, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6133, 4, 76, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6134, 4, 76, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6135, 4, 76, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6136, 4, 77, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6137, 4, 78, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6138, 4, 79, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6139, 4, 80, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6140, 4, 81, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6141, 4, 81, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6142, 4, 81, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6143, 4, 82, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6144, 4, 82, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6145, 4, 82, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6146, 4, 83, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6147, 4, 83, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6148, 4, 83, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6149, 4, 84, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6150, 4, 84, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6151, 4, 84, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6152, 4, 85, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6153, 4, 85, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6154, 4, 85, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6155, 4, 86, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6156, 4, 86, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6157, 4, 86, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6158, 4, 87, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6159, 4, 87, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6160, 4, 87, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6161, 4, 88, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6162, 4, 88, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6163, 4, 88, 13, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6164, 4, 89, 15, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6165, 4, 89, 14, 1);
INSERT INTO `rights` (`id`, `u_id`, `type_id`, `rtype_id`, `right`) VALUES(6166, 4, 89, 13, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `rrtypes`
--
-- Создание: Май 20 2011 г., 09:25
--

CREATE TABLE IF NOT EXISTS `rrtypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rtype` varchar(50) NOT NULL DEFAULT '',
  `value` int(11) NOT NULL COMMENT 'Установленный бит',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Типы доступа' AUTO_INCREMENT=16 ;

--
-- Дамп данных таблицы `rrtypes`
--

INSERT INTO `rrtypes` (`id`, `rtype`, `value`) VALUES(13, 'del', 4);
INSERT INTO `rrtypes` (`id`, `rtype`, `value`) VALUES(14, 'edit', 2);
INSERT INTO `rrtypes` (`id`, `rtype`, `value`) VALUES(15, 'view', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `rtypes`
--
-- Создание: Май 20 2011 г., 09:25
--

CREATE TABLE IF NOT EXISTS `rtypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Типы для доступа' AUTO_INCREMENT=90 ;

--
-- Дамп данных таблицы `rtypes`
--

INSERT INTO `rtypes` (`id`, `type`) VALUES(31, 'arc');
INSERT INTO `rtypes` (`id`, `type`) VALUES(79, 'baza_cp');
INSERT INTO `rtypes` (`id`, `type`) VALUES(77, 'baza_lanch');
INSERT INTO `rtypes` (`id`, `type`) VALUES(78, 'baza_orders');
INSERT INTO `rtypes` (`id`, `type`) VALUES(80, 'baza_storages');
INSERT INTO `rtypes` (`id`, `type`) VALUES(63, 'blocks');
INSERT INTO `rtypes` (`id`, `type`) VALUES(64, 'boards');
INSERT INTO `rtypes` (`id`, `type`) VALUES(58, 'conduct');
INSERT INTO `rtypes` (`id`, `type`) VALUES(19, 'conductors');
INSERT INTO `rtypes` (`id`, `type`) VALUES(69, 'cp');
INSERT INTO `rtypes` (`id`, `type`) VALUES(71, 'cp_todo');
INSERT INTO `rtypes` (`id`, `type`) VALUES(70, 'cp_users');
INSERT INTO `rtypes` (`id`, `type`) VALUES(62, 'customer');
INSERT INTO `rtypes` (`id`, `type`) VALUES(1, 'customers');
INSERT INTO `rtypes` (`id`, `type`) VALUES(35, 'dvizh');
INSERT INTO `rtypes` (`id`, `type`) VALUES(24, 'halaty');
INSERT INTO `rtypes` (`id`, `type`) VALUES(20, 'himiya');
INSERT INTO `rtypes` (`id`, `type`) VALUES(22, 'himiya2');
INSERT INTO `rtypes` (`id`, `type`) VALUES(25, 'instr');
INSERT INTO `rtypes` (`id`, `type`) VALUES(44, 'lanch');
INSERT INTO `rtypes` (`id`, `type`) VALUES(67, 'lanch_conduct');
INSERT INTO `rtypes` (`id`, `type`) VALUES(68, 'lanch_mp');
INSERT INTO `rtypes` (`id`, `type`) VALUES(65, 'lanch_nzap');
INSERT INTO `rtypes` (`id`, `type`) VALUES(81, 'lanch_pt');
INSERT INTO `rtypes` (`id`, `type`) VALUES(72, 'lanch_zad');
INSERT INTO `rtypes` (`id`, `type`) VALUES(66, 'lanch_zap');
INSERT INTO `rtypes` (`id`, `type`) VALUES(9, 'logs');
INSERT INTO `rtypes` (`id`, `type`) VALUES(27, 'maloc');
INSERT INTO `rtypes` (`id`, `type`) VALUES(21, 'materials');
INSERT INTO `rtypes` (`id`, `type`) VALUES(32, 'movecheck');
INSERT INTO `rtypes` (`id`, `type`) VALUES(7, 'mp');
INSERT INTO `rtypes` (`id`, `type`) VALUES(26, 'nepon');
INSERT INTO `rtypes` (`id`, `type`) VALUES(5, 'nzap');
INSERT INTO `rtypes` (`id`, `type`) VALUES(75, 'order');
INSERT INTO `rtypes` (`id`, `type`) VALUES(38, 'orders');
INSERT INTO `rtypes` (`id`, `type`) VALUES(88, 'orders_blocks');
INSERT INTO `rtypes` (`id`, `type`) VALUES(89, 'orders_boards');
INSERT INTO `rtypes` (`id`, `type`) VALUES(73, 'orders_customers');
INSERT INTO `rtypes` (`id`, `type`) VALUES(74, 'orders_order');
INSERT INTO `rtypes` (`id`, `type`) VALUES(82, 'orders_posintz');
INSERT INTO `rtypes` (`id`, `type`) VALUES(76, 'orders_tz');
INSERT INTO `rtypes` (`id`, `type`) VALUES(30, 'ost');
INSERT INTO `rtypes` (`id`, `type`) VALUES(60, 'phototemplate');
INSERT INTO `rtypes` (`id`, `type`) VALUES(4, 'posintz');
INSERT INTO `rtypes` (`id`, `type`) VALUES(8, 'pt');
INSERT INTO `rtypes` (`id`, `type`) VALUES(15, 'rights');
INSERT INTO `rtypes` (`id`, `type`) VALUES(46, 'sklads');
INSERT INTO `rtypes` (`id`, `type`) VALUES(29, 'skzap');
INSERT INTO `rtypes` (`id`, `type`) VALUES(83, 'storages_storage');
INSERT INTO `rtypes` (`id`, `type`) VALUES(85, 'storage_archive');
INSERT INTO `rtypes` (`id`, `type`) VALUES(87, 'storage_archivemoves');
INSERT INTO `rtypes` (`id`, `type`) VALUES(86, 'storage_moves');
INSERT INTO `rtypes` (`id`, `type`) VALUES(84, 'storage_rest');
INSERT INTO `rtypes` (`id`, `type`) VALUES(28, 'stroy');
INSERT INTO `rtypes` (`id`, `type`) VALUES(23, 'sverla');
INSERT INTO `rtypes` (`id`, `type`) VALUES(16, 'todo');
INSERT INTO `rtypes` (`id`, `type`) VALUES(33, 'trebcheck');
INSERT INTO `rtypes` (`id`, `type`) VALUES(3, 'tz');
INSERT INTO `rtypes` (`id`, `type`) VALUES(10, 'users');
INSERT INTO `rtypes` (`id`, `type`) VALUES(34, 'year');
INSERT INTO `rtypes` (`id`, `type`) VALUES(59, 'zad');
INSERT INTO `rtypes` (`id`, `type`) VALUES(6, 'zap');
INSERT INTO `rtypes` (`id`, `type`) VALUES(17, 'zd');

-- --------------------------------------------------------

--
-- Структура таблицы `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `session` varchar(255) NOT NULL DEFAULT '',
  `u_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`session`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

--
-- СВЯЗИ ТАБЛИЦЫ `session`:
--   `u_id`
--       `users` -> `id`
--

--
-- Дамп данных таблицы `session`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--
-- Создание: Май 20 2011 г., 09:03
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(15) NOT NULL DEFAULT '',
  `fullname` varchar(50) NOT NULL DEFAULT '',
  `position` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nik` (`nik`),
  UNIQUE KEY `password` (`password`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Пользватели базы данных' AUTO_INCREMENT=22 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(1, 'igor', 'Игорь Юрьевич Федоров', 'Конструктор', '14874');
INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(2, 'victor', 'Виктор Анатольевич Смирнов', 'Главный конструктор', '210664');
INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(3, 'rytova', 'Ольга Алексеевна Рытова', 'Конструктор', '100551');
INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(4, 'andrew', 'Андрей Игоревич Жинкин', 'Программист', '301055');
INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(5, '', '', '', '12345678901234567890');
INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(12, 'GGF', 'Федоров Игорь Юрьевич', 'Конструкторр', '14081974');
INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(13, 'dima', 'Дмитрий Сергеевич Китуничев', 'Начальник производства МПП', '070470');
INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(14, 'valrom', 'Валентина Романова', 'Кладовщик', '280355');
INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(15, 'tanya', 'Татьяна Леонидовна Макарова', 'Начальник производства ДПП', '130476');
INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(16, 'rimma', 'Римма Владимировна Попова', 'Главный бухгалтер', 'rimma');
INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(17, 'anna', 'Анна Шкопорова', '', 'anna');
INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(18, 'sokolova', 'Соколова Валенитна Михайлова', 'Главны технолог', '010747');
INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(19, 'mars', 'Марина Антатольевна Слободенюк', 'Бухгалтер', '070155');
INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(20, 'stasv', 'Васильев СтанИслав', 'Сверловщик', '4004430');
INSERT INTO `users` (`id`, `nik`, `fullname`, `position`, `password`) VALUES(21, 'nina', 'Нина Ивановона Величко', 'Технолог', '280784');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `rights`
--
ALTER TABLE `rights`
  ADD CONSTRAINT `fk_type` FOREIGN KEY (`type_id`) REFERENCES `rtypes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rtype` FOREIGN KEY (`rtype_id`) REFERENCES `rrtypes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_users` FOREIGN KEY (`u_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
