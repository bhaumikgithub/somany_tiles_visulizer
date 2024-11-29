-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 06, 2024 at 02:58 PM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `a3droogy_test02`
--

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `email`, `address`, `phone`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Tile Visualizer', 'inbox@tiles.com', NULL, NULL, 'company/25uF57ZberMliz2uA6WXXmjSaG2J2wkSlcow1zfY.png', NULL, '2024-03-17 16:51:59');

-- --------------------------------------------------------

--
-- Table structure for table `custom_tiles`
--

CREATE TABLE `custom_tiles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `shape` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'square',
  `user_id` int(11) DEFAULT NULL,
  `session_token` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `custom_tiles`
--

INSERT INTO `custom_tiles` (`id`, `name`, `file`, `width`, `height`, `shape`, `user_id`, `session_token`, `settings`, `created_at`, `updated_at`) VALUES
(1, NULL, 'customtiles/5a3ec8cf7863d9.477283371.png', 400, 400, 'square', NULL, 'idroXBOCjFM4VtbFXBIn8BBPcYrDbi2AdjDolpbg', NULL, '2017-12-23 21:21:19', '2017-12-23 21:21:19'),
(2, NULL, 'customtiles/5a43de88b29841.599095951.png', 400, 400, 'square', NULL, 'MZ5cuoFOQo4zH1HjpavyFa7PFh1ZkpvVN6w6c32X', NULL, '2017-12-27 17:55:20', '2017-12-27 17:55:20'),
(3, NULL, 'customtiles/5a43df3f22a521.062202421.png', 400, 400, 'square', NULL, 'MZ5cuoFOQo4zH1HjpavyFa7PFh1ZkpvVN6w6c32X', NULL, '2017-12-27 17:58:23', '2017-12-27 17:58:23'),
(4, NULL, 'customtiles/5a440c642a6906.006865111.png', 400, 400, 'square', NULL, 'Pg3JV37n8YoiUJ4Mali2qOzUGYYZJy95nOqB8n0W', NULL, '2017-12-27 21:11:00', '2017-12-27 21:11:00'),
(5, NULL, 'customtiles/5a5689ed23f307.611146711.png', 400, 346, 'hexagon', NULL, 'Ize6lpd6og2vPiHm68HuFwEpjbzv5wwd6OLdU7x3', NULL, '2018-01-10 21:47:25', '2018-01-10 21:47:25'),
(6, NULL, 'customtiles/5a592cb8542901.455414121.png', 400, 400, 'square', NULL, 'nCnr5hWSAhmKsnxNTOsMq4WlRqD4vlupV15zdzIZ', NULL, '2018-01-12 21:46:32', '2018-01-12 21:46:32'),
(7, NULL, 'customtiles/5a60fb200cf9b1.635202591.png', 400, 400, 'square', NULL, 'UZuN7Oxbp6NOHI67sKGyXPggcyciukJ1og3HFa6R', NULL, '2018-01-18 19:53:04', '2018-01-18 19:53:04'),
(8, NULL, 'customtiles/5a6105e7592858.231641481.png', 400, 400, 'square', NULL, 'UZuN7Oxbp6NOHI67sKGyXPggcyciukJ1og3HFa6R', NULL, '2018-01-18 20:39:03', '2018-01-18 20:39:03'),
(9, NULL, 'customtiles/5a6107e4c81ec8.396904541.png', 400, 400, 'square', NULL, 'UZuN7Oxbp6NOHI67sKGyXPggcyciukJ1og3HFa6R', NULL, '2018-01-18 20:47:32', '2018-01-18 20:47:32'),
(10, NULL, 'customtiles/5a610865772e24.423971061.png', 400, 400, 'square', NULL, 'UZuN7Oxbp6NOHI67sKGyXPggcyciukJ1og3HFa6R', NULL, '2018-01-18 20:49:41', '2018-01-18 20:49:41'),
(11, NULL, 'customtiles/5a81cb8f0387c6.485912271.png', 400, 400, 'square', NULL, 'BS145LfPtZKb4Ufb8kCd7JxmAjdZ9E4Ci4e8tFqH', NULL, '2018-02-12 17:14:55', '2018-02-12 17:14:55'),
(12, NULL, 'customtiles/5a81cbb8a2f188.857308311.png', 400, 400, 'square', NULL, '10jxOnkz5CIPiOaDgwkDNM9BSRDFSvqlrrdfzknu', NULL, '2018-02-12 17:15:36', '2018-02-12 17:15:36'),
(13, NULL, 'customtiles/5a81cbca0e8542.704174121.png', 400, 400, 'square', NULL, 'BS145LfPtZKb4Ufb8kCd7JxmAjdZ9E4Ci4e8tFqH', NULL, '2018-02-12 17:15:54', '2018-02-12 17:15:54'),
(14, NULL, 'customtiles/5a81cbe5415338.964863401.png', 400, 400, 'square', NULL, '10jxOnkz5CIPiOaDgwkDNM9BSRDFSvqlrrdfzknu', NULL, '2018-02-12 17:16:21', '2018-02-12 17:16:21'),
(15, NULL, 'customtiles/5a81d10052a6b9.618304731.png', 400, 400, 'square', NULL, 'BS145LfPtZKb4Ufb8kCd7JxmAjdZ9E4Ci4e8tFqH', NULL, '2018-02-12 17:38:08', '2018-02-12 17:38:08'),
(16, NULL, 'customtiles/5a85ff6fd281f0.551554731.png', 400, 400, 'square', NULL, 'S3w6H4dWd6ySZjUBoQZpx3UDzlIdTEmP9inUsGP4', NULL, '2018-02-15 21:45:19', '2018-02-15 21:45:19'),
(17, NULL, 'customtiles/5a85ff8a9a7b18.660658801.png', 400, 400, 'square', NULL, 'S3w6H4dWd6ySZjUBoQZpx3UDzlIdTEmP9inUsGP4', NULL, '2018-02-15 21:45:46', '2018-02-15 21:45:46'),
(18, NULL, 'customtiles/5a9a2e14aa5db2.090262241.png', 400, 400, 'square', NULL, 'BlpSBVG3Ox9Rd0Twxw5KNLc6LAcKjfyC5LXnIi1X', NULL, '2018-03-03 05:09:40', '2018-03-03 05:09:40'),
(19, NULL, 'customtiles/32741952699333a3c71f59dfbaec51ce.png', 200, 200, 'square', NULL, 'wzlRg7puCi5JAEv7xvUSjWZFh3vFglNDW3H42tAI', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"ROSEWATER\",\"MUSTARD\",\"CERISE\",\"SNOW\"]}', '2018-03-22 06:49:44', '2018-03-22 06:49:44'),
(20, NULL, 'customtiles/4d4f4ba3bf065b5737c32fb4696ad7a8.png', 200, 200, 'square', NULL, 'sWQoiH6HC7GTGWYj7n4mgq249pdzTlbs6zaOKfnX', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"ORCHID\"]}', '2018-03-22 14:02:34', '2018-03-22 14:02:34'),
(21, NULL, 'customtiles/6bf44e689519fd88d50452f535b66a21.png', 200, 200, 'square', NULL, '4ApIWizVRWF1TWAAiqcL12NzyUMgLRMYPCN954Bl', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"CHALK\",\"CELADON\"]}', '2018-04-28 05:27:49', '2018-04-28 05:27:49'),
(22, NULL, 'customtiles/7f791f4157f96e5b6d3cfa1f09f118c1.png', 200, 200, 'square', NULL, 'HGZ6Se1IDxNAKWkA8oooPHEejRL4ke83h6YyP1Fz', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[]}', '2018-05-12 10:27:27', '2018-05-12 10:27:27'),
(24, NULL, 'customtiles/ac44cd67b9366feff23daf5fdd7e20d9.png', 200, 200, 'square', 21, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"CARAMEL\"]}', '2018-06-13 01:56:09', '2018-06-13 09:38:16'),
(25, NULL, 'customtiles/99b9db5b96d30e46cb017a05865658f9.png', 200, 200, 'square', NULL, 'RO8j1TgjGkNdMOKY18pd9PsjtSw0KYSAJAcGsdJr', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"MIDNIGHT\"]}', '2018-07-17 20:16:03', '2018-07-17 20:16:03'),
(26, NULL, 'customtiles/685fbebfb6ce4b2f4107b6bf3f734b4b.png', 200, 200, 'square', NULL, 'NMKk8smO7xAR6cyEIHOVfmp7jkLqKpKHHBiqgKND', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"CHALK\"]}', '2018-07-31 13:03:11', '2018-07-31 13:03:11'),
(27, NULL, 'customtiles/e9ea6cde02c0ce2fff64b11b72cb9a95.png', 200, 200, 'square', NULL, 'mmWo3FauUUvecdQ3y8Ggp5JC6e0b4rYM6BBAAqQM', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"CHALK\"]}', '2018-08-01 10:41:28', '2018-08-01 10:41:28'),
(28, NULL, 'customtiles/bcbe014f9a7c7bd77b77a6523810b609.png', 200, 200, 'square', NULL, 'mmWo3FauUUvecdQ3y8Ggp5JC6e0b4rYM6BBAAqQM', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[]}', '2018-08-01 10:44:22', '2018-08-01 10:44:22'),
(29, NULL, 'customtiles/6be21a75334d848daadac424008e7e13.png', 200, 200, 'square', NULL, 'nVAfKgXnV3a47tg0AV1qnbHKvJU6Dk9lhXfJndSK', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"NIGELLA\",\"APPLE GREEN\",\"CORAL\",\"SLATE\",\"CERISE\"]}', '2018-08-10 05:27:17', '2018-08-10 05:27:17'),
(30, NULL, 'customtiles/1f53ba0496cb972a87c8d9ab53daa40d.png', 200, 200, 'square', NULL, 'BP3Aq6DQOlROBVI1DAphO3uVt4GkqzYfcabB6eiC', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[]}', '2018-08-14 15:51:48', '2018-08-14 15:51:48'),
(31, NULL, 'customtiles/73405e02db3ff9118932a187433f32e9.png', 200, 200, 'square', NULL, 'BP3Aq6DQOlROBVI1DAphO3uVt4GkqzYfcabB6eiC', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"NIGELLA\",\"CRIMSON\"]}', '2018-08-14 15:52:19', '2018-08-14 15:52:19'),
(32, NULL, 'customtiles/b0fd1b9d21c1a7a2fa7eae93345cb6f1.png', 200, 200, 'square', NULL, 'BP3Aq6DQOlROBVI1DAphO3uVt4GkqzYfcabB6eiC', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"CRIMSON\"]}', '2018-08-14 15:52:49', '2018-08-14 15:52:49'),
(33, NULL, 'customtiles/5c820ab72e300816802ac92b81c71c24.png', 200, 200, 'square', 27, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[]}', '2018-09-03 10:26:28', '2018-09-03 10:26:28'),
(34, NULL, 'customtiles/b32e6b4e02bf42f17d5117f82175c4c7.png', 200, 200, 'square', NULL, 'CBWIiRJncnZvtFb3gw8FjAoW8XQGInHsWwNXNmrO', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"NIGELLA\"]}', '2018-09-07 20:53:36', '2018-09-07 20:53:36'),
(36, NULL, 'customtiles/e415589596fc35dbd5a3253488687c58.png', 200, 200, 'square', 9, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"INDIGO\",\"COCOA\",\"CHALK\",\"BLACK\",\"MUSTARD\"]}', '2018-09-13 08:05:45', '2018-09-13 08:05:45'),
(37, NULL, 'customtiles/d3e1aa28a591ebfac2adaa05a9d6b254.png', 200, 200, 'square', 9, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"INDIGO\",\"COCOA\",\"CHALK\",\"BLACK\",\"MUSTARD\"]}', '2018-09-13 08:06:46', '2018-09-13 08:06:46'),
(38, NULL, 'customtiles/d6b93eace5b3b9c6f71eacaa1c5c92c1.png', 200, 200, 'square', NULL, 'qJvXT5WQ8cBfXZxhWCPKj6s15Qir5lkNP5mW55I1', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"COCOA\"]}', '2018-09-13 10:25:19', '2018-09-13 10:25:19'),
(46, NULL, 'customtiles/2dbe24dfd1c7cacbc76435efa6c7fb55.png', 200, 200, 'square', NULL, 'RjdFIzsBXEndkFVOhVNk513eaHdsdBBySMAQuhXA', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"SAFFRON\"]}', '2018-09-19 19:10:59', '2018-09-19 19:10:59'),
(40, NULL, 'customtiles/e27eeab0b4db30f049ad11ce82d1a263.png', 200, 200, 'square', 9, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"NIGELLA\"]}', '2018-09-13 10:35:47', '2018-09-13 10:35:47'),
(42, NULL, 'customtiles/7d98c0fcde5a16c827143a9b26456493.png', 200, 200, 'square', NULL, 'GxWf0dvNIFSTHAgSnM6pTOcvV6gSaMDsUM07uhBU', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"INDIGO\",\"NIAGARA\"]}', '2018-09-13 10:37:59', '2018-09-13 10:37:59'),
(43, NULL, 'customtiles/054380733071f75fb16b9ceeadca5b9b.png', 200, 200, 'square', NULL, 'CAL5MYtsupTJQCKEDNol7eRJixh0QqfXLkrvTUt7', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"CARAFE\",\"CERISE\"]}', '2018-09-14 14:50:33', '2018-09-14 14:50:33'),
(44, NULL, 'customtiles/5633905a2f75abee7a8dec1992542f26.png', 200, 200, 'square', NULL, '3RgyaUFlRYhOsEwHtZ8vhJA7U7aXbDTcCkZXyzEe', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"LIMUN\"]}', '2018-09-19 19:05:39', '2018-09-19 19:05:39'),
(47, NULL, 'customtiles/21e0f1bdf6d5822ef494a667e08724d3.png', 200, 200, 'square', NULL, 'RjdFIzsBXEndkFVOhVNk513eaHdsdBBySMAQuhXA', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"SAFFRON\"]}', '2018-09-19 19:11:21', '2018-09-19 19:11:21'),
(48, NULL, 'customtiles/589c35de20448c998070e71e827df30f.png', 200, 200, 'square', NULL, 'RjdFIzsBXEndkFVOhVNk513eaHdsdBBySMAQuhXA', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"SAFFRON\"]}', '2018-09-19 19:13:42', '2018-09-19 19:13:42'),
(49, NULL, 'customtiles/d1c3ed5ac4e6f3feaec5f062c18dafb9.png', 200, 200, 'square', NULL, '3RgyaUFlRYhOsEwHtZ8vhJA7U7aXbDTcCkZXyzEe', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"BLACK\"]}', '2018-09-19 19:17:21', '2018-09-19 19:17:21'),
(50, NULL, 'customtiles/b314812b24b75c0119a49a2127030c74.png', 200, 200, 'square', NULL, 'EPpLXvb5MVMF8vpX2N5hZf9OJncaH2yAPR4TmkLF', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"CELADON\"]}', '2018-09-19 20:24:00', '2018-09-19 20:24:00'),
(74, NULL, 'customtiles/f59dc2c2c5a8787a0b6968971bbe1f91.png', 200, 200, 'square', NULL, 'vi4p6bJWpJzvTMKFWHLp5glOiHbM26Ie7zLSWSfO', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/LATICCE COLORS.png\",\"usedColorNames\":[]}', '2018-12-18 07:18:00', '2018-12-18 07:18:00'),
(73, NULL, 'customtiles/cbb2e590083ca2af12fff8b254a6c341.png', 200, 200, 'square', NULL, 'vi4p6bJWpJzvTMKFWHLp5glOiHbM26Ie7zLSWSfO', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/SEVILLE COLORS.png\",\"usedColorNames\":[]}', '2018-12-18 07:17:33', '2018-12-18 07:17:33'),
(53, NULL, 'customtiles/7108252f526e00e4393f29f4d11ecd55.png', 200, 200, 'square', NULL, 'BkJIYe7BZ0uxw55TwczTJWJ6RrdDez9nZA5w9KDm', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"ORCHID\",\"NIGHTFALL\",\"NIGELLA\",\"CRIMSON\",\"MIDNIGHT\"]}', '2018-09-20 16:39:20', '2018-09-20 16:39:20'),
(54, NULL, 'customtiles/43dbab2b668a702f732ae8430fce4542.png', 200, 200, 'square', NULL, 'BkJIYe7BZ0uxw55TwczTJWJ6RrdDez9nZA5w9KDm', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"BONE\",\"NIGHTFALL\",\"NIGELLA\",\"CRIMSON\",\"SAFFRON\"]}', '2018-09-20 16:40:35', '2018-09-20 16:40:35'),
(58, NULL, 'customtiles/f805e7909e7f853c66f2295d1a6fb3cc.png', 200, 200, 'square', NULL, 'xT63F83iIJVrIRExCcNASTZ3h2YTpyXNDBdS6GhF', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"BLACK\"]}', '2018-09-25 10:06:56', '2018-09-25 10:06:56'),
(56, NULL, 'customtiles/32a98f54c7bf670d46ea492c14b98aff.png', 200, 200, 'square', NULL, 'xT63F83iIJVrIRExCcNASTZ3h2YTpyXNDBdS6GhF', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"LAVANDER\",\"BLACK\",\"AUBERGINE\"]}', '2018-09-25 09:46:01', '2018-09-25 09:46:01'),
(57, NULL, 'customtiles/df2c41856ecc2175a22046de87213d1d.png', 200, 200, 'square', NULL, 'xT63F83iIJVrIRExCcNASTZ3h2YTpyXNDBdS6GhF', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"CLOVE\"]}', '2018-09-25 10:05:18', '2018-09-25 10:05:18'),
(63, NULL, 'customtiles/d7f70b2fd2f1344613d345c8b8617c74.png', 200, 200, 'square', NULL, 'LqlJr1zB0kLGJ3Fey9ezz1fw0619VCUmObtM2wak', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/SEVILLE COLORS.png\",\"usedColorNames\":[\"SAFFRON\",\"NIGELLA\",\"CHESTNUT\",\"SAGE\"]}', '2018-09-27 19:07:59', '2018-09-27 19:07:59'),
(64, NULL, 'customtiles/9e667cc623dff361f82b396b732a002e.png', 200, 200, 'square', NULL, 'LqlJr1zB0kLGJ3Fey9ezz1fw0619VCUmObtM2wak', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/TANGIER COLORS.png\",\"usedColorNames\":[\"CRIMSON\",\"LAVANDER\",\"CARAFE\",\"ORCHID\"]}', '2018-09-27 19:08:28', '2018-09-27 19:08:28'),
(65, NULL, 'customtiles/d89a3f5b6e20416917b7d76ffd1853a2.png', 200, 200, 'square', NULL, 'BlWegtKMNWNGl1w5L2scNhoSsy0tgYnm8HTIz0zz', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"APPLE GREEN\",\"CARAFE\",\"NIGELLA\"]}', '2018-10-13 13:35:35', '2018-10-13 13:35:35'),
(66, NULL, 'customtiles/53464d4ae9aa5f4e2c3ed54d0ec9784e.png', 200, 200, 'square', NULL, '2PFN6AxtdCEQC3dHjeFbBjhmzB4r5fczsVvq7AN8', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[]}', '2018-10-14 14:16:19', '2018-10-14 14:16:19'),
(67, NULL, 'customtiles/a15a8fa6f75f258a26ac48a0f7d71b63.png', 200, 200, 'square', NULL, '2PFN6AxtdCEQC3dHjeFbBjhmzB4r5fczsVvq7AN8', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[]}', '2018-10-14 14:18:18', '2018-10-14 14:18:18'),
(68, NULL, 'customtiles/0c83ea6aab5932e2bd4e9ee0159b94bd.png', 200, 200, 'square', NULL, 'HnU7IykvvwmwtpobptZFz3fe1XhA95fCQZpO1D1j', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/LATICCE COLORS.png\",\"usedColorNames\":[]}', '2018-10-23 15:20:09', '2018-10-23 15:20:09'),
(69, NULL, 'customtiles/80947d15a5a6e1505c524a723de2e6c6.png', 200, 200, 'square', NULL, 'jwRbx7tuJsP5SUAYfGdjlZE91zfoiNWQBtMOMgTD', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/LATICCE COLORS.png\",\"usedColorNames\":[\"CHALK\"]}', '2018-10-29 18:00:27', '2018-10-29 18:00:27'),
(70, NULL, 'customtiles/31fe5da602ea5f90697e263b3514e6d7.png', 200, 200, 'square', NULL, 'jwRbx7tuJsP5SUAYfGdjlZE91zfoiNWQBtMOMgTD', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/SEVILLE COLORS.png\",\"usedColorNames\":[\"INDIGO\"]}', '2018-10-29 18:01:09', '2018-10-29 18:01:09'),
(71, NULL, 'customtiles/2bd4ff61fa39e252caa40ba99ea9bd90.png', 200, 200, 'square', NULL, 'SaYP8pYSM5gf1jraP6iRbWDBS9yibRNM2hOAvQuM', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[]}', '2018-11-01 10:33:31', '2018-11-01 10:33:31'),
(72, NULL, 'customtiles/442a57f382be8ec358986a5ffb799648.png', 200, 200, 'square', NULL, 'uIiPKs49ies4DZoGZIDzD1a3VKOvIEzFnKByoaZI', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"BERRY\",\"SAGE\",\"NIGELLA\",\"CRIMSON\"]}', '2018-11-08 05:58:35', '2018-11-08 05:58:35'),
(75, NULL, 'customtiles/df0618613e0edca7e3e1b97031611834.png', 200, 200, 'square', 29, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[]}', '2018-12-19 16:44:10', '2018-12-21 17:54:53'),
(76, NULL, 'customtiles/32a7011a83db0e22075467f75c61952e.png', 200, 200, 'square', 29, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/TANGIER COLORS.png\",\"usedColorNames\":[]}', '2018-12-19 16:44:22', '2018-12-21 17:54:53'),
(77, NULL, 'customtiles/55507a62a9e5f7652c6a90e6329195a6.png', 200, 200, 'square', 29, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/TANGIER COLORS.png\",\"usedColorNames\":[\"CARAMEL\",\"NAVY\",\"APPLE GREEN\",\"COCOA\"]}', '2018-12-19 16:44:46', '2018-12-21 17:54:53'),
(78, NULL, 'customtiles/9570feb5889fe0d115a9d079d161c07d.png', 200, 200, 'square', 29, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/SEVILLE COLORS.png\",\"usedColorNames\":[\"APPLE GREEN\"]}', '2018-12-19 16:45:27', '2018-12-21 17:54:53'),
(79, NULL, 'customtiles/f9b3cfd5a1b8179d5d4c12bb20bd50e6.png', 200, 200, 'square', 29, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/SEVILLE COLORS.png\",\"usedColorNames\":[\"APPLE GREEN\"]}', '2018-12-19 16:47:08', '2018-12-21 17:54:53'),
(80, NULL, 'customtiles/13a23660d25fc428360ad163a67ccf81.png', 200, 200, 'square', 29, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"NAVY\",\"CARAMEL\",\"APPLE GREEN\"]}', '2018-12-19 16:48:42', '2018-12-21 17:54:53'),
(81, NULL, 'customtiles/93415ccef17721da86adfae14f1372d0.png', 200, 200, 'square', 29, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"NAVY\",\"CARAMEL\",\"APPLE GREEN\"]}', '2018-12-19 16:49:16', '2018-12-21 17:54:53'),
(82, NULL, 'customtiles/7b19265f6a5bd129fc06d2ee19676022.png', 200, 200, 'square', 29, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"NAVY\",\"CARAMEL\",\"APPLE GREEN\"]}', '2018-12-19 16:49:24', '2018-12-21 17:54:53'),
(83, NULL, 'customtiles/f4a88b00441d9a3e91ffc28e64828420.png', 200, 200, 'square', 29, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"NAVY\",\"CARAMEL\",\"APPLE GREEN\"]}', '2018-12-19 16:49:35', '2018-12-21 17:54:53'),
(84, NULL, 'customtiles/65e1290da149c5a8f00e6ff965881452.png', 200, 200, 'square', 29, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"NAVY\",\"CARAMEL\",\"APPLE GREEN\"]}', '2018-12-19 16:49:55', '2018-12-21 17:54:53'),
(85, NULL, 'customtiles/8ee215a64ba3e4a02bde23eb9176fdae.png', 200, 200, 'square', 29, NULL, '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/Alameda.png\",\"usedColorNames\":[\"NAVY\",\"CARAMEL\",\"APPLE GREEN\"]}', '2018-12-19 16:50:01', '2018-12-21 17:54:53'),
(86, NULL, 'customtiles/3fa8e394ce43e860348799b680ef3aad.png', 200, 200, 'square', NULL, 'agXMDIDu8cBWUqS2TwDfRF2whFV4AxlAmZAojycv', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/GRANADA COLORS.png\",\"usedColorNames\":[\"ORCHID\",\"APPLE GREEN\"]}', '2018-12-21 17:52:22', '2018-12-21 17:52:22'),
(87, NULL, 'customtiles/9760c40fc13e687583fe48e415b92b10.png', 200, 200, 'square', NULL, 'fZ7IHloDmw0smokfmjb8tQdnP63yQpbiQtQSMQ16', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/GRANADA COLORS.png\",\"usedColorNames\":[]}', '2019-01-05 19:46:14', '2019-01-05 19:46:14'),
(88, NULL, 'customtiles/500693376c67d91d091c917852f2202d.png', 200, 200, 'square', NULL, 'fZ7IHloDmw0smokfmjb8tQdnP63yQpbiQtQSMQ16', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/LATICCE COLORS.png\",\"usedColorNames\":[]}', '2019-01-05 19:47:49', '2019-01-05 19:47:49'),
(89, NULL, 'customtiles/8724c5695b6f845d34b6517b66f2de07.png', 200, 200, 'square', NULL, 'jWeTW8z54z4VgC321NV6re1mqzV9PDeDkY4LxvGR', '{\"baseTileUrl\":\"/storage/tilesdesigner/classic/SEVILLE COLORS.png\",\"usedColorNames\":[\"BLACK\",\"LIMUN\",\"INDIGO\",\"CRIMSON\"]}', '2019-01-22 17:48:31', '2019-01-22 17:48:31');

-- --------------------------------------------------------

--
-- Table structure for table `filters`
--

CREATE TABLE `filters` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `field` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surface` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'checkbox',
  `values` text COLLATE utf8mb4_unicode_ci,
  `enabled` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2017_02_27_200633_create_tasks_table', 1),
(10, '2017_02_28_141841_create_filters_table', 2),
(11, '2017_02_28_141926_create_rooms_table', 2),
(12, '2017_02_28_112616_create_tiles_table', 3),
(13, '2017_03_14_081503_create_companies_table', 4),
(14, '2017_03_14_090456_create_savedrooms_table', 4),
(15, '2017_06_26_102031_create_room2ds_table', 4),
(16, '2017_09_11_174519_create_surface_types_table', 4),
(17, '2017_09_11_224734_create_room_types_table', 4),
(18, '2017_10_19_165335_create_panoramas_table', 4),
(19, '2017_12_22_154328_create_custom_tiles_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `panoramas`
--

CREATE TABLE `panoramas` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shadow` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shadow_matt` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surfaces` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vr` tinyint(1) DEFAULT '0',
  `enabled` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `panoramas`
--

INSERT INTO `panoramas` (`id`, `name`, `type`, `icon`, `image`, `shadow`, `shadow_matt`, `surfaces`, `vr`, `enabled`, `created_at`, `updated_at`) VALUES
(123, 'Clothing Store 01', 'commercial', 'panoramas/arpxnalXhTZZOCR5Z6LzKhjLdVlMBlmEPFYB7hrr.jpg', 'panoramas/sxdPgJb77t9q2tuyvBrhD5t6ad6gg4JOtw8efRiK.png', 'panoramas/B9GdS8H6nwy0VitOpu5jxfXOGt2DjWOxWDOJMkTZ.jpg', 'panoramas/UPw5KVaQC3xS973rrSMymln2vZ3gDtjQAzzuEuue.jpg', '[{\"type\":\"floor\",\"json\":\"/storage/panoramas/clothing_1/Clothing store 01_00_floor.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/clothing_1/Clothing store 01_00_wall.json\"}]', 0, 1, '2024-08-31 00:58:42', '2024-08-31 05:15:06'),
(124, 'Clothing Store 02', 'commercial', 'panoramas/KzgEuJ3tqZfh6IIZTFXIxMGPT72zE3cSHx0y6xN2.jpg', 'panoramas/WXyd7JQPONPfTS3aucPu6vkMkHYjbk9p6kW9f4J0.png', 'panoramas/8ANSZHy6wGluJr355szAlDCm1yFbUqheBLUvnqO3.jpg', 'panoramas/dHkYwDxCcRx88EzKtMjtUg3fVk7sw8ilTkoWmPIG.jpg', '[{\"type\":\"floor\",\"json\":\"/storage/panoramas/clothing_2/Clothing store 02_00_floor.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/clothing_2/Clothing store 02_00_wall1.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/clothing_2/Clothing store 02_00_wall2.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/clothing_2/Clothing store 02_00_wall3.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/clothing_2/Clothing store 02_00_wall4.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/clothing_2/Clothing store 02_00_wall5.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/clothing_2/Clothing store 02_00_wall6.json\"}]', 0, 1, '2024-08-31 01:00:24', '2024-08-31 05:15:06'),
(128, 'Mall 01', 'commercial', 'panoramas/nVSaxGHudOBkS3aQMRuGUfY7SkS8iOAAWDsTbK7K.jpg', 'panoramas/YJ6quKSe5T2ZJKB8yEQbVDQK1keylCzybgPLQ8vY.png', 'panoramas/zhGxqZ5YXp9cfYYA6xBLKev3JxlK5xtNdT7wUwTO.jpg', 'panoramas/hVmCTVJDmhuPBHxzQ6beocRXB7Lv0SsqIvaCYZtD.jpg', '[{\"type\":\"floor\",\"json\":\"/storage/panoramas/mall01/Mall01_00_floor.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/mall01/Mall01_00_wall1.json\"},\r\n{\"type\":\"wall\",\"json\":\"/storage/panoramas/mall01/Mall01_00_wall2.json\"},\r\n{\"type\":\"wall\",\"json\":\"/storage/panoramas/mall01/Mall01_00_wall3.json\"},\r\n{\"type\":\"wall\",\"json\":\"/storage/panoramas/mall01/Mall01_00_wall4.json\"},\r\n{\"type\":\"wall\",\"json\":\"/storage/panoramas/mall01/Mall01_00_wall5.json\"},\r\n{\"type\":\"wall\",\"json\":\"/storage/panoramas/mall01/Mall01_00_wall6.json\"}]', 0, 1, '2024-08-31 01:12:20', '2024-09-07 07:00:03'),
(129, 'Mall 02', 'commercial', 'panoramas/r1DC0RcJ64HpMm0oc9mv6TdiqyhIU0K9bKlKnUWn.jpg', 'panoramas/qHyid2youWakUaqmc73NRbIDAFKYgvr19Pi2Q0BQ.png', 'panoramas/hA4jjV2YWGRarLPjg1DEz07zALmnlCHpepPMkVCk.jpg', 'panoramas/XHX6UBLuXf4qAD3NrwUhfS5tntecsmMLgnMtEWCk.jpg', '[{\"type\":\"floor\",\"json\":\"/storage/panoramas/mall02/Mall-02_00_floor.json\"}]', 0, 1, '2024-08-31 01:17:00', '2024-09-02 03:20:41'),
(131, 'Public Washroom 01', 'bathroom', 'panoramas/0bKtxMBFWrwLqLnBUKgAukWgOGrG9Dgch8le4wwz.jpg', 'panoramas/NJq45m7znCfqux1wrfirYsOCtA0XkfuzS2j06tpa.png', 'panoramas/yjnxYQS3nvotpXnMCuRDbGORaVFcsxRNAXIkzqOG.jpg', 'panoramas/kOcdiK2Q7abO0qkojLqVi923NP6xmX5kDdsGnysG.jpg', '[{\"type\":\"floor\",\"json\":\"/storage/panoramas/publicwashroom_1/floor.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/publicwashroom_1/wall1.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/publicwashroom_1/wall2.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/publicwashroom_1/wall3.json\"},\r\n{\"type\":\"wall\",\"json\":\"/storage/panoramas/publicwashroom_1/wall4.json\"},{\"type\":\"counter\",\"json\":\"/storage/panoramas/publicwashroom_1/cf.json\"},{\"type\":\"counter\",\"json\":\"/storage/panoramas/publicwashroom_1/ct.json\"}]', 0, 1, '2024-09-05 01:19:12', '2024-09-19 04:47:44'),
(132, 'Public Washroom 02', 'bathroom', 'panoramas/YKwukcKNdJ7VVgQpsPDbrWUuzjOD6o6dyoCT0N29.jpg', 'panoramas/zeMhoHU7BaegEG29QI7pfnPMU6SWPO0GMSSFYQ7X.png', 'panoramas/BR4oUdomRRsV2fuOQBv8sUCxg8ucvwL87Ft20wLB.jpg', 'panoramas/SIPnPacWXmHq1EzUTe2VW7O3PMM5bpiTLM7ejW3P.jpg', '[{\"type\":\"floor\",\"json\":\"/storage/panoramas/publicwashroom_2/floor.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/publicwashroom_2/wall1.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/publicwashroom_2/wall2.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/publicwashroom_2/wall3.json\"},\r\n{\"type\":\"wall\",\"json\":\"/storage/panoramas/publicwashroom_2/wall4.json\"},\r\n{\"type\":\"wall\",\"json\":\"/storage/panoramas/publicwashroom_2/wall5.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/publicwashroom_2/wall6.json\"},{\"type\":\"wall\",\"json\":\"/storage/panoramas/publicwashroom_2/wall7.json\"},\r\n{\"type\":\"wall\",\"json\":\"/storage/panoramas/publicwashroom_2/wall8.json\"},{\"type\":\"counter\",\"json\":\"/storage/panoramas/publicwashroom_2/ct.json\"},{\"type\":\"counter\",\"json\":\"/storage/panoramas/publicwashroom_2/cf.json\"}]', 0, 1, '2024-09-05 01:28:07', '2024-09-21 00:50:42');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room2ds`
--

CREATE TABLE `room2ds` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shadow` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shadow_matt` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surfaces` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room2ds`
--

INSERT INTO `room2ds` (`id`, `name`, `type`, `icon`, `image`, `shadow`, `shadow_matt`, `surfaces`, `enabled`, `created_at`, `updated_at`) VALUES
(85, 'Test', 'other', NULL, 'rooms2d/yIpzK6KllJIK9cckC6n7ZLJrplJGe4d6selZwipd.png', 'rooms2d/qThOB7jQX1HPOPCJc2zrswp4jaAqa8rmEqNisxCF.jpg', NULL, '[{\"0\":\"1\",\"1\":\"-4.2\",\"2\":\"-2.3\",\"3\":\"0\",\"4\":\"-41.6\",\"5\":\"0\",\"6\":\"-29.6\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"20.4\",\"10\":\"20.4\",\"11\":[[\"0\",\"1721\"],[\"0\",\"0\"],[\"2560\",\"0\"],[\"2560\",\"1721\"]],\"176\":\"floor\",\"group\":\"1\",\"cameraFov\":\"30\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"\"}]', 0, '2024-04-23 03:56:11', '2024-08-14 07:26:21'),
(93, 'Kitchen 01', 'kitchen', 'rooms2d/8WtgXGusegMz6XIT0Asz8stGbVGdXChrZNLAlZMj.jpg', 'rooms2d/OlAlP7aEcxyoZzzxPfIc2iabbAf53O37XQlKtHe1.png', 'rooms2d/asrHsD394Qw5qANEHh2vgdsX1nQdfBbPxykOBqgy.jpg', 'rooms2d/G12mCM95heOOr2RtZrnhS27PtC4W8EcfkRiSn8D9.jpg', '[{\"0\":\"1\",\"1\":\"-58.9\",\"2\":\"-9.23\",\"3\":\"2.4\",\"4\":\"0\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"20\",\"10\":\"20\",\"11\":[[\"62\",\"612\"],[\"62\",\"20\"],[\"1476\",\"19\"],[\"1477\",\"612\"],[\"1382\",\"615\"],[\"1382\",\"462\"],[\"1311\",\"434\"],[\"1299\",\"434\"],[\"1296\",\"23\"],[\"842\",\"32\"],[\"844\",\"435\"],[\"800\",\"435\"],[\"800\",\"615\"]],\"176\":\"wall\",\"group\":\"1\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"2\",\"1\":\"-0.2\",\"2\":\"3.4\",\"3\":\"0\",\"4\":\"0\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"24\",\"10\":\"24\",\"11\":[[\"800\",\"724\"],[\"800\",\"462\"],[\"1382\",\"462\"],[\"1382\",\"724\"]],\"176\":\"counter\",\"group\":\"2\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"3\",\"1\":\"0\",\"2\":\"2.9\",\"3\":\"0\",\"4\":\"-80\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"24\",\"10\":\"24\",\"11\":[[\"800\",\"462\"],[\"800\",\"435\"],[\"1311\",\"434\"],[\"1382\",\"462\"]],\"176\":\"counter\",\"group\":\"2\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"4\",\"1\":\"-60.4\",\"2\":\"-9.8\",\"3\":\"0\",\"4\":\"-76.2\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"20.5\",\"10\":\"20.5\",\"11\":[[\"0\",\"999\"],[\"0\",\"582\"],[\"65\",\"583\"],[\"74\",\"612\"],[\"800\",\"611\"],[\"800\",\"724\"],[\"1382\",\"724\"],[\"1382\",\"612\"],[\"1600\",\"617\"],[\"1600\",\"999\"]],\"176\":\"floor\",\"group\":\"4\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"5\",\"1\":\"40.2\",\"2\":\"9.1\",\"3\":\"0\",\"4\":\"0\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"16.6\",\"10\":\"16.6\",\"11\":[[\"844\",\"435\"],[\"841\",\"230\"],[\"1291\",\"235\"],[\"1304\",\"435\"]],\"176\":\"wall\",\"group\":\"1\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"}]', 1, '2024-04-24 03:44:49', '2024-08-15 00:34:29'),
(94, 'Kitchen 02', 'kitchen', 'rooms2d/p6b2PyMufvC6qurBUXvPwSBMPpdE6NDlLrqvc4f7.jpg', 'rooms2d/RZgo5ygxWA1meO33SUwrVl6DK1hAYbrRVVqW9d08.png', 'rooms2d/REqcB7f2NtzbpiYSC6zmHzE8y5eqeL7muVDc0ibc.jpg', 'rooms2d/eHIZExcit8DlxI7xAuOL8lwDF0KAI2TYuZlos7a7.jpg', '[{\"0\":\"1\",\"1\":\"-63\",\"2\":\"-14.7\",\"3\":\"0\",\"4\":\"-81.3\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"20.8\",\"10\":\"20.8\",\"11\":[[\"0\",\"999\"],[\"0\",\"693\"],[\"40\",\"679\"],[\"398\",\"679\"],[\"398\",\"790\"],[\"1100\",\"790\"],[\"1100\",\"625\"],[\"1393\",\"624\"],[\"1398\",\"679\"],[\"1575\",\"679\"],[\"1600\",\"676\"],[\"1600\",\"999\"]],\"176\":\"floor\",\"group\":\"2\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"2\",\"1\":\"-20.6\",\"2\":\"0.6\",\"3\":\"0\",\"4\":\"0\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"20.8\",\"10\":\"20.8\",\"11\":[[\"543\",\"481\"],[\"543\",\"326\"],[\"1198\",\"323\"],[\"1195\",\"483\"]],\"176\":\"wall\",\"group\":\"5\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"3\",\"1\":\"-33.5\",\"2\":\"-24.3\",\"3\":\"0\",\"4\":\"0\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"22.2\",\"10\":\"22.2\",\"11\":[[\"398\",\"572\"],[\"398\",\"790\"],[\"1100\",\"790\"],[\"1100\",\"625\"],[\"926\",\"572\"]],\"176\":\"counter\",\"group\":\"4\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"4\",\"1\":\"0\",\"2\":\"0\",\"3\":\"0\",\"4\":\"-81.2\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"22.2\",\"10\":\"22.2\",\"11\":[[\"398\",\"572\"],[\"926\",\"572\"],[\"926\",\"529\"],[\"425\",\"537\"]],\"176\":\"counter\",\"group\":\"4\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"5\",\"1\":\"-63.3\",\"2\":\"-15.1\",\"3\":\"0\",\"4\":\"0\",\"5\":\"0\",\"6\":\"-0.3\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"20.9\",\"10\":\"20.9\",\"11\":[[\"40\",\"679\"],[\"36\",\"71\"],[\"1586\",\"71\"],[\"1575\",\"679\"],[\"1398\",\"679\"],[\"1276\",\"578\"],[\"1266\",\"230\"],[\"400\",\"213\"],[\"394\",\"679\"]],\"176\":\"wall\",\"group\":\"5\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"}]', 1, '2024-04-24 04:37:27', '2024-08-15 00:33:58'),
(99, 'Kitchen 03', 'kitchen', 'rooms2d/hgUsD1tJ8h3eekmSedLkqXuqlTZfzDv9oSD6lPyM.jpg', 'rooms2d/igewh2DWLXcsL79wO4vr4uvGHsgx9RYhbkHgyMBO.png', 'rooms2d/rjA1GWLbwY6otxW3HS1OpwW1GdUrrZQdJOUj3knE.jpg', 'rooms2d/OSj3D9JpRh63zoFHEyPxjkh4Pbt1nHGpB9x99VHJ.jpg', '[{\"0\":\"1\",\"1\":\"-44.9\",\"2\":\"-14.5\",\"3\":\"0\",\"4\":\"-82.5\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"21.4\",\"10\":\"21.4\",\"11\":[[\"0\",\"999\"],[\"0\",\"734\"],[\"228\",\"654\"],[\"328\",\"654\"],[\"1101\",\"650\"],[\"1151\",\"792\"],[\"1600\",\"792\"],[\"1600\",\"999\"]],\"176\":\"floor\",\"group\":\"1\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"2\",\"1\":\"-42.1\",\"2\":\"-13.1\",\"3\":\"0\",\"4\":\"0\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"20\",\"10\":\"20\",\"11\":[[\"228\",\"654\"],[\"217\",\"72\"],[\"1116\",\"114\"],[\"1001\",\"486\"],[\"351\",\"486\"],[\"325\",\"500\"],[\"325\",\"509\"],[\"327\",\"654\"]],\"176\":\"wall\",\"group\":\"2\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"3\",\"1\":\"29.3\",\"2\":\"-24.7\",\"3\":\"0\",\"4\":\"0\",\"5\":\"-95.7\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"20\",\"10\":\"20\",\"11\":[[\"3321\",\"2204\"],[\"3322\",\"351\"],[\"3458\",\"277\"],[\"3458\",\"2378\"]],\"176\":\"wall\",\"group\":\"3\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"4\",\"1\":\"28.8\",\"2\":\"-24.75\",\"3\":\"0\",\"4\":\"0\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"26\",\"10\":\"26\",\"11\":[[\"1151\",\"792\"],[\"1146\",\"71\"],[\"1600\",\"75\"],[\"1600\",\"792\"]],\"176\":\"wall\",\"group\":\"3\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"5\",\"1\":\"0\",\"2\":\"0\",\"3\":\"0\",\"4\":\"-82.2\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"10\",\"10\":\"10\",\"11\":[[\"1001\",\"486\"],[\"351\",\"486\"],[\"325\",\"500\"],[\"1015\",\"500\"],[\"1015\",\"500\"]],\"176\":\"counter\",\"group\":\"5\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"6\",\"1\":\"0\",\"2\":\"0.4\",\"3\":\"0\",\"4\":\"0\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"20\",\"10\":\"20\",\"11\":[[\"1015\",\"500\"],[\"1019\",\"522\"],[\"325\",\"509\"],[\"325\",\"500\"]],\"176\":\"counter\",\"group\":\"5\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"}]', 1, '2024-04-25 01:59:08', '2024-08-15 00:33:30'),
(101, 'Outdoor E', 'outdoor', NULL, 'rooms2d/rnn6kqZ2vDKcTnOz1IQa6EyhbuH4iizTdUKdzKEe.png', 'rooms2d/xtXD39FIbmwOJCRY3SbR0JeKZ4FVxo3U6xqNfk5O.jpg', NULL, '[]', 0, '2024-04-27 00:10:05', '2024-05-20 03:46:47'),
(102, 'Outdoor F', 'outdoor', NULL, 'rooms2d/aqcz9HDxpmioGfkuoioE9Qp2FDiTHytogDT7mc9M.png', 'rooms2d/xc2buGlJhwB2iv8dzIC2vomFGBueoWQRxzCGt3TL.jpg', NULL, '[{\"0\":\"1\",\"1\":\"0\",\"2\":\"0\",\"3\":\"0\",\"4\":\"-65.7\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"25\",\"10\":\"25\",\"11\":[[\"437\",\"1174\"],[\"4382\",\"1221\"],[\"4800\",\"2879\"],[\"4800\",\"3227\"],[\"0\",\"3227\"],[\"0\",\"1845\"]],\"176\":\"floor\",\"group\":\"1\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"}]', 0, '2024-04-27 00:10:54', '2024-05-20 03:46:47'),
(103, 'Outdoor G', 'outdoor', NULL, 'rooms2d/NnYOcMMS6YGkfseywkAgpL7FCwn8cKs39eTvTmFo.png', 'rooms2d/hMZfdORVLrnFPf4qYlPhgi8bzKfyxIj2mc2OJvwp.jpg', NULL, '[{\"0\":\"1\",\"1\":\"3.1\",\"2\":\"-0.2\",\"3\":\"0\",\"4\":\"-65\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"27\",\"10\":\"27\",\"11\":[[\"0\",\"1127\"],[\"4800\",\"1217\"],[\"4800\",\"3227\"],[\"0\",\"3227\"]],\"176\":\"floor\",\"group\":\"1\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"}]', 0, '2024-04-27 00:11:30', '2024-05-20 03:46:47'),
(117, 'Living Room 01', 'livingroom', 'rooms2d/AM8tGWDBUoHe18JfURPVIZSNyg3icRbtDuo55Zbj.jpg', 'rooms2d/z5DIRoDgErJVbSZnVXcCZldK997LPZ7kKu8vVYJQ.png', 'rooms2d/Ims2uvm8nDQzZIwfT8GfSTefdbC1tEdPPZMfQz3n.jpg', 'rooms2d/WQNTD7dw41mn4vmxJDsDjsL2otNes0wGL6tUEX5O.jpg', '[{\"0\":\"1\",\"1\":\"-53.4\",\"2\":\"-8.6\",\"3\":\"0\",\"4\":\"0\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"20.5\",\"10\":\"20.5\",\"11\":[[\"161\",\"599\"],[\"1411\",\"599\"],[\"1372\",\"0\"],[\"154\",\"0\"]],\"176\":\"wall\",\"group\":\"1\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"2\",\"1\":\"-53\",\"2\":\"-7.8\",\"3\":\"0\",\"4\":\"-69.9\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"20.35\",\"10\":\"20.35\",\"11\":[[\"0\",\"999\"],[\"0\",\"685\"],[\"161\",\"599\"],[\"1411\",\"599\"],[\"1600\",\"599\"],[\"1600\",\"999\"]],\"176\":\"floor\",\"group\":\"2\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"}]', 1, '2024-04-30 06:42:28', '2024-08-15 23:33:34'),
(120, 'Outdoor 01', 'outdoor', 'rooms2d/u72KuKuVFZslrF0UqIg2vpmqoYwVsXca8MfSLl46.jpg', 'rooms2d/hsEtr7G3V24EBxT0Yyy3RDhR2OfGewXIsAX4iTO8.png', 'rooms2d/QNQ6DdaReqepGe05HCqppIeBW81JVVrtXHxl6nFb.jpg', 'rooms2d/3OoueyUltdAYCOJ5nPSrMIocqQEz4HQUTEBcZOyG.jpg', '[{\"0\":\"1\",\"1\":\"26.2\",\"2\":\"8.9\",\"3\":\"0\",\"4\":\"-73.5\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"17.5\",\"10\":\"17.5\",\"11\":[[\"0\",\"1000\"],[\"0\",\"286\"],[\"266\",\"286\"],[\"274\",\"282\"],[\"310\",\"264\"],[\"349\",\"264\"],[\"349\",\"342\"],[\"349\",\"393\"],[\"1127\",\"393\"],[\"1600\",\"340\"],[\"1600\",\"1000\"]],\"176\":\"floor\",\"group\":\"1\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"2\",\"1\":\"-43.9\",\"2\":\"17.5\",\"3\":\"0\",\"4\":\"0\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"15\",\"10\":\"15\",\"11\":[[\"266\",\"286\"],[\"266\",\"0\"],[\"0\",\"0\"],[\"0\",\"286\"]],\"176\":\"wall\",\"group\":\"2\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"3\",\"1\":\"-44.3\",\"2\":\"17.4\",\"3\":\"0\",\"4\":\"0\",\"5\":\"-43.5\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"15\",\"10\":\"15\",\"11\":[[\"266\",\"286\"],[\"266\",\"0\"],[\"277\",\"0\"],[\"274\",\"282\"]],\"176\":\"wall\",\"group\":\"2\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"4\",\"1\":\"-40.4\",\"2\":\"19.4\",\"3\":\"0\",\"4\":\"0\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"12.2\",\"10\":\"12.2\",\"11\":[[\"310\",\"264\"],[\"310\",\"0\"],[\"349\",\"0\"],[\"349\",\"264\"]],\"176\":\"wall\",\"group\":\"2\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"5\",\"1\":\"26.4\",\"2\":\"8.6\",\"3\":\"0\",\"4\":\"0\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"17.62\",\"10\":\"17.62\",\"11\":[[\"349\",\"0\"],[\"349\",\"393\"],[\"1120\",\"393\"],[\"1127\",\"0\"]],\"176\":\"wall\",\"group\":\"2\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"}]', 1, '2024-05-13 05:13:52', '2024-08-15 23:26:47'),
(123, 'Outdoor 02', 'outdoor', 'rooms2d/rQjijNxdyBbKxHnZzR7l65pDyUT2reeSW8ww6pPX.jpg', 'rooms2d/P0KVoYYA5AGJF22j8xOZ2wJtnx7GJ8ccQi07jfEd.png', 'rooms2d/VPJSb6410WDoq40wDtDYzeyT7aLxU7mPIgnxYKpZ.jpg', 'rooms2d/7IdzOBUlrSdbLTKWNsYvOoNhV4B9ebAXf51PnTIG.jpg', '[{\"0\":\"1\",\"1\":\"37.5\",\"2\":\"21.6\",\"3\":\"0\",\"4\":\"-46\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"18.2\",\"10\":\"18.2\",\"11\":[[\"0\",\"999\"],[\"0\",\"0\"],[\"1600\",\"0\"],[\"1600\",\"999\"]],\"176\":\"floor\",\"group\":\"1\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"}]', 1, '2024-05-13 05:17:13', '2024-08-15 00:54:02'),
(130, 'Outdoor 03', 'outdoor', 'rooms2d/2eHaqAA5gz5k1UhuYWPkhfXXO4uONWlnthxpy2hh.jpg', 'rooms2d/xMdDj14v4RzTPiOQC1bOBKl3sU2zMXolPFRunqpM.png', 'rooms2d/SXmyIHcPseAgrX7RXTpGZq9AtA0ZEUdfngnOz3BZ.jpg', 'rooms2d/rUixDFSsr6Af48SPViDj8yAgvtkINNEGPZ7B21ek.jpg', '[{\"0\":\"1\",\"1\":\"44.7\",\"2\":\"4.9\",\"3\":\"0\",\"4\":\"-66.4\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"21.2\",\"10\":\"21.2\",\"11\":[[\"0\",\"1000\"],[\"0\",\"0\"],[\"1600\",\"0\"],[\"1600\",\"1000\"]],\"176\":\"floor\",\"group\":\"1\",\"cameraFov\":\"45\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"}]', 1, '2024-05-13 05:22:09', '2024-08-15 00:52:39'),
(151, 'Living Room 02', 'livingroom', 'rooms2d/lqPgCuS0WRawx5u2bcPzeut4b7atbtqTsraEYy1d.jpg', 'rooms2d/uBOJ2wBpT2hcMR0nflfZrBu1rxXMVoqNY92pBcOm.png', 'rooms2d/qLtKUeUgWgVxpcdSYhczrNzssxmQISOxp50Rpin7.jpg', 'rooms2d/CqLvw5TyIIxj55letbGMfKXvtys9uEnxWOtMwf04.jpg', '[{\"0\":\"1\",\"1\":\"-13\",\"2\":\"13.3\",\"3\":\"0\",\"4\":\"-66.5\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"19\",\"10\":\"19\",\"11\":[[\"0\",\"999\"],[\"0\",\"0\"],[\"1600\",\"0\"],[\"1600\",\"999\"]],\"176\":\"floor\",\"group\":\"1\",\"cameraFov\":\"40\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"}]', 1, '2024-05-28 03:35:55', '2024-08-15 23:33:55'),
(164, 'Living Room 03', 'livingroom', 'rooms2d/3lUt2vOguWO0BTbtzZvgVJrBSAjMh8vEzY9qXQE3.jpg', 'rooms2d/lTFpGD155zmC1RkP75n9uz78gRrDJS2AUmjvtre9.png', 'rooms2d/gmCyoMWVj9ytVzaUvGAqeg9BDblvIOzkxp3W20Qp.jpg', 'rooms2d/Z3tYuGSemB50Mo4DNNazKbITbIb8femJsuMWhD48.jpg', '[{\"0\":\"1\",\"1\":\"-37.8\",\"2\":\"-3.4\",\"3\":\"0\",\"4\":\"0\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"26\",\"10\":\"26\",\"11\":[[\"0\",\"553\"],[\"1600\",\"548\"],[\"1600\",\"0\"],[\"0\",\"0\"]],\"176\":\"wall\",\"group\":\"1\",\"cameraFov\":\"40\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"},{\"0\":\"2\",\"1\":\"-38.1\",\"2\":\"-4\",\"3\":\"0\",\"4\":\"-76.7\",\"5\":\"0\",\"6\":\"0\",\"7\":\"5000\",\"8\":\"5000\",\"9\":\"26\",\"10\":\"26\",\"11\":[[\"0\",\"548\"],[\"1600\",\"550\"],[\"1600\",\"999\"],[\"0\",\"999\"]],\"176\":\"floor\",\"group\":\"2\",\"cameraFov\":\"40\",\"viewVerticalOffset\":\"0\",\"viewHorizontalOffset\":\"0\"}]', 1, '2024-06-03 01:16:38', '2024-08-15 23:34:07');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `name`, `display_name`, `enabled`, `created_at`, `updated_at`) VALUES
(1, 'bedroom', 'Bedroom', 1, '2017-09-12 18:26:16', '2017-09-12 18:26:16'),
(2, 'livingroom', 'Livingroom', 1, '2017-09-12 18:26:31', '2017-09-12 18:26:31'),
(3, 'kitchen', 'Kitchen', 1, '2017-09-12 18:26:38', '2017-09-12 18:26:38'),
(4, 'bathroom', 'Bathroom', 1, '2017-09-12 18:26:45', '2017-09-12 18:26:45'),
(5, 'hall', 'Hall', 1, '2017-09-12 18:26:52', '2017-09-12 18:26:52'),
(6, 'balcony', 'Balcony', 1, '2017-09-12 18:27:00', '2017-09-12 18:27:00'),
(7, 'outdoor', 'Outdoor', 1, '2017-09-12 18:27:31', '2017-09-12 18:27:31'),
(9, 'other', 'Other', 1, '2017-09-16 06:13:24', '2017-09-16 06:13:24'),
(12, 'dressing-room', 'Dressing-Room', 1, '2024-05-15 05:05:12', '2024-05-15 05:05:12'),
(11, 'dinning', 'Dinning', 1, '2024-05-14 06:09:34', '2024-05-14 06:09:34'),
(13, 'commercial', 'Commercial', 1, '2024-07-03 02:08:24', '2024-07-03 02:08:24');

-- --------------------------------------------------------

--
-- Table structure for table `savedrooms`
--

CREATE TABLE `savedrooms` (
  `id` int(10) UNSIGNED NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `roomid` int(11) NOT NULL,
  `engine` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `roomsettings` text COLLATE utf8mb4_unicode_ci,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `session_token` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `savedrooms`
--

INSERT INTO `savedrooms` (`id`, `userid`, `roomid`, `engine`, `url`, `image`, `note`, `roomsettings`, `enabled`, `session_token`, `created_at`, `updated_at`) VALUES
(3, 1, 1, '3d', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', NULL, NULL, '{\"ceilingColor\":\"#ffffff\",\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":24,\"groutColor\":\"#a0a0a0\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":15},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":\"135\",\"fillTypeIndex\":0,\"tileId\":124},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":\"135\",\"fillTypeIndex\":0,\"tileId\":16},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":122},{\"color\":\"#ffffff\",\"groutSize\":6,\"groutColor\":\"#ff1117\",\"rotation\":0,\"fillTypeIndex\":\"2\",\"tileId\":120}]}', 1, NULL, '2017-03-14 11:33:34', '2017-03-15 15:32:01'),
(15, NULL, 1, '3d', '9bf31c7ff062936a96d3c8bd1f8f2ff3', NULL, NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4,\"rotation\":0,\"groutColor\":\"#ffffff\"},{\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"tileId\":16,\"groutSize\":4,\"rotation\":0,\"groutColor\":\"#ffffff\"},{\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4,\"rotation\":0,\"groutColor\":\"#ffffff\"},{\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4,\"rotation\":0,\"groutColor\":\"#ffffff\"},{\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"tileId\":26,\"groutSize\":4,\"rotation\":0,\"groutColor\":\"#ffffff\"}]}', 1, 'spZk345O034ShQvy6EyalTksSJYyLGOZyWttXCJe', '2017-03-16 08:14:23', '2017-03-16 08:14:23'),
(16, 1, 1, '3d', 'c74d97b01eae257e44aa9d5bade97baf', NULL, NULL, '{\"surfaces\":[{\"tileId\":15,\"fillTypeIndex\":0,\"groutSize\":4,\"rotation\":0,\"color\":\"#ffffff\",\"groutColor\":\"#ffffff\"},{\"fillTypeIndex\":0,\"groutSize\":4,\"rotation\":0,\"color\":\"#ffffff\",\"groutColor\":\"#ffffff\"},{\"tileId\":22,\"fillTypeIndex\":0,\"groutSize\":4,\"rotation\":0,\"color\":\"#ffffff\",\"groutColor\":\"#ffffff\"},{\"fillTypeIndex\":0,\"groutSize\":4,\"rotation\":0,\"color\":\"#ffffff\",\"groutColor\":\"#ffffff\"},{\"tileId\":27,\"fillTypeIndex\":0,\"groutSize\":4,\"rotation\":0,\"color\":\"#ffffff\",\"groutColor\":\"#ffffff\"}]}', 1, NULL, '2017-03-16 08:36:42', '2017-03-16 08:36:42'),
(17, NULL, 1, '3d', '70efdf2ec9b086079795c442636b55fb', NULL, NULL, '{\"surfaces\":[{\"tileId\":19,\"fillTypeIndex\":0,\"groutSize\":4,\"rotation\":0,\"color\":\"#ffffff\",\"groutColor\":\"#ffffff\"},{\"tileId\":19,\"fillTypeIndex\":0,\"groutSize\":4,\"rotation\":0,\"color\":\"#ffffff\",\"groutColor\":\"#ffffff\"},{\"fillTypeIndex\":0,\"groutSize\":4,\"rotation\":0,\"color\":\"#ffffff\",\"groutColor\":\"#ffffff\"},{\"fillTypeIndex\":0,\"groutSize\":4,\"rotation\":0,\"color\":\"#ffffff\",\"groutColor\":\"#ffffff\"},{\"tileId\":27,\"fillTypeIndex\":0,\"groutSize\":4,\"rotation\":0,\"color\":\"#ffffff\",\"groutColor\":\"#ffffff\"}]}', 1, 't23zJpsjiJ1xgnYaYuGsXes9Q72ZZbLcrRJvTM64', '2017-03-18 04:46:12', '2017-03-18 04:47:42'),
(25, 1, 1, '3d', '8e296a067a37563370ded05f5a3bf3ec', 'savedrooms/8e296a067a37563370ded05f5a3bf3ec.png', NULL, '{\"ceilingColor\":\"#ffffff\",\"surfaces\":[{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"tileId\":49,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"tileId\":82,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"tileId\":39,\"groutSize\":4}]}', 1, NULL, '2017-03-22 19:37:44', '2017-03-22 19:37:44'),
(26, NULL, 1, '3d', '4e732ced3463d06de0ca9a15b6153677', 'savedrooms/4e732ced3463d06de0ca9a15b6153677.png', NULL, '{\"surfaces\":[{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"tileId\":19,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4}]}', 1, 'AOdDO9P6NHnY1g1h3a5wHsWCtKH10ZWPA69Q0FRK', '2017-03-23 03:29:58', '2017-03-23 03:29:58'),
(27, NULL, 1, '3d', '02e74f10e0327ad868d138f2b4fdd6f0', 'savedrooms/02e74f10e0327ad868d138f2b4fdd6f0.png', NULL, '{\"surfaces\":[{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"tileId\":49,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[{\"yIndex\":9,\"id\":63,\"xIndex\":4},{\"yIndex\":8,\"id\":59,\"xIndex\":4},{\"yIndex\":10,\"id\":62,\"xIndex\":4},{\"yIndex\":9,\"id\":64,\"xIndex\":3},{\"yIndex\":10,\"id\":58,\"xIndex\":3},{\"yIndex\":8,\"id\":61,\"xIndex\":3},{\"yIndex\":6,\"id\":59,\"xIndex\":3},{\"yIndex\":7,\"id\":64,\"xIndex\":3},{\"yIndex\":7,\"id\":63,\"xIndex\":4},{\"yIndex\":5,\"id\":57,\"xIndex\":4},{\"yIndex\":6,\"id\":62,\"xIndex\":4},{\"yIndex\":5,\"id\":60,\"xIndex\":3},{\"yIndex\":4,\"id\":62,\"xIndex\":3},{\"yIndex\":3,\"id\":57,\"xIndex\":3},{\"yIndex\":4,\"id\":63,\"xIndex\":4},{\"yIndex\":3,\"id\":64,\"xIndex\":4},{\"yIndex\":1,\"id\":59,\"xIndex\":4},{\"yIndex\":-1,\"id\":59,\"xIndex\":4},{\"yIndex\":0,\"id\":63,\"xIndex\":4},{\"yIndex\":2,\"id\":64,\"xIndex\":4},{\"yIndex\":1,\"id\":64,\"xIndex\":3},{\"yIndex\":-1,\"id\":60,\"xIndex\":3},{\"yIndex\":0,\"id\":58,\"xIndex\":3},{\"yIndex\":2,\"id\":59,\"xIndex\":3}],\"freeDesign\":true,\"groutColor\":\"#ffffff\",\"rotation\":0,\"tileId\":49,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"tileId\":49,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"tileId\":39,\"groutSize\":4}]}', 1, 'AOdDO9P6NHnY1g1h3a5wHsWCtKH10ZWPA69Q0FRK', '2017-03-23 03:32:47', '2017-03-23 03:32:47'),
(28, NULL, 1, '3d', '33e75ff09dd601bbe69f351039152189', 'savedrooms/33e75ff09dd601bbe69f351039152189.png', NULL, '{\"surfaces\":[{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"tileId\":49,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"tileId\":49,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"tileId\":19,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"tileId\":39,\"groutSize\":4}]}', 1, 'UfY397a7B7INZA8O3mECfdTHOgzd5ibt4Pyd2H91', '2017-03-29 07:55:41', '2017-03-29 07:55:41'),
(30, NULL, 1, '3d', '34173cb38f07f89ddbebc2ac9128303f', 'savedrooms/34173cb38f07f89ddbebc2ac9128303f.png', NULL, '{\"surfaces\":[{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"tileId\":37,\"groutSize\":4}]}', 1, '60hfRQTuDvA9ji4ewKgjAvlVtw4adsTfJEaL98xX', '2017-03-29 11:59:16', '2017-03-29 11:59:16'),
(31, NULL, 1, '3d', 'c16a5320fa475530d9583c34fd356ef5', 'savedrooms/c16a5320fa475530d9583c34fd356ef5.png', NULL, '{\"surfaces\":[{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"tileId\":38,\"groutSize\":4}]}', 1, '60hfRQTuDvA9ji4ewKgjAvlVtw4adsTfJEaL98xX', '2017-03-29 12:00:40', '2017-03-29 12:00:40'),
(32, 2, 1, '3d', '6364d3f0f495b6ab9dcf8d3b5c6e0b01', 'savedrooms/6364d3f0f495b6ab9dcf8d3b5c6e0b01.png', NULL, '{\"surfaces\":[{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4},{\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"freeDesignTiles\":[],\"groutColor\":\"#ffffff\",\"rotation\":0,\"groutSize\":4}]}', 1, NULL, '2017-03-31 10:09:27', '2017-03-31 10:09:36'),
(33, 5, 5, '3d', '182be0c5cdcd5072bb1864cdee4d3d6e', 'savedrooms/182be0c5cdcd5072bb1864cdee4d3d6e.png', NULL, '{\"surfaces\":[{\"groutSize\":4,\"groutColor\":\"#ffffff\",\"freeDesignTiles\":[],\"tileId\":159,\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"rotation\":0},{\"groutSize\":4,\"groutColor\":\"#ffffff\",\"freeDesignTiles\":[],\"tileId\":84,\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"rotation\":0},{\"groutSize\":4,\"groutColor\":\"#ffffff\",\"freeDesignTiles\":[],\"tileId\":49,\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"rotation\":0},{\"groutSize\":4,\"groutColor\":\"#ffffff\",\"freeDesignTiles\":[],\"tileId\":40,\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"rotation\":0}]}', 1, NULL, '2017-04-20 10:37:10', '2017-04-20 10:37:10'),
(34, NULL, 5, '3d', 'e369853df766fa44e1ed0ff613f563bd', 'savedrooms/e369853df766fa44e1ed0ff613f563bd.png', NULL, '{\"surfaces\":[{\"groutSize\":4,\"groutColor\":\"#ffffff\",\"freeDesignTiles\":[],\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"rotation\":0},{\"groutSize\":4,\"groutColor\":\"#ffffff\",\"freeDesignTiles\":[],\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"rotation\":0},{\"groutSize\":4,\"groutColor\":\"#ffffff\",\"freeDesignTiles\":[],\"tileId\":81,\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"rotation\":0},{\"groutSize\":4,\"groutColor\":\"#ffffff\",\"freeDesignTiles\":[],\"fillTypeIndex\":0,\"color\":\"#ffffff\",\"rotation\":0}]}', 1, 'KcFAeJnZlBlOmCkI3DBHtUKHmUmPixcx1PD98Rtc', '2017-04-20 10:38:39', '2017-04-20 10:38:39'),
(35, 5, 5, '3d', '1c383cd30b7c298ab50293adfecb7b18', 'savedrooms/1c383cd30b7c298ab50293adfecb7b18.png', NULL, '{\"surfaces\":[{\"groutColor\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4,\"color\":\"#ffffff\",\"rotation\":0,\"freeDesignTiles\":[],\"tileId\":87},{\"groutColor\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4,\"color\":\"#ffffff\",\"rotation\":0,\"freeDesignTiles\":[]},{\"groutColor\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4,\"color\":\"#ffffff\",\"rotation\":0,\"freeDesignTiles\":[],\"tileId\":49},{\"groutColor\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4,\"color\":\"#ffffff\",\"rotation\":0,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-05-12 18:20:01', '2017-05-12 18:20:57'),
(36, NULL, 9, '3d', '19ca14e7ea6328a42e0eb13d585e4c22', 'savedrooms/19ca14e7ea6328a42e0eb13d585e4c22.png', NULL, '{\"surfaces\":[{\"groutColor\":\"#ff3d45\",\"fillTypeIndex\":0,\"groutSize\":10,\"color\":\"#ffffff\",\"rotation\":135,\"freeDesignTiles\":[],\"tileId\":19},{\"groutColor\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4,\"color\":\"#ffffff\",\"rotation\":0,\"freeDesignTiles\":[]},{\"groutColor\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4,\"color\":\"#ffffff\",\"rotation\":0,\"freeDesignTiles\":[],\"tileId\":19},{\"groutColor\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4,\"color\":\"#ffffff\",\"rotation\":0,\"freeDesignTiles\":[],\"tileId\":195}]}', 1, 'IZvl5f0rdACpHveFQLMhPGma9dk8D90H4tBgO76n', '2017-05-15 17:32:57', '2017-05-15 17:32:57'),
(37, 6, 5, '3d', 'a5bfc9e07964f8dddeb95fc584cd965d', 'savedrooms/a5bfc9e07964f8dddeb95fc584cd965d.png', NULL, '{\"surfaces\":[{\"freeDesignTiles\":[],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4},{\"freeDesignTiles\":[],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4},{\"tileId\":282,\"freeDesignTiles\":[],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4},{\"tileId\":108,\"freeDesignTiles\":[],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4}]}', 1, NULL, '2017-06-15 06:59:05', '2017-06-15 06:59:05'),
(38, NULL, 5, '3d', 'a5771bce93e200c36f7cd9dfd0e5deaa', 'savedrooms/a5771bce93e200c36f7cd9dfd0e5deaa.png', NULL, '{\"surfaces\":[{\"freeDesignTiles\":[],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4},{\"tileId\":283,\"freeDesignTiles\":[],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4},{\"tileId\":348,\"freeDesignTiles\":[],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":0},{\"tileId\":278,\"freeDesignTiles\":[],\"rotation\":45,\"groutColor\":\"#ebf1fc\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":3,\"freeDesign\":false}]}', 1, 'XTddfs9gUtIFSzR7zmoNu18H9RfcZKQkT24lpYeZ', '2017-06-15 09:25:26', '2017-06-15 09:25:26'),
(39, 6, 5, '3d', 'd67d8ab4f4c10bf22aa353e27879133c', 'savedrooms/d67d8ab4f4c10bf22aa353e27879133c.png', NULL, '{\"surfaces\":[{\"freeDesignTiles\":[],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4},{\"freeDesignTiles\":[],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4},{\"freeDesignTiles\":[],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4},{\"tileId\":434,\"freeDesignTiles\":[],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":1}]}', 1, NULL, '2017-06-22 07:23:59', '2017-06-22 07:23:59'),
(40, 6, 2, '3d', 'd645920e395fedad7bbbed0eca3fe2e0', 'savedrooms/d645920e395fedad7bbbed0eca3fe2e0.png', NULL, '{\"surfaces\":[{\"tileId\":428,\"freeDesignTiles\":[],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":2},{\"tileId\":428,\"freeDesignTiles\":[],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":2},{\"tileId\":428,\"freeDesignTiles\":[{\"id\":429,\"xIndex\":2,\"yIndex\":0},{\"id\":429,\"xIndex\":3,\"yIndex\":0},{\"id\":429,\"xIndex\":4,\"yIndex\":0},{\"id\":429,\"xIndex\":5,\"yIndex\":0},{\"id\":429,\"xIndex\":5,\"yIndex\":1},{\"id\":429,\"xIndex\":5,\"yIndex\":2},{\"id\":429,\"xIndex\":4,\"yIndex\":2},{\"id\":429,\"xIndex\":4,\"yIndex\":1},{\"id\":429,\"xIndex\":3,\"yIndex\":1},{\"id\":429,\"xIndex\":2,\"yIndex\":1},{\"id\":429,\"xIndex\":2,\"yIndex\":2},{\"id\":429,\"xIndex\":3,\"yIndex\":2},{\"id\":429,\"xIndex\":2,\"yIndex\":3},{\"id\":429,\"xIndex\":2,\"yIndex\":4},{\"id\":429,\"xIndex\":2,\"yIndex\":5},{\"id\":429,\"xIndex\":3,\"yIndex\":5},{\"id\":429,\"xIndex\":4,\"yIndex\":5},{\"id\":429,\"xIndex\":5,\"yIndex\":4},{\"id\":429,\"xIndex\":5,\"yIndex\":3},{\"id\":429,\"xIndex\":2,\"yIndex\":6},{\"id\":429,\"xIndex\":4,\"yIndex\":6},{\"id\":429,\"xIndex\":3,\"yIndex\":6},{\"id\":429,\"xIndex\":5,\"yIndex\":6},{\"id\":429,\"xIndex\":5,\"yIndex\":8},{\"id\":429,\"xIndex\":2,\"yIndex\":8},{\"id\":429,\"xIndex\":2,\"yIndex\":7},{\"id\":429,\"xIndex\":5,\"yIndex\":5},{\"id\":429,\"xIndex\":5,\"yIndex\":7}],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":2,\"freeDesign\":true},{\"tileId\":428,\"freeDesignTiles\":[{\"id\":429,\"xIndex\":2,\"yIndex\":7},{\"id\":429,\"xIndex\":2,\"yIndex\":5},{\"id\":429,\"xIndex\":2,\"yIndex\":4},{\"id\":429,\"xIndex\":2,\"yIndex\":6},{\"id\":429,\"xIndex\":2,\"yIndex\":3},{\"id\":429,\"xIndex\":2,\"yIndex\":2},{\"id\":429,\"xIndex\":2,\"yIndex\":1},{\"id\":429,\"xIndex\":2,\"yIndex\":0}],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":2,\"freeDesign\":true},{\"tileId\":417,\"freeDesignTiles\":[],\"rotation\":0,\"groutColor\":\"#ffffff\",\"color\":\"#ffffff\",\"fillTypeIndex\":0,\"groutSize\":4}],\"ceilingColor\":\"#ffffff\"}', 1, NULL, '2017-06-22 07:31:01', '2017-06-22 07:36:45'),
(54, 1, 8, '2d', 'a684eceee76fc522773286a895bc8436', 'savedrooms/a684eceee76fc522773286a895bc8436.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":282,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":65,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-09-12 21:20:14', '2017-09-12 21:20:14'),
(66, 9, 43, '2d', '3295c76acbf4caaed33c36b1b5fc2cb1', 'savedrooms/3295c76acbf4caaed33c36b1b5fc2cb1.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":284,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":284,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":107,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":284,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-09-24 18:48:52', '2017-09-24 18:48:52'),
(67, NULL, 1, '3d', '735b90b4568125ed6c3f678819b6e058', 'savedrooms/735b90b4568125ed6c3f678819b6e058.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":360,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":45,\"fillTypeIndex\":0,\"tileId\":378,\"freeDesignTiles\":[]}]}', 1, 'AHwiDECt5chz9Oy0V7JatMjYDIolPMnAGvZZSRsd', '2017-09-25 18:49:55', '2017-09-25 18:49:55'),
(68, 7, 5, '3d', 'a3f390d88e4c41f2747bfa2f1b5f87db', 'savedrooms/a3f390d88e4c41f2747bfa2f1b5f87db.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":295,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-09-27 03:54:58', '2017-09-27 03:54:58'),
(69, 12, 5, '3d', '14bfa6bb14875e45bba028a21ed38046', 'savedrooms/14bfa6bb14875e45bba028a21ed38046.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":284,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":104,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-09-27 04:07:11', '2017-09-27 04:08:11'),
(71, NULL, 7, '3d', 'e2c420d928d4bf8ce0ff2ec19b371514', 'savedrooms/e2c420d928d4bf8ce0ff2ec19b371514.png', NULL, '{\"ceilingColor\":\"#ffffff\",\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":341,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":295,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":341,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":341,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":117,\"freeDesignTiles\":[]}]}', 1, '0FS7UBZ3C8ycUgKFQo2T9kP3kBKP0uqkKlXbrqRm', '2017-09-30 11:48:01', '2017-09-30 11:49:15'),
(73, 10, 13, '2d', 'd2ddea18f00665ce8623e36bd4e3c7c5', 'savedrooms/d2ddea18f00665ce8623e36bd4e3c7c5.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":3,\"groutColor\":\"#f8f8f8\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":284,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":104,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":295,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":340,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-10-05 05:13:48', '2017-10-05 05:14:15'),
(74, 10, 25, '2d', 'ad61ab143223efbc24c7d2583be69251', 'savedrooms/ad61ab143223efbc24c7d2583be69251.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":111,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#eaeaff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":49,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-10-10 15:52:32', '2017-10-10 15:52:44'),
(75, 10, 13, '2d', 'd09bf41544a3365a46c9077ebb5e35c3', 'savedrooms/d09bf41544a3365a46c9077ebb5e35c3.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":284,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":104,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":295,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":340,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-10-20 14:02:03', '2017-10-20 14:02:19'),
(83, 10, 64, 'panorama', 'fe9fc289c3ff0af142b6d3bead98a923', 'savedrooms/fe9fc289c3ff0af142b6d3bead98a923.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":7,\"groutColor\":\"#bbbbbb\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":3,\"tileId\":546,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-10-30 12:19:47', '2017-10-30 12:20:04'),
(84, 10, 64, 'panorama', '68d30a9594728bc39aa24be94b319d21', 'savedrooms/68d30a9594728bc39aa24be94b319d21.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesign\":false,\"freeDesignTiles\":[{\"id\":666,\"xIndex\":3,\"yIndex\":2},{\"id\":666,\"xIndex\":3,\"yIndex\":3},{\"id\":666,\"xIndex\":3,\"yIndex\":4},{\"id\":666,\"xIndex\":3,\"yIndex\":5},{\"id\":666,\"xIndex\":3,\"yIndex\":6},{\"id\":666,\"xIndex\":3,\"yIndex\":7},{\"id\":666,\"xIndex\":4,\"yIndex\":7},{\"id\":666,\"xIndex\":4,\"yIndex\":6},{\"id\":666,\"xIndex\":4,\"yIndex\":5},{\"id\":666,\"xIndex\":4,\"yIndex\":4},{\"id\":666,\"xIndex\":4,\"yIndex\":3},{\"id\":666,\"xIndex\":4,\"yIndex\":2}]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#9f9f9f\",\"rotation\":0,\"fillTypeIndex\":3,\"tileId\":546,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-10-30 12:35:54', '2017-10-30 12:36:10'),
(85, 7, 64, 'panorama', '3ef815416f775098fe977004015c6193', 'savedrooms/3ef815416f775098fe977004015c6193.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":3,\"tileId\":784,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-10-30 12:48:34', '2017-10-30 12:48:34'),
(87, 10, 2, '3d', 'c7e1249ffc03eb9ded908c236bd1996d', 'savedrooms/c7e1249ffc03eb9ded908c236bd1996d.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesign\":true,\"freeDesignTiles\":[{\"id\":666,\"xIndex\":3,\"yIndex\":6},{\"id\":666,\"xIndex\":4,\"yIndex\":6},{\"id\":666,\"xIndex\":4,\"yIndex\":5},{\"id\":666,\"xIndex\":3,\"yIndex\":5},{\"id\":666,\"xIndex\":3,\"yIndex\":4},{\"id\":666,\"xIndex\":3,\"yIndex\":2},{\"id\":666,\"xIndex\":3,\"yIndex\":1},{\"id\":666,\"xIndex\":4,\"yIndex\":0},{\"id\":666,\"xIndex\":3,\"yIndex\":0},{\"id\":666,\"xIndex\":4,\"yIndex\":2},{\"id\":666,\"xIndex\":4,\"yIndex\":1},{\"id\":666,\"xIndex\":4,\"yIndex\":3},{\"id\":666,\"xIndex\":4,\"yIndex\":4}]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-11-14 03:24:53', '2017-11-14 03:25:00'),
(88, 10, 8, '3d', '2a38a4a9316c49e5a833517c45d31070', 'savedrooms/2a38a4a9316c49e5a833517c45d31070.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesign\":true,\"freeDesignTiles\":[{\"id\":666,\"xIndex\":4,\"yIndex\":7},{\"id\":666,\"xIndex\":3,\"yIndex\":7},{\"id\":666,\"xIndex\":3,\"yIndex\":6},{\"id\":666,\"xIndex\":3,\"yIndex\":4},{\"id\":666,\"xIndex\":3,\"yIndex\":5},{\"id\":666,\"xIndex\":3,\"yIndex\":3},{\"id\":666,\"xIndex\":3,\"yIndex\":2},{\"id\":666,\"xIndex\":4,\"yIndex\":2},{\"id\":666,\"xIndex\":4,\"yIndex\":3},{\"id\":666,\"xIndex\":4,\"yIndex\":4},{\"id\":666,\"xIndex\":4,\"yIndex\":5},{\"id\":666,\"xIndex\":4,\"yIndex\":6},{\"id\":666,\"xIndex\":3,\"yIndex\":1},{\"id\":666,\"xIndex\":3,\"yIndex\":0},{\"id\":666,\"xIndex\":4,\"yIndex\":0},{\"id\":666,\"xIndex\":4,\"yIndex\":1}]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesign\":true,\"freeDesignTiles\":[{\"id\":666,\"xIndex\":2,\"yIndex\":8},{\"id\":666,\"xIndex\":3,\"yIndex\":8},{\"id\":666,\"xIndex\":3,\"yIndex\":7},{\"id\":666,\"xIndex\":2,\"yIndex\":7},{\"id\":666,\"xIndex\":2,\"yIndex\":6},{\"id\":666,\"xIndex\":2,\"yIndex\":4},{\"id\":666,\"xIndex\":2,\"yIndex\":5},{\"id\":666,\"xIndex\":2,\"yIndex\":3},{\"id\":666,\"xIndex\":2,\"yIndex\":2},{\"id\":666,\"xIndex\":2,\"yIndex\":1},{\"id\":666,\"xIndex\":2,\"yIndex\":0},{\"id\":666,\"xIndex\":3,\"yIndex\":0},{\"id\":666,\"xIndex\":3,\"yIndex\":1},{\"id\":666,\"xIndex\":3,\"yIndex\":2},{\"id\":666,\"xIndex\":3,\"yIndex\":3},{\"id\":666,\"xIndex\":3,\"yIndex\":4},{\"id\":666,\"xIndex\":3,\"yIndex\":5},{\"id\":666,\"xIndex\":3,\"yIndex\":6}]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-11-14 03:30:48', '2017-11-14 03:30:48'),
(89, 10, 5, '3d', '7647966b7343c29048673252e490f736', 'savedrooms/7647966b7343c29048673252e490f736.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-11-14 03:42:43', '2017-11-14 03:44:37'),
(90, 10, 20, '2d', '8613985ec49eb8f757ae6439e879bb2a', 'savedrooms/8613985ec49eb8f757ae6439e879bb2a.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesign\":true,\"freeDesignTiles\":[{\"id\":666,\"xIndex\":1,\"yIndex\":0},{\"id\":666,\"xIndex\":2,\"yIndex\":0},{\"id\":666,\"xIndex\":2,\"yIndex\":1},{\"id\":666,\"xIndex\":1,\"yIndex\":1},{\"id\":666,\"xIndex\":1,\"yIndex\":2},{\"id\":666,\"xIndex\":1,\"yIndex\":3},{\"id\":666,\"xIndex\":2,\"yIndex\":3},{\"id\":666,\"xIndex\":2,\"yIndex\":2},{\"id\":666,\"xIndex\":1,\"yIndex\":4},{\"id\":666,\"xIndex\":1,\"yIndex\":5},{\"id\":666,\"xIndex\":2,\"yIndex\":5},{\"id\":666,\"xIndex\":2,\"yIndex\":4},{\"id\":666,\"xIndex\":1,\"yIndex\":6},{\"id\":666,\"xIndex\":2,\"yIndex\":6},{\"id\":666,\"xIndex\":1,\"yIndex\":7},{\"id\":666,\"xIndex\":2,\"yIndex\":7},{\"id\":666,\"xIndex\":3,\"yIndex\":4},{\"id\":666,\"xIndex\":3,\"yIndex\":3},{\"id\":666,\"xIndex\":0,\"yIndex\":4},{\"id\":666,\"xIndex\":0,\"yIndex\":3}]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":574,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-11-14 07:25:19', '2017-11-14 07:25:26'),
(91, 10, 20, '2d', '54229abfcfa5649e7003b83dd4755294', 'savedrooms/54229abfcfa5649e7003b83dd4755294.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":3,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":2,\"tileId\":665,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":665,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":5,\"groutColor\":\"#ffffff\",\"rotation\":180,\"fillTypeIndex\":3,\"tileId\":519,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2017-11-14 11:09:00', '2017-11-14 11:09:21'),
(96, NULL, 21, '2d', '26657d5ff9020d2abefe558796b99584', 'savedrooms/26657d5ff9020d2abefe558796b99584.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":361,\"freeDesign\":false,\"freeDesignTiles\":[{\"id\":656,\"xIndex\":0,\"yIndex\":8},{\"id\":656,\"xIndex\":1,\"yIndex\":8},{\"id\":656,\"xIndex\":3,\"yIndex\":8},{\"id\":656,\"xIndex\":2,\"yIndex\":8},{\"id\":656,\"xIndex\":4,\"yIndex\":8},{\"id\":656,\"xIndex\":5,\"yIndex\":8},{\"id\":656,\"xIndex\":6,\"yIndex\":8},{\"id\":656,\"xIndex\":7,\"yIndex\":8},{\"id\":656,\"xIndex\":8,\"yIndex\":8},{\"id\":656,\"xIndex\":9,\"yIndex\":8},{\"id\":656,\"xIndex\":10,\"yIndex\":8},{\"id\":656,\"xIndex\":11,\"yIndex\":8},{\"id\":656,\"xIndex\":12,\"yIndex\":8},{\"id\":656,\"xIndex\":13,\"yIndex\":8},{\"id\":656,\"xIndex\":6,\"yIndex\":12},{\"id\":656,\"xIndex\":6,\"yIndex\":10},{\"id\":656,\"xIndex\":9,\"yIndex\":10}]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":586,\"freeDesign\":true,\"freeDesignTiles\":[{\"id\":677,\"xIndex\":2,\"yIndex\":-5},{\"id\":707,\"xIndex\":4,\"yIndex\":-6},{\"id\":705,\"xIndex\":4,\"yIndex\":-4},{\"id\":706,\"xIndex\":1,\"yIndex\":-3}]}]}', 1, 'roVxsVFbwmuDmeB0jn3fAzqrleU4rCyY6XMw1SCc', '2018-01-04 17:28:55', '2018-01-04 17:28:55'),
(97, NULL, 13, '2d', 'e2ef524fbf3d9fe611d5a8e90fefdc9c', 'savedrooms/e2ef524fbf3d9fe611d5a8e90fefdc9c.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":368,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":617,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":368,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":368,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, '6kALtaDedbpz5fvITNMcQ8clSCYSQOwuOBlOqPqa', '2018-01-05 17:19:20', '2018-01-05 17:19:20'),
(104, 7, 22, '2d', 'c9e1074f5b3f9fc8ea15d152add07294', 'savedrooms/c9e1074f5b3f9fc8ea15d152add07294.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":927,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2018-04-12 08:15:20', '2018-04-12 08:15:20'),
(105, NULL, 20, '2d', '65b9eea6e1cc6bb9f0cd2a47751a186f', 'savedrooms/65b9eea6e1cc6bb9f0cd2a47751a186f.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":656,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":478,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":912,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, 'y7ikXvJjwmDW29wmH9M2i9cHZo9BRT1FhgPPFwCl', '2018-04-14 07:53:32', '2018-04-14 07:53:32'),
(109, 20, 20, '2d', '2723d092b63885e0d7c260cc007e8b9d', 'savedrooms/2723d092b63885e0d7c260cc007e8b9d.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":640,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":259,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2018-06-04 09:42:57', '2018-06-04 09:42:57'),
(112, NULL, 2, '3d', '7f6ffaa6bb0b408017b62254211691b5', 'savedrooms/7f6ffaa6bb0b408017b62254211691b5.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":669,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":978,\"freeDesign\":true,\"freeDesignTiles\":[{\"id\":669,\"xIndex\":1,\"yIndex\":8},{\"id\":669,\"xIndex\":0,\"yIndex\":8},{\"id\":669,\"xIndex\":0,\"yIndex\":7},{\"id\":669,\"xIndex\":1,\"yIndex\":7},{\"id\":669,\"xIndex\":0,\"yIndex\":6},{\"id\":669,\"xIndex\":0,\"yIndex\":5},{\"id\":669,\"xIndex\":1,\"yIndex\":5},{\"id\":669,\"xIndex\":2,\"yIndex\":5},{\"id\":669,\"xIndex\":3,\"yIndex\":5},{\"id\":669,\"xIndex\":4,\"yIndex\":5},{\"id\":669,\"xIndex\":4,\"yIndex\":4},{\"id\":669,\"xIndex\":3,\"yIndex\":4},{\"id\":669,\"xIndex\":1,\"yIndex\":4},{\"id\":669,\"xIndex\":2,\"yIndex\":4},{\"id\":669,\"xIndex\":2,\"yIndex\":3},{\"id\":669,\"xIndex\":2,\"yIndex\":2},{\"id\":669,\"xIndex\":2,\"yIndex\":1},{\"id\":669,\"xIndex\":2,\"yIndex\":0},{\"id\":669,\"xIndex\":3,\"yIndex\":0},{\"id\":669,\"xIndex\":4,\"yIndex\":0},{\"id\":669,\"xIndex\":4,\"yIndex\":1},{\"id\":669,\"xIndex\":3,\"yIndex\":1},{\"id\":669,\"xIndex\":3,\"yIndex\":2},{\"id\":669,\"xIndex\":4,\"yIndex\":2},{\"id\":669,\"xIndex\":3,\"yIndex\":3},{\"id\":669,\"xIndex\":4,\"yIndex\":3},{\"id\":669,\"xIndex\":1,\"yIndex\":3},{\"id\":669,\"xIndex\":1,\"yIndex\":2},{\"id\":669,\"xIndex\":1,\"yIndex\":1},{\"id\":669,\"xIndex\":1,\"yIndex\":0},{\"id\":669,\"xIndex\":0,\"yIndex\":0},{\"id\":669,\"xIndex\":0,\"yIndex\":1},{\"id\":669,\"xIndex\":0,\"yIndex\":2},{\"id\":669,\"xIndex\":0,\"yIndex\":3},{\"id\":669,\"xIndex\":0,\"yIndex\":4},{\"id\":669,\"xIndex\":1,\"yIndex\":6},{\"id\":669,\"xIndex\":2,\"yIndex\":6},{\"id\":669,\"xIndex\":3,\"yIndex\":6},{\"id\":669,\"xIndex\":4,\"yIndex\":6},{\"id\":669,\"xIndex\":4,\"yIndex\":7},{\"id\":669,\"xIndex\":3,\"yIndex\":7},{\"id\":669,\"xIndex\":2,\"yIndex\":7},{\"id\":669,\"xIndex\":2,\"yIndex\":8},{\"id\":669,\"xIndex\":3,\"yIndex\":8},{\"id\":669,\"xIndex\":4,\"yIndex\":8}]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesign\":true,\"freeDesignTiles\":[{\"id\":978,\"xIndex\":0,\"yIndex\":0},{\"id\":978,\"xIndex\":1,\"yIndex\":0},{\"id\":978,\"xIndex\":0,\"yIndex\":1},{\"id\":978,\"xIndex\":1,\"yIndex\":1},{\"id\":978,\"xIndex\":0,\"yIndex\":2},{\"id\":978,\"xIndex\":1,\"yIndex\":2},{\"id\":978,\"xIndex\":0,\"yIndex\":3},{\"id\":978,\"xIndex\":1,\"yIndex\":3},{\"id\":978,\"xIndex\":0,\"yIndex\":4},{\"id\":978,\"xIndex\":1,\"yIndex\":4},{\"id\":978,\"xIndex\":0,\"yIndex\":6},{\"id\":978,\"xIndex\":0,\"yIndex\":5},{\"id\":978,\"xIndex\":0,\"yIndex\":7},{\"id\":978,\"xIndex\":0,\"yIndex\":8},{\"id\":978,\"xIndex\":1,\"yIndex\":5},{\"id\":978,\"xIndex\":1,\"yIndex\":6},{\"id\":978,\"xIndex\":1,\"yIndex\":7},{\"id\":669,\"xIndex\":2,\"yIndex\":1},{\"id\":669,\"xIndex\":2,\"yIndex\":0},{\"id\":669,\"xIndex\":2,\"yIndex\":2},{\"id\":669,\"xIndex\":2,\"yIndex\":3},{\"id\":669,\"xIndex\":2,\"yIndex\":4},{\"id\":669,\"xIndex\":2,\"yIndex\":5},{\"id\":669,\"xIndex\":2,\"yIndex\":6},{\"id\":669,\"xIndex\":3,\"yIndex\":0},{\"id\":669,\"xIndex\":3,\"yIndex\":2},{\"id\":669,\"xIndex\":3,\"yIndex\":1},{\"id\":669,\"xIndex\":3,\"yIndex\":5},{\"id\":669,\"xIndex\":3,\"yIndex\":6},{\"id\":669,\"xIndex\":4,\"yIndex\":1},{\"id\":669,\"xIndex\":4,\"yIndex\":0},{\"id\":669,\"xIndex\":5,\"yIndex\":0},{\"id\":669,\"xIndex\":6,\"yIndex\":0},{\"id\":669,\"xIndex\":5,\"yIndex\":1},{\"id\":669,\"xIndex\":4,\"yIndex\":2},{\"id\":669,\"xIndex\":4,\"yIndex\":5},{\"id\":669,\"xIndex\":4,\"yIndex\":6},{\"id\":669,\"xIndex\":5,\"yIndex\":6},{\"id\":669,\"xIndex\":5,\"yIndex\":5},{\"id\":669,\"xIndex\":5,\"yIndex\":4},{\"id\":669,\"xIndex\":5,\"yIndex\":3},{\"id\":669,\"xIndex\":5,\"yIndex\":2},{\"id\":669,\"xIndex\":6,\"yIndex\":1},{\"id\":669,\"xIndex\":6,\"yIndex\":2},{\"id\":669,\"xIndex\":6,\"yIndex\":3},{\"id\":669,\"xIndex\":6,\"yIndex\":4},{\"id\":669,\"xIndex\":6,\"yIndex\":5},{\"id\":669,\"xIndex\":6,\"yIndex\":6},{\"id\":669,\"xIndex\":6,\"yIndex\":7},{\"id\":669,\"xIndex\":5,\"yIndex\":7},{\"id\":669,\"xIndex\":5,\"yIndex\":8}]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":669,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesign\":true,\"freeDesignTiles\":[]}]}', 1, '0aQPQ3MTGtTXdXRfRy7yXSOyEKo64LjggWHI5FzG', '2018-06-26 06:27:28', '2018-06-26 06:27:28'),
(117, 26, 20, '2d', 'eb160de1de89d9058fcb0b968dbbbd68', 'savedrooms/eb160de1de89d9058fcb0b968dbbbd68.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":656,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":696,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2018-08-29 08:27:10', '2018-08-29 08:28:18'),
(118, 27, 11, '2d', '5ef059938ba799aaa845e1c2e8a762bd', 'savedrooms/5ef059938ba799aaa845e1c2e8a762bd.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":1,\"tileId\":617,\"skewSize\":0.25,\"freeDesign\":true,\"freeDesignTiles\":[{\"id\":561,\"xIndex\":4,\"yIndex\":1},{\"id\":558,\"xIndex\":4,\"yIndex\":3},{\"id\":555,\"xIndex\":5,\"yIndex\":2},{\"id\":557,\"xIndex\":5,\"yIndex\":0},{\"id\":557,\"xIndex\":2,\"yIndex\":3},{\"id\":557,\"xIndex\":1,\"yIndex\":2},{\"id\":554,\"xIndex\":2,\"yIndex\":1},{\"id\":559,\"xIndex\":0,\"yIndex\":1},{\"id\":560,\"xIndex\":3,\"yIndex\":2}]}]}', 1, NULL, '2018-09-03 10:24:41', '2018-09-03 10:25:27'),
(119, NULL, 20, '2d', '07e1cd7dca89a1678042477183b7ac3f', 'savedrooms/07e1cd7dca89a1678042477183b7ac3f.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#bfbfbf\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":374,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#bfbfbf\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":374,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#bfbfbf\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":374,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":377,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, 'Bskgn27g98y6GvO7Jx1ud7Koio2nXSyBhU2heJiH', '2018-09-05 16:39:22', '2018-09-05 16:39:22'),
(120, NULL, 8, '3d', 'da4fb5c6e93e74d3df8527599fa62642', 'savedrooms/da4fb5c6e93e74d3df8527599fa62642.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#c26f6f\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":1,\"tileId\":367,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":478,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":617,\"freeDesignTiles\":[]}]}', 1, 'hzTIJiMnTzOF0Pchj9s65ruoEUbTeulZLB8kMvK0', '2018-09-06 07:19:42', '2018-09-06 07:19:42'),
(121, 7, 13, '2d', '4c56ff4ce4aaf9573aa5dff913df997a', 'savedrooms/4c56ff4ce4aaf9573aa5dff913df997a.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":656,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":696,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2018-09-06 13:03:28', '2018-09-06 13:03:28'),
(133, 29, 13, '2d', '9fc3d7152ba9336a670e36d0ed79bc43', 'savedrooms/9fc3d7152ba9336a670e36d0ed79bc43.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":367,\"tileCustom\":false,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":562,\"tileCustom\":false,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":478,\"tileCustom\":false,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":478,\"tileCustom\":false,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2018-12-19 16:43:05', '2018-12-21 17:54:53'),
(145, NULL, 6, '2d', '2b24d495052a8ce66358eb576b8912c8', 'savedrooms/2b24d495052a8ce66358eb576b8912c8.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":1094,\"tileCustom\":false,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, '9mUtM6VOF2wfavlHzBZFRTzNvT8MNBpQgwTJp2Ma', '2019-07-11 08:51:10', '2019-07-11 08:51:10'),
(147, 7, 13, '2d', '8d5e957f297893487bd98fa830fa6413', 'savedrooms/8d5e957f297893487bd98fa830fa6413.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":0,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":367,\"tileCustom\":false,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":696,\"tileCustom\":false,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":656,\"tileCustom\":false,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":656,\"tileCustom\":false,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2019-07-21 14:15:12', '2019-07-21 14:16:04');
INSERT INTO `savedrooms` (`id`, `userid`, `roomid`, `engine`, `url`, `image`, `note`, `roomsettings`, `enabled`, `session_token`, `created_at`, `updated_at`) VALUES
(148, 7, 13, '2d', '47d1e990583c9c67424d369f3414728e', 'savedrooms/47d1e990583c9c67424d369f3414728e.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#645050\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":663,\"tileCustom\":false,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":696,\"tileCustom\":false,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":656,\"tileCustom\":false,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":2,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"tileId\":656,\"tileCustom\":false,\"skewSize\":0.25,\"freeDesign\":false,\"freeDesignTiles\":[]}]}', 1, NULL, '2019-07-25 20:03:36', '2019-07-25 20:03:36'),
(150, NULL, 1, '3d', '7ef605fc8dba5425d6965fbd4c8fbe1f', 'savedrooms/7ef605fc8dba5425d6965fbd4c8fbe1f.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]}]}', 1, 'IjX7MWbTgJbb0jOAnsRBqHGe8RuzgPnd2dpXLGWi', '2019-08-21 10:04:59', '2019-08-21 10:04:59'),
(152, NULL, 8, '3d', '37a749d808e46495a8da1e5352d03cae', 'savedrooms/37a749d808e46495a8da1e5352d03cae.png', NULL, '{\"surfaces\":[{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]},{\"color\":\"#ffffff\",\"groutSize\":4,\"groutColor\":\"#ffffff\",\"rotation\":0,\"fillTypeIndex\":0,\"freeDesignTiles\":[]}]}', 1, 'F5H3StxIrSg77wOnYvrWNBfvIxPtav37hWXGtEXU', '2019-09-30 07:39:34', '2019-09-30 07:39:34');

-- --------------------------------------------------------

--
-- Table structure for table `surface_types`
--

CREATE TABLE `surface_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surface_types`
--

INSERT INTO `surface_types` (`id`, `name`, `display_name`, `enabled`, `created_at`, `updated_at`) VALUES
(1, 'wall', 'Wall', 1, '2017-09-12 18:25:07', '2017-09-12 18:25:07'),
(2, 'floor', 'Floor', 1, '2017-09-12 18:25:17', '2017-09-12 18:25:17'),
(3, 'furniture', 'Furniture', 1, '2017-09-12 18:25:44', '2017-09-12 18:25:44'),
(4, 'counter', 'Counter', 1, '2017-09-12 18:25:56', '2017-09-12 18:25:56');

-- --------------------------------------------------------

--
-- Table structure for table `tiles`
--

CREATE TABLE `tiles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shape` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'square',
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `surface` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `finish` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'glossy',
  `file` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grout` tinyint(1) DEFAULT '1',
  `url` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rotoPrintSetName` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expProps` text COLLATE utf8mb4_unicode_ci,
  `access_level` int(4) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tiles`
--

INSERT INTO `tiles` (`id`, `name`, `shape`, `width`, `height`, `surface`, `finish`, `file`, `grout`, `url`, `price`, `rotoPrintSetName`, `expProps`, `access_level`, `enabled`, `created_at`, `updated_at`) VALUES
(2826, 'CRYSTAL WHITE', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/yFp41CfhY8fpzPQ0CwjolI4pFCu5Dr4qyC0rXU5c.jpg', 1, NULL, NULL, 'CRYSTAL WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:30:41', '2024-09-03 23:30:45'),
(2827, 'CRYSTAL WHITE 31_R1', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/7JrmQlZjmPyZniEEwq3YAiPiQjiqJWXbtIxNlQZA.jpg', 1, NULL, NULL, 'CRYSTAL WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:30:42', '2024-09-03 23:30:45'),
(2828, 'CRYSTAL WHITE 31_R2', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/TNhl2ykFzAzbWZeYf2NVSx31eXM90D23kSI1GMtS.jpg', 1, NULL, NULL, 'CRYSTAL WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:30:42', '2024-09-03 23:30:45'),
(2829, 'CRYSTAL WHITE 31_R3 MASTER', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/y9lqclYnIvwWBJ5gjV003Y2yW0KefcCqzQ65NEaw.jpg', 1, NULL, NULL, 'CRYSTAL WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:30:42', '2024-09-03 23:30:46'),
(2830, 'GLIMAR WHITE', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/rMDr1yHiFSyZ0uvrZO0e4LebkMeEpwXRLBXHxcL5.jpg', 1, NULL, NULL, 'GLIMAR WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:31:11', '2024-09-03 23:31:26'),
(2831, 'GLIMAR WHITE_R2', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/Zev90xS4xPTVTrwDvYRdI2dybaEs6JJsbT3WBCfN.jpg', 1, NULL, NULL, 'GLIMAR WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:31:11', '2024-09-03 23:31:26'),
(2832, 'GLIMAR WHITE_R3', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/71xQDLoohI8NTejzzWn5eDFt2P5YLuHxYRS1vlUC.jpg', 1, NULL, NULL, 'GLIMAR WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:31:11', '2024-09-03 23:31:26'),
(2833, 'CM-015', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/DYUyz9By0EHwWCg4whzRo3VWHKMmp6oMe21zaSrs.jpg', 1, NULL, NULL, 'CM-015', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:31:48', '2024-09-03 23:32:09'),
(2834, 'CM-015_R2', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/3e16kYPLHB4xGVBmubIyUcW8NUly0dizJjKpFbAv.jpg', 1, NULL, NULL, 'CM-015', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:31:48', '2024-09-03 23:32:09'),
(2835, 'CM-015_R3', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/KE74P6224VegJQVUJUoYvcvFvTx5PlCKub1UFCDT.jpg', 1, NULL, NULL, 'CM-015', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:31:49', '2024-09-03 23:32:09'),
(2836, 'CM-015_R4', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/bKT7eyb5R2sEXEefqGFEw1hcPsBOcYNCDkQtaEqD.jpg', 1, NULL, NULL, 'CM-015', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:31:49', '2024-09-03 23:32:09'),
(2837, 'ODALISCA NATURAL', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/dhS7OQ3CKsVjVlmgmOr3dTJBlEFZMY3SM8UFKz0Y.jpg', 1, NULL, NULL, 'ODALISCA NATURAL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:33:02', '2024-09-03 23:33:06'),
(2838, 'ODALISCA NATURAL F2', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/o0MfffUK7tKSbYEu6cLKErVknWHOA8xOMIsT3p3c.jpg', 1, NULL, NULL, 'ODALISCA NATURAL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:33:02', '2024-09-03 23:33:06'),
(2839, 'ODALISCA NATURAL F3', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/6tCx2AwKmrm7isJ12PvfcbnKb6wc3tGTaR0zgHXu.jpg', 1, NULL, NULL, 'ODALISCA NATURAL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:33:03', '2024-09-03 23:33:07'),
(2840, 'ODALISCA NATURAL F4', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/R8HUKnrSEbGN75lHjqDZ8KCydQhS5YWnS3fhvuxb.jpg', 1, NULL, NULL, 'ODALISCA NATURAL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:33:03', '2024-09-03 23:33:07'),
(2841, 'ALP SATUARIO', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/OF88vpT3yHURXnyjNNCRQ2DI85EBI35FX2XA2yVa.jpg', 1, NULL, NULL, 'ALP SATUARIO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:33:25', '2024-09-03 23:33:29'),
(2842, 'ALP SATUARIO_R2', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/HCu1B4ZiivbMs2TG6GIMcC5MD0PLjMG96lEQ7ct0.jpg', 1, NULL, NULL, 'ALP SATUARIO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:33:25', '2024-09-03 23:33:29'),
(2843, 'ALP SATUARIO_R3', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/9aYHae9Z0j7L2pb4URw7Kry1jg1OsZ6yaPNYwVPX.jpg', 1, NULL, NULL, 'ALP SATUARIO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:33:26', '2024-09-03 23:33:29'),
(2844, 'ALP SATUARIO_R4', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/RBp79fA6xSHqPUJyIzcieXNe70oUUnCGiPA2xBxJ.jpg', 1, NULL, NULL, 'ALP SATUARIO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:33:26', '2024-09-03 23:33:29'),
(2845, 'ALP SATUARIO_R5', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/47JbLreLpxq1YRYCN12IMLAoXVnMdbYRJKV6f571.jpg', 1, NULL, NULL, 'ALP SATUARIO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:33:26', '2024-09-03 23:33:29'),
(2846, 'CARNELIA CREMA', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/IykBKqyQA6omZZUVru5NiL24OrNrVRXjxuTOxPVy.jpg', 1, NULL, NULL, 'CARNELIA CREMA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:33:56', '2024-09-03 23:33:59'),
(2847, 'CARNELIA CREMA_NEW PROFILE_P2', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/MgythBiBQLtBrizVOMKlXHhKN3tGy6Isw6LOrog3.jpg', 1, NULL, NULL, 'CARNELIA CREMA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:33:56', '2024-09-03 23:33:59'),
(2848, 'CARNELIA CREMA_NEW PROFILE_P3', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/MMS3HMjPx5tINqUnhRd6QY1lUsg3H3rNjgUKMnKU.jpg', 1, NULL, NULL, 'CARNELIA CREMA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:33:56', '2024-09-03 23:33:59'),
(2849, 'MAJESTIC LIGHT', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/SqFmNOrtTY7VfWHSYLlbZ0WmCjiwvN2qIUEDIwvH.jpg', 1, NULL, NULL, 'MAJESTIC LIGHT', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:34:22', '2024-09-03 23:34:25'),
(2850, 'MAJESTIC LIGHT_NEW PROFILE_R2', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/UZqJduF3rhOEE75NMyHhnMLiuwdehjhRo2rrGUEA.jpg', 1, NULL, NULL, 'MAJESTIC LIGHT', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:34:22', '2024-09-03 23:34:25'),
(2851, 'MAJESTIC LIGHT_NEW PROFILE_R3', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/qIS8owVCeVlcdOgRFUS9e4DD8kKCaoLoSrFHiarR.jpg', 1, NULL, NULL, 'MAJESTIC LIGHT', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:34:22', '2024-09-03 23:34:25'),
(2852, 'CM-030', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/hbm0SnmX3HYhFJB5Nr5lsF5fqJ4B1dcMPhlVnLyd.jpg', 1, NULL, NULL, 'CM-030', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:34:59', '2024-09-03 23:35:02'),
(2853, 'CM-030_R2', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/jXqFkB0K8QCpTLKfPlO2QLrYDqsjWKgOoTubX5oR.jpg', 1, NULL, NULL, 'CM-030', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:35:00', '2024-09-03 23:35:02'),
(2854, 'CM-030_R3', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/jX5NMjby9Ot0wJVrnANfaLJHllgZAnt4x0MpaQOs.jpg', 1, NULL, NULL, 'CM-030', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:35:00', '2024-09-03 23:35:02'),
(2855, 'CM-030_R4', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/lcKnqLhoLbXZt8m0oZDuHGA9p34NdnejBkSr63Z0.jpg', 1, NULL, NULL, 'CM-030', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:35:00', '2024-09-03 23:35:02'),
(2856, 'BOTTICHINO PEAR', 'square', 600, 600, 'floor', 'glossy', 'tiles/t4lOl4u3VPDWQu57GsCWg6C1fPJRlE7sX9M7nHUl.jpg', 1, NULL, NULL, 'BOTTICHINO PEAR', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:39:01', '2024-09-03 23:39:09'),
(2857, 'BOTTICHINO_PEARL_P1', 'square', 600, 600, 'floor', 'glossy', 'tiles/WXXUD7pAPhp2tDG0CUNTESOYA320frtIypMQt18f.jpg', 1, NULL, NULL, 'BOTTICHINO PEAR', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:39:02', '2024-09-03 23:39:09'),
(2858, 'BOTTICHINO_PEARL_P2', 'square', 600, 600, 'floor', 'glossy', 'tiles/NUUAO3fbR0k3CWj8KH9kTJHEnCPmmk9B9JGZKybs.jpg', 1, NULL, NULL, 'BOTTICHINO PEAR', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:39:02', '2024-09-03 23:39:09'),
(2859, 'BOTTICHINO_PEARL_P3', 'square', 600, 600, 'floor', 'glossy', 'tiles/L2psekB7LKQCNm9yFF99dZ5r0MmxxZlaKyW1Ognt.jpg', 1, NULL, NULL, 'BOTTICHINO PEAR', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:39:02', '2024-09-03 23:39:09'),
(2860, 'BOTTICHINO_PEARL_P4', 'square', 600, 600, 'floor', 'glossy', 'tiles/SkKOcCTal6PX5TUT74hNdFhjxeH7LJ5AhRLlrgJf.jpg', 1, NULL, NULL, 'BOTTICHINO PEAR', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:39:02', '2024-09-03 23:39:09'),
(2861, 'SATUARIO SELECT', 'square', 600, 600, 'floor', 'glossy', 'tiles/39m1Rc9DNjxeUmsK0asVcYUoLY9KF3KoeUljg49N.jpg', 1, NULL, NULL, 'SATUARIO SELECT', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:39:43', '2024-09-03 23:39:50'),
(2862, 'SATUARIO_SELECT_F2', 'square', 600, 600, 'floor', 'glossy', 'tiles/XH73jz2wAixW1pn0Ms5wKr2hVBsofAO9seB3rGLi.jpg', 1, NULL, NULL, 'SATUARIO SELECT', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:39:43', '2024-09-03 23:39:50'),
(2863, 'SATUARIO_SELECT_F3', 'square', 600, 600, 'floor', 'glossy', 'tiles/Rkh2NH8d2LmVG11EIlUaCwstUU9ELzHZH4HDm47D.jpg', 1, NULL, NULL, 'SATUARIO SELECT', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:39:44', '2024-09-03 23:39:50'),
(2864, 'SATUARIO_SELECT_F4', 'square', 600, 600, 'floor', 'glossy', 'tiles/6WhLBKlhzMjoXfs9KEmZ8qyucUKdXeKrOg7sq4wR.jpg', 1, NULL, NULL, 'SATUARIO SELECT', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:39:44', '2024-09-03 23:39:50'),
(2865, 'SATUARIO_SELECT_F6', 'square', 600, 600, 'floor', 'glossy', 'tiles/nvaYRMgIw4CPNAzr7FQn4n7FrXGtuGm1WirEQDvb.jpg', 1, NULL, NULL, 'SATUARIO SELECT', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:39:44', '2024-09-03 23:39:50'),
(2866, 'SONATA PEARL', 'square', 600, 600, 'floor', 'glossy', 'tiles/5irulgKUPrkhyg1UYuuyc393EGOqK8UxjXM161cH.jpg', 1, NULL, NULL, 'SONATA PEARL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:40:18', '2024-09-03 23:40:22'),
(2867, 'SONATA_PEARL_F2', 'square', 600, 600, 'floor', 'glossy', 'tiles/mqXapiERDhG2RysRxT4WJVfRroQAEgMA26iEjFFK.jpg', 1, NULL, NULL, 'SONATA PEARL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:40:18', '2024-09-03 23:40:22'),
(2868, 'SONATA_PEARL_F3', 'square', 600, 600, 'floor', 'glossy', 'tiles/OPfpZCd6QOIUSbXncXckkSE5YnYL11NFlXzhdNte.jpg', 1, NULL, NULL, 'SONATA PEARL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:40:18', '2024-09-03 23:40:22'),
(2869, 'SONATA_PEARL_F4', 'square', 600, 600, 'floor', 'glossy', 'tiles/7fzH39EL57xIRCzw1Az23gO1lBupyZ2PmapPrDG1.jpg', 1, NULL, NULL, 'SONATA PEARL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:40:18', '2024-09-03 23:40:22'),
(2870, 'SONATA_PEARL_F5', 'square', 600, 600, 'floor', 'glossy', 'tiles/vaybPycYUsWcQpEtrEcXPHfeGJ0bIKQNbzSkv78Q.jpg', 1, NULL, NULL, 'SONATA PEARL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:40:18', '2024-09-03 23:40:22'),
(2871, 'VELETA WHITE', 'square', 600, 600, 'floor', 'glossy', 'tiles/XCMwvTJOFOoBtqzsdIwlIbA5pKXGucoQaVDJqq8Q.jpg', 1, NULL, NULL, 'VELETA WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:40:42', '2024-09-03 23:40:47'),
(2872, 'VELETA_WHITE_F2', 'square', 600, 600, 'floor', 'glossy', 'tiles/6ic3CVUQ0pnBuO0fKkjFus4U1DbOL5Ekkila87XN.jpg', 1, NULL, NULL, 'VELETA WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:40:42', '2024-09-03 23:40:47'),
(2873, 'VELETA_WHITE_F4', 'square', 600, 600, 'floor', 'glossy', 'tiles/LmFEQh5V6WsN4C1qJmV7zTz6BgihTXikWmI04yLv.jpg', 1, NULL, NULL, 'VELETA WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:40:42', '2024-09-03 23:40:47'),
(2874, 'VELETA_WHITE_F5', 'square', 600, 600, 'floor', 'glossy', 'tiles/fu3zLh5tlx6qIalMWR8Q1ZOdg9oSE1eAWzhbOl29.jpg', 1, NULL, NULL, 'VELETA WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:40:42', '2024-09-03 23:40:47'),
(2875, 'BALMA BIANCO', 'square', 600, 600, 'floor', 'matt', 'tiles/aA7qISJGj6Ddb9NOR0R1q8NLnzm2zqzdIYQjnwhs.jpg', 1, NULL, NULL, 'BALMA BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:41:07', '2024-09-03 23:41:11'),
(2876, 'BALMA_BIANCO_F2', 'square', 600, 600, 'floor', 'matt', 'tiles/9Hf95x6iTKY2XLXeLALUqmnunaZz0BeLdNU9iRKv.jpg', 1, NULL, NULL, 'BALMA BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:41:07', '2024-09-03 23:41:11'),
(2877, 'BALMA_BIANCO_F3', 'square', 600, 600, 'floor', 'matt', 'tiles/LDjJOUNWqclXMtKbPz2pdBxFzSeknaFM0yOWY5Bt.jpg', 1, NULL, NULL, 'BALMA BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:41:07', '2024-09-03 23:41:11'),
(2878, 'BALMA_BIANCO_F4', 'square', 600, 600, 'floor', 'matt', 'tiles/7NVMlL8n7s4C0PSjTERBrrZnNOXMGzVjwtoq7gvK.jpg', 1, NULL, NULL, 'BALMA BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:41:07', '2024-09-03 23:41:11'),
(2879, 'DESERT SMOKE', 'square', 600, 600, 'floor', 'matt', 'tiles/MgINUVyFx9PAI90FeTqV2SHbfGHFmdCV0TU8NMcV.jpg', 1, NULL, NULL, 'DESERT SMOKE', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:41:43', '2024-09-03 23:41:45'),
(2880, 'Desert_Smoke_F4', 'square', 600, 600, 'floor', 'matt', 'tiles/QqrdVEyIGT99ODelkiVNoD7uZlAqo3K92Nr6lvb5.jpg', 1, NULL, NULL, 'DESERT SMOKE', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:41:43', '2024-09-03 23:41:45'),
(2881, 'Desert_Smoke_F5', 'square', 600, 600, 'floor', 'matt', 'tiles/dQ853SQhf7XRpOg81o3CwHFO69itNr51bD47JHo3.jpg', 1, NULL, NULL, 'DESERT SMOKE', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:41:43', '2024-09-03 23:41:45'),
(2882, 'PARKER BEIGE', 'square', 600, 600, 'floor', 'matt', 'tiles/8BrpQGEwWZp6FFgfUEfiAnuRImms8t1VQfI8Uuhv.jpg', 1, NULL, NULL, 'PARKER BEIGE', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:04', '2024-09-03 23:42:09'),
(2883, 'PARKER_BEIGE_F3', 'square', 600, 600, 'floor', 'matt', 'tiles/qpChaQ8rIZKhamCzglbQggCHE4lomoPahVNPkATN.jpg', 1, NULL, NULL, 'PARKER BEIGE', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:04', '2024-09-03 23:42:09'),
(2884, 'PARKER_BEIGE_MAIN FACE', 'square', 600, 600, 'floor', 'matt', 'tiles/GTc4ivCOy36Lwqbg3Mda7pHdUUnRT6oPRSAHBrM2.jpg', 1, NULL, NULL, 'PARKER BEIGE', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:04', '2024-09-03 23:42:09'),
(2885, 'CRYSTAL WHITE', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec3154e316.22983802.jpg', 1, NULL, NULL, 'CRYSTAL WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2886, 'CRYSTAL WHITE 31_R1', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec3158c500.06225180.jpg', 1, NULL, NULL, 'CRYSTAL WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2887, 'CRYSTAL WHITE 31_R2', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec315fa5f6.41414188.jpg', 1, NULL, NULL, 'CRYSTAL WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2888, 'CRYSTAL WHITE 31_R3 MASTER', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec316c5a07.19377382.jpg', 1, NULL, NULL, 'CRYSTAL WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2889, 'GLIMAR WHITE', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec316e9f92.84401487.jpg', 1, NULL, NULL, 'GLIMAR WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2890, 'GLIMAR WHITE_R2', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec317809c9.51721806.jpg', 1, NULL, NULL, 'GLIMAR WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2891, 'GLIMAR WHITE_R3', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec317b83d6.27484966.jpg', 1, NULL, NULL, 'GLIMAR WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2892, 'CM-015', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec31888527.67522433.jpg', 1, NULL, NULL, 'CM-015', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2893, 'CM-015_R2', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec31962449.65301697.jpg', 1, NULL, NULL, 'CM-015', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2894, 'CM-015_R3', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec31a4d3f4.03723724.jpg', 1, NULL, NULL, 'CM-015', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2895, 'CM-015_R4', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec31b881f3.38921554.jpg', 1, NULL, NULL, 'CM-015', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2896, 'ODALISCA NATURAL', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec31caab74.89846408.jpg', 1, NULL, NULL, 'ODALISCA NATURAL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2897, 'ODALISCA NATURAL F2', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec31d22ae3.52320635.jpg', 1, NULL, NULL, 'ODALISCA NATURAL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2898, 'ODALISCA NATURAL F3', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec31d655a1.29341369.jpg', 1, NULL, NULL, 'ODALISCA NATURAL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2899, 'ODALISCA NATURAL F4', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ec31e4c259.43001665.jpg', 1, NULL, NULL, 'ODALISCA NATURAL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:17', '2024-09-03 23:42:18'),
(2900, 'ALP SATUARIO', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec31f05df8.15444676.jpg', 1, NULL, NULL, 'ALP SATUARIO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2901, 'ALP SATUARIO_R2', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec32016959.39385170.jpg', 1, NULL, NULL, 'ALP SATUARIO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2902, 'ALP SATUARIO_R3', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec32095566.41547667.jpg', 1, NULL, NULL, 'ALP SATUARIO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2903, 'ALP SATUARIO_R4', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec32145184.64030633.jpg', 1, NULL, NULL, 'ALP SATUARIO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2904, 'ALP SATUARIO_R5', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec32178307.02535331.jpg', 1, NULL, NULL, 'ALP SATUARIO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2905, 'CARNELIA CREMA', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec321a37c7.21917390.jpg', 1, NULL, NULL, 'CARNELIA CREMA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2906, 'CARNELIA CREMA_NEW PROFILE_P2', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec322aad56.76444004.jpg', 1, NULL, NULL, 'CARNELIA CREMA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2907, 'CARNELIA CREMA_NEW PROFILE_P3', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec32373608.38200429.jpg', 1, NULL, NULL, 'CARNELIA CREMA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2908, 'MAJESTIC LIGHT', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec324698d6.91878952.jpg', 1, NULL, NULL, 'MAJESTIC LIGHT', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2909, 'MAJESTIC LIGHT_NEW PROFILE_R2', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec3252de67.09661333.jpg', 1, NULL, NULL, 'MAJESTIC LIGHT', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2910, 'MAJESTIC LIGHT_NEW PROFILE_R3', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec325c9825.95888402.jpg', 1, NULL, NULL, 'MAJESTIC LIGHT', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2911, 'CM-030', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec326598f3.41994354.jpg', 1, NULL, NULL, 'CM-030', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2912, 'CM-030_R2', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec327185f1.54770760.jpg', 1, NULL, NULL, 'CM-030', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2913, 'CM-030_R3', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec3274f177.55833660.jpg', 1, NULL, NULL, 'CM-030', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2914, 'CM-030_R4', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ec3276f679.05141290.jpg', 1, NULL, NULL, 'CM-030', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2915, 'BOTTICHINO PEAR', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec327942a9.12231030.jpg', 1, NULL, NULL, 'BOTTICHINO PEAR', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2916, 'BOTTICHINO_PEARL_P1', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec327b7c42.55891718.jpg', 1, NULL, NULL, 'BOTTICHINO PEAR', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2917, 'BOTTICHINO_PEARL_P2', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec327cc974.74789205.jpg', 1, NULL, NULL, 'BOTTICHINO PEAR', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2918, 'BOTTICHINO_PEARL_P3', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec327e81b0.43232420.jpg', 1, NULL, NULL, 'BOTTICHINO PEAR', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2919, 'BOTTICHINO_PEARL_P4', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec3280f1f6.48433275.jpg', 1, NULL, NULL, 'BOTTICHINO PEAR', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2920, 'SATUARIO SELECT', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec3281c604.87311455.jpg', 1, NULL, NULL, 'SATUARIO SELECT', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2921, 'SATUARIO_SELECT_F2', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec3282a482.72403454.jpg', 1, NULL, NULL, 'SATUARIO SELECT', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2922, 'SATUARIO_SELECT_F3', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec3283bba1.40317122.jpg', 1, NULL, NULL, 'SATUARIO SELECT', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2923, 'SATUARIO_SELECT_F4', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec32874b05.26838095.jpg', 1, NULL, NULL, 'SATUARIO SELECT', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2924, 'SATUARIO_SELECT_F6', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec328913c0.18283813.jpg', 1, NULL, NULL, 'SATUARIO SELECT', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2925, 'SONATA PEARL', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec328adea9.92567233.jpg', 1, NULL, NULL, 'SONATA PEARL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2926, 'SONATA_PEARL_F2', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec328b9723.90632918.jpg', 1, NULL, NULL, 'SONATA PEARL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2927, 'SONATA_PEARL_F3', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec328c38f7.08268483.jpg', 1, NULL, NULL, 'SONATA PEARL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2928, 'SONATA_PEARL_F4', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec328f9957.82233585.jpg', 1, NULL, NULL, 'SONATA PEARL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2929, 'SONATA_PEARL_F5', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec329075c2.85097908.jpg', 1, NULL, NULL, 'SONATA PEARL', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2930, 'VELETA WHITE', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec32925d87.54379237.jpg', 1, NULL, NULL, 'VELETA WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2931, 'VELETA_WHITE_F2', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec3294e6f2.61784708.jpg', 1, NULL, NULL, 'VELETA WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2932, 'VELETA_WHITE_F4', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec3296ca49.79641458.jpg', 1, NULL, NULL, 'VELETA WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2933, 'VELETA_WHITE_F5', 'square', 600, 600, 'wall', 'glossy', 'tiles/66d7ec32984b98.22743678.jpg', 1, NULL, NULL, 'VELETA WHITE', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2934, 'BALMA BIANCO', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ec329a2ab3.43604796.jpg', 1, NULL, NULL, 'BALMA BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2935, 'BALMA_BIANCO_F2', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ec329eded1.81782406.jpg', 1, NULL, NULL, 'BALMA BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2936, 'BALMA_BIANCO_F3', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ec32a2e273.97277003.jpg', 1, NULL, NULL, 'BALMA BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2937, 'BALMA_BIANCO_F4', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ec32a51510.29957129.jpg', 1, NULL, NULL, 'BALMA BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2938, 'DESERT SMOKE', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ec32a71173.41407798.jpg', 1, NULL, NULL, 'DESERT SMOKE', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2939, 'Desert_Smoke_F4', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ec32a935f0.80863818.jpg', 1, NULL, NULL, 'DESERT SMOKE', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2940, 'Desert_Smoke_F5', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ec32aa5a55.80340342.jpg', 1, NULL, NULL, 'DESERT SMOKE', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2941, 'PARKER BEIGE', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ec32ac3bc0.44696413.jpg', 1, NULL, NULL, 'PARKER BEIGE', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2942, 'PARKER_BEIGE_F3', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ec32ad25b6.81614679.jpg', 1, NULL, NULL, 'PARKER BEIGE', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2943, 'PARKER_BEIGE_MAIN FACE', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ec32adff34.59739212.jpg', 1, NULL, NULL, 'PARKER BEIGE', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:42:18', '2024-09-03 23:42:18'),
(2944, 'OREGON BIANCO', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/YcJlM2YrpDxGax9rRvopUoAo5SGRdrvtbtTGdJBc.jpg', 1, NULL, NULL, 'OREGON_BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:49:56', '2024-09-03 23:50:00'),
(2945, 'OREGON_BIANCO_F2', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/i3OYIFKMNM0OPrlFlxS215Kac0rCmG2OrdiX2OHj.jpg', 1, NULL, NULL, 'OREGON_BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:49:56', '2024-09-03 23:50:00'),
(2946, 'OREGON_BIANCO_F3', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/4rMXzDddKTALbuzeR982ybrGMb3Rr8L52uqpfOQz.jpg', 1, NULL, NULL, 'OREGON_BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:49:56', '2024-09-03 23:50:00'),
(2947, 'OREGON_BIANCO_F4', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/1vdodCB0BmdnYcxSPGN5VSCKTbz81tIJznU1wXSg.jpg', 1, NULL, NULL, 'OREGON_BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:49:56', '2024-09-03 23:50:00'),
(2948, 'OREGON_BIANCO_F5', 'rectangle', 1200, 600, 'floor', 'matt', 'tiles/QedtdAxcRj2WImQd3U5NpaC25XNthZhiucjoHhgZ.jpg', 1, NULL, NULL, 'OREGON_BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:49:56', '2024-09-03 23:50:00'),
(2949, 'CARRARA', 'square', 600, 600, 'floor', 'matt', 'tiles/5HSlmmYKnBVum3tCIwUAXsk1y2ApWIIA5bBAdrAe.jpg', 1, NULL, NULL, 'CARRARA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:34', '2024-09-03 23:51:39'),
(2950, 'Carrara_F2', 'square', 600, 600, 'floor', 'matt', 'tiles/r6J4bq77ciSBf7H4tNe9iZnzm1evd4CvuaW33LeZ.jpg', 1, NULL, NULL, 'CARRARA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:34', '2024-09-03 23:51:39'),
(2951, 'Carrara_F3', 'square', 600, 600, 'floor', 'matt', 'tiles/kgG8OF3gV5DSGn500twtAT10AlIflzdYrnNbMEIw.jpg', 1, NULL, NULL, 'CARRARA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:35', '2024-09-03 23:51:39'),
(2952, 'Carrara_F4', 'square', 600, 600, 'floor', 'matt', 'tiles/zoDkS0ja73ZkPe1qAxDhCePGr3BLxaN0cIYBKUd0.jpg', 1, NULL, NULL, 'CARRARA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:35', '2024-09-03 23:51:39'),
(2953, 'Carrara_MAIN FACE', 'square', 600, 600, 'floor', 'matt', 'tiles/sbYJd63nNAJ6th1IsYzVvEhg9E0IMONSVtC0Hlsm.jpg', 1, NULL, NULL, 'CARRARA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:35', '2024-09-03 23:51:39'),
(2954, 'OREGON BIANCO', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ee73cd7b79.48250874.jpg', 1, NULL, NULL, 'OREGON_BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:55', '2024-09-03 23:51:56'),
(2955, 'OREGON_BIANCO_F2', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ee73d1a311.04841917.jpg', 1, NULL, NULL, 'OREGON_BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:55', '2024-09-03 23:51:56'),
(2956, 'OREGON_BIANCO_F3', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ee73d8d4b7.91624977.jpg', 1, NULL, NULL, 'OREGON_BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:55', '2024-09-03 23:51:56'),
(2957, 'OREGON_BIANCO_F4', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ee73e1bb31.28769889.jpg', 1, NULL, NULL, 'OREGON_BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:55', '2024-09-03 23:51:56'),
(2958, 'OREGON_BIANCO_F5', 'rectangle', 1200, 600, 'wall', 'matt', 'tiles/66d7ee73e7dba6.01068197.jpg', 1, NULL, NULL, 'OREGON_BIANCO', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:55', '2024-09-03 23:51:56'),
(2959, 'CARRARA', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ee73ed4ef9.38376156.jpg', 1, NULL, NULL, 'CARRARA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:55', '2024-09-03 23:51:56'),
(2960, 'Carrara_F2', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ee73f07520.97823371.jpg', 1, NULL, NULL, 'CARRARA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:56', '2024-09-03 23:51:56'),
(2961, 'Carrara_F3', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ee74029df9.92407817.jpg', 1, NULL, NULL, 'CARRARA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:56', '2024-09-03 23:51:56'),
(2962, 'Carrara_F4', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ee74053e90.42177818.jpg', 1, NULL, NULL, 'CARRARA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:56', '2024-09-03 23:51:56'),
(2963, 'Carrara_MAIN FACE', 'square', 600, 600, 'wall', 'matt', 'tiles/66d7ee740aa803.27683030.jpg', 1, NULL, NULL, 'CARRARA', '{\"product code\":\"MATT\"}', 0, 1, '2024-09-03 23:51:56', '2024-09-03 23:51:56'),
(2964, 'WHITE FANTASY', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/pRhzAdO0y5cTXb2gO8SWMWBY5h4QsBnlIeLKcgYk.jpg', 1, NULL, NULL, 'WHITE FANTASY (600X1200)', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:54:23', '2024-09-03 23:54:26'),
(2965, 'WHITE_FANTASY_F2', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/1fDlA0PzEO9TESpXm1lJLddBBy21qStQBz3fgbMP.jpg', 1, NULL, NULL, 'WHITE FANTASY (600X1200)', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:54:23', '2024-09-03 23:54:26'),
(2966, 'WHITE_FANTASY_F3', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/9lIGZiEjY1XYSd0KmAFs2mvTPRvhD05rnaEOfqYU.jpg', 1, NULL, NULL, 'WHITE FANTASY (600X1200)', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:54:23', '2024-09-03 23:54:26'),
(2967, 'WHITE_FANTASY_MAIN FACE', 'rectangle', 1200, 600, 'floor', 'glossy', 'tiles/Dn50XLWIMFEtmODogj7eL71z4hLMJyMSCxnSy7Nr.jpg', 1, NULL, NULL, 'WHITE FANTASY (600X1200)', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:54:23', '2024-09-03 23:54:26'),
(2968, 'WHITE FANTASY', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ef13755cd2.99780150.jpg', 1, NULL, NULL, 'WHITE FANTASY (600X1200)', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:54:35', '2024-09-03 23:54:35'),
(2969, 'WHITE_FANTASY_F2', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ef13764888.43404967.jpg', 1, NULL, NULL, 'WHITE FANTASY (600X1200)', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:54:35', '2024-09-03 23:54:35'),
(2970, 'WHITE_FANTASY_F3', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ef1376e779.78512838.jpg', 1, NULL, NULL, 'WHITE FANTASY (600X1200)', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:54:35', '2024-09-03 23:54:35'),
(2971, 'WHITE_FANTASY_MAIN FACE', 'rectangle', 1200, 600, 'wall', 'glossy', 'tiles/66d7ef13778df2.65061794.jpg', 1, NULL, NULL, 'WHITE FANTASY (600X1200)', '{\"product code\":\"GLOSSY\"}', 0, 1, '2024-09-03 23:54:35', '2024-09-03 23:54:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'guest',
  `avatar` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `avatar`, `remember_token`, `enabled`, `created_at`, `updated_at`) VALUES
(7, 'Admin', 'tilevisualizer@gmail.com', '$2y$10$z1uGRqWeD2x6II9y.dsPFeqwwP7ABwnVxK9rWxpMqA7sXb3wttwkm', 'administrator', NULL, 'IdTeSnuHmpHfgVroc6UaD52FrT6SoBEk5ZCCUn8Ne8hvkY4PUyJkMHccKpgp', 1, '2017-07-04 05:47:54', '2018-02-19 07:38:10'),
(8, 'Shubham Prajapati', 'spshubham575@gmail.com', '$2y$10$OqV7EQUnmhasU1/j8F.1R.a92IxfVQt4h/j1B5jfy9yfIY8t3TeWy', 'guest', NULL, NULL, 1, '2024-04-12 02:48:16', '2024-04-12 02:48:16'),
(9, 'GG GG', 'xgagik7@gmail.com', '$2y$10$aH/nasCN9UkeSmIhPUn.JeC7IvtO9AJb60BmpHBguSk4HaYtGs0bi', 'guest', NULL, NULL, 1, '2024-04-19 12:11:54', '2024-04-19 12:11:54'),
(10, 'Ilyas Jumashev', 'ilsjum@gmail.com', '$2y$10$9esn3c80bFeDoqdV8KtjiO4PHk14mzI18dx9Dr15Xak6J4Uz4DlUG', 'administrator', NULL, NULL, 1, '2024-04-27 12:48:40', '2024-04-27 12:50:51'),
(11, 'PRIYA SHAH', 'tilescarreaux6@gmail.com', '$2y$10$07cgp/5m7ggR1olFdoHsQuxd/q/.sZr.YBqCDEVgmNJsu1rMWvGjy', 'editor', NULL, NULL, 1, '2024-07-23 05:44:40', '2024-07-23 05:44:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_tiles`
--
ALTER TABLE `custom_tiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `filters`
--
ALTER TABLE `filters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `panoramas`
--
ALTER TABLE `panoramas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD UNIQUE KEY `password_resets_email_unique` (`email`),
  ADD UNIQUE KEY `password_resets_token_unique` (`token`);

--
-- Indexes for table `room2ds`
--
ALTER TABLE `room2ds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `savedrooms`
--
ALTER TABLE `savedrooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surface_types`
--
ALTER TABLE `surface_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tiles`
--
ALTER TABLE `tiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `custom_tiles`
--
ALTER TABLE `custom_tiles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `filters`
--
ALTER TABLE `filters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `panoramas`
--
ALTER TABLE `panoramas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `room2ds`
--
ALTER TABLE `room2ds`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `savedrooms`
--
ALTER TABLE `savedrooms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `surface_types`
--
ALTER TABLE `surface_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tiles`
--
ALTER TABLE `tiles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2972;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
