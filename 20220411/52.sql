-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2022-04-11 10:18:02
-- 伺服器版本： 10.4.22-MariaDB
-- PHP 版本： 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫: `52`
--

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `account` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(50) NOT NULL,
  `groups` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`id`, `account`, `password`, `groups`) VALUES
(1, 'admin', '1234', 'admin'),
(2, 'abcd', '1234', 'user'),
(5, 'user', '1234', 'user');

-- --------------------------------------------------------

--
-- 資料表結構 `userlog`
--

CREATE TABLE `userlog` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `userlog`
--

INSERT INTO `userlog` (`id`, `userid`, `action`, `time`) VALUES
(1, 1, '密碼有誤', '2022-04-11 11:04:11'),
(2, 1, '圖形驗證碼有誤', '2022-04-11 11:04:14'),
(3, 1, '圖形驗證碼有誤', '2022-04-11 11:04:25'),
(4, 1, '圖形驗證碼有誤', '2022-04-11 11:04:26'),
(5, 1, '圖形驗證碼有誤', '2022-04-11 11:04:26'),
(6, 1, '登入成功', '2022-04-11 11:06:42'),
(7, 1, '登入成功', '2022-04-11 11:35:10'),
(8, 1, '登出成功', '2022-04-11 11:42:04'),
(9, 2, '登入成功', '2022-04-11 11:42:29'),
(10, 2, '登出成功', '2022-04-11 13:10:10'),
(11, 2, '登入成功', '2022-04-11 13:17:13'),
(12, 2, '登出成功', '2022-04-11 13:17:27'),
(13, 1, '密碼有誤', '2022-04-11 13:17:52'),
(14, 1, '圖形驗證碼有誤', '2022-04-11 13:17:59'),
(15, 1, '登入成功', '2022-04-11 13:18:07'),
(16, 1, '登出成功', '2022-04-11 13:18:15'),
(17, 1, '登入成功', '2022-04-11 13:18:50'),
(18, 1, '登出成功', '2022-04-11 13:19:53'),
(19, 2, '登入成功', '2022-04-11 13:20:00'),
(20, 2, '登出成功', '2022-04-11 14:41:42'),
(21, 1, '密碼有誤', '2022-04-11 14:45:49'),
(22, 1, '圖形驗證碼有誤', '2022-04-11 14:45:58'),
(23, 1, '登入成功', '2022-04-11 14:46:07'),
(24, 1, '登出成功', '2022-04-11 14:46:32'),
(25, 1, '登入成功', '2022-04-11 14:47:50'),
(26, 2, '登入成功', '2022-04-11 14:50:25'),
(27, 2, '登出成功', '2022-04-11 14:50:28'),
(28, 2, '登入成功', '2022-04-11 14:50:42'),
(29, 2, '登出成功', '2022-04-11 14:51:45'),
(30, 5, '登入成功', '2022-04-11 14:52:32'),
(31, 5, '登出成功', '2022-04-11 15:18:34'),
(32, 5, '登入成功', '2022-04-11 15:19:00'),
(33, 5, '登出成功', '2022-04-11 15:33:05'),
(34, 1, '登出成功', '2022-04-11 15:33:57'),
(35, 1, '登入成功', '2022-04-11 15:34:43'),
(36, 1, '登出成功', '2022-04-11 15:36:15'),
(37, 2, '登入成功', '2022-04-11 15:36:36'),
(38, 2, '登出成功', '2022-04-11 15:36:40'),
(39, 5, '登入成功', '2022-04-11 15:36:54'),
(40, 5, '登出成功', '2022-04-11 15:38:46'),
(41, 1, '登入成功', '2022-04-11 15:45:54'),
(42, 1, '登出成功', '2022-04-11 15:46:03'),
(43, 2, '登入成功', '2022-04-11 15:46:13'),
(44, 2, '登出成功', '2022-04-11 15:46:15'),
(45, 5, '登入成功', '2022-04-11 16:17:17');

-- --------------------------------------------------------

--
-- 資料表結構 `works`
--

CREATE TABLE `works` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `startTime` int(11) NOT NULL,
  `endTime` int(11) NOT NULL,
  `speed` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `works`
--

INSERT INTO `works` (`id`, `userid`, `name`, `date`, `startTime`, `endTime`, `speed`, `status`, `description`) VALUES
(1, 2, 'bla', '2022-04-11', 4, 5, 'b', 'c', 'blabla'),
(7, 5, '模擬賽', '2022-04-11', 6, 10, 'a', 'a', '51屆全國技能競賽'),
(10, 5, '12131', '2022-04-11', 18, 24, 'a', 'a', ''),
(11, 5, 'DASF', '2022-04-11', 9, 11, 'c', 'c', '');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- 資料表索引 `works`
--
ALTER TABLE `works`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `works`
--
ALTER TABLE `works`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `userlog`
--
ALTER TABLE `userlog`
  ADD CONSTRAINT `userlog_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的限制式 `works`
--
ALTER TABLE `works`
  ADD CONSTRAINT `works_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
