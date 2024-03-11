-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Mar 11, 2024 at 10:44 AM
-- Server version: 11.3.2-MariaDB-1:11.3.2+maria~ubu2204
-- PHP Version: 8.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developmentdb`
--
CREATE DATABASE IF NOT EXISTS `developmentdb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `developmentdb`;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `intro` text DEFAULT NULL,
  `picture` longblob DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `page_id`, `title`, `intro`, `picture`) VALUES
(1, 1, 'Jazz', 'Experience the soulful diversity at Haarlem Festival Jazz with an electrifying lineup of vibrant jazz styles. From the improvisational fusion of Gumbo Kings to Evolve\'s avant-garde beats, and Ntjam Rosie\'s captivating neo-soul, immerse yourself in a tapestry of musical brilliance. Groove to Wicked Jazz Sounds\' infectious rhythms, savor Tom Thomsom Assemble\'s dynamic compositions, and be enchanted by Jonna Frazer\'s spellbinding vocals. Let Fox & The Mayors\' melodic tales captivate, Uncle Sue\'s nostalgia linger, and Chris Allen\'s smooth jazz mastery mesmerize. Feel Myles Sanko\'s soulful tunes, embrace Ruis Soundsystem\'s experimental beats, and bask in The Family XL\'s expansive melodies. Join us in Haarlem for an unforgettable celebration of jazz\'s rich tapestry!', NULL),
(2, 3, 'Yummy', 'Experience Haarlem\'s Yummie! Festival event – \r\na culinary delight showcasing our city\'s diverse \r\nflavors! Discover exclusive festival menus at top \r\nrestaurants and savor home cooking recipes from \r\nour renowned chefs. Join us for an unforgettable \r\ncelebration of Haarlem\'s vibrant food scene!', NULL),
(3, 2, 'Stroll through history', 'Embark on an enchanting voyage through the rich tapestry of Haarlem\'s centuries-old history! Now, before you even step foot on the tour, you\'ll get a preview of the city\'s hidden gems. Our expertly guided walking tour is a thrilling 2.5-hour exploration, complete with a rejuvenating 15-minute break.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
CREATE TABLE IF NOT EXISTS `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `picture` longblob DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `redirect_link` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `title`, `text`, `picture`, `page_id`, `redirect_link`) VALUES
(1, 'Gare du Nord', 'Gare du Nord is a sophisticated jazz-pop band acclaimed for their fusion of lounge, jazz, and pop elements, creating a distinctive sound that resonates with listeners worldwide.', NULL, 1, ''),
(2, 'Tatsu Haarlem', 'At Tatsu Haarlem, you can enjoy a delightful all-you-can-eat lunch and dinner seven days a week. Come by and get two hours of unlimited access to our delicious sushi and modern Japanese cuisine.', NULL, 3, ''),
(4, 'De Sint Bavokerk', 'The city of Haarlem is located around the Grote St. Bavokerk (or Sint-Bavo Cathedral). This late medieval church was built on the Grote Markt and has a height of 78 meters.', NULL, 2, ''),
(5, 'Myles Sanko', 'Myles Sanko, a soul singer extraordinaire, brings his powerful voice and emotive storytelling to the stage, delivering performances that touch hearts and uplift spirits.', NULL, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `startTime` datetime NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `location` varchar(255) NOT NULL,
  `ticket_amount` int(11) NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `endTime` datetime NOT NULL,
  `eventType` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  KEY `event_typefk` (`eventType`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `startTime`, `price`, `location`, `ticket_amount`, `page_id`, `endTime`, `eventType`) VALUES
(1, 'Gare du Nord', '2024-07-26 18:00:00', 15.00, 'Patronaat Main Hall', 200, 1, '2024-07-26 19:00:00', 1),
(2, 'Mano Restaurant', '2024-07-26 18:00:00', 10.00, 'Bakenessergracht 109', 20, 3, '2024-07-26 20:00:00', 3),
(3, 'English Tour', '2024-07-26 10:00:00', 17.50, 'Grote markt', 25, 2, '2024-07-26 12:30:00', 2),
(4, 'Rilan & The Bombadiers', '2024-07-26 19:30:00', 15.00, 'Patronaat Main Hall', 200, 1, '2024-07-26 20:30:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `eventType`
--

DROP TABLE IF EXISTS `eventType`;
CREATE TABLE IF NOT EXISTS `eventType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventType`
--

INSERT INTO `eventType` (`id`, `event`) VALUES
(1, 'Jazz'),
(2, 'History'),
(3, 'Yummy'),
(4, 'Dance');

-- --------------------------------------------------------

--
-- Table structure for table `info_texts`
--

DROP TABLE IF EXISTS `info_texts`;
CREATE TABLE IF NOT EXISTS `info_texts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `img` longblob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `info_texts`
--

INSERT INTO `info_texts` (`id`, `page_id`, `title`, `content`, `img`) VALUES
(1, 2, 'Flexible Departures', 'Available every Thursday, Friday, and Saturday, our tours provide multiple departure times each day for your convenience. Discover the magic of Haarlem, starting from the awe-inspiring \'Church of St. Bavo,\' an iconic landmark that sets the stage for your adventure.', '');

-- --------------------------------------------------------

--
-- Table structure for table `Orders`
--

DROP TABLE IF EXISTS `Orders`;
CREATE TABLE IF NOT EXISTS `Orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `paymentDate` date DEFAULT NULL,
  `checkedIn` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Orders`
--

INSERT INTO `Orders` (`id`, `event_id`, `user_id`, `quantity`, `comment`, `paymentDate`, `checkedIn`) VALUES
(1, 1, 1, 2, NULL, NULL, b'1');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`) VALUES
(1, 'Jazz'),
(2, 'History'),
(3, 'Yummy'),
(4, 'Dance');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `img` longblob DEFAULT NULL,
  `create_time` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `img`, `create_time`) VALUES
(1, 'test', '$2y$10$Y0l3evmJXD4sXrXBoQmv5.A84sKDNvyW5o5qacZ9bb8bCS45zeDd.', 0, NULL, '2024-03-11'),
(3, 'test@test.test', '$2y$10$CUCi56DH6GbykXc3GdaJFuWhuhCPCkxzYLeTsdv8YyHzjoMVsgcYO', 0, NULL, '2024-03-11');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `banners`
--
ALTER TABLE `banners`
  ADD CONSTRAINT `banners_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `event_typefk` FOREIGN KEY (`eventType`) REFERENCES `eventType` (`id`),
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
