-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Mar 17, 2024 at 12:58 PM
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
CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `intro` text DEFAULT NULL,
  `picture` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `page_id`, `title`, `intro`, `picture`) VALUES
(1, 1, 'Jazz', 'Experience the soulful diversity at Haarlem Festival Jazz with an electrifying lineup of vibrant jazz styles. From the improvisational fusion of Gumbo Kings to Evolve\'s avant-garde beats, and Ntjam Rosie\'s captivating neo-soul, immerse yourself in a tapestry of musical brilliance. Groove to Wicked Jazz Sounds\' infectious rhythms, savor Tom Thomsom Assemble\'s dynamic compositions, and be enchanted by Jonna Frazer\'s spellbinding vocals. Let Fox & The Mayors\' melodic tales captivate, Uncle Sue\'s nostalgia linger, and Chris Allen\'s smooth jazz mastery mesmerize. Feel Myles Sanko\'s soulful tunes, embrace Ruis Soundsystem\'s experimental beats, and bask in The Family XL\'s expansive melodies. Join us in Haarlem for an unforgettable celebration of jazz\'s rich tapestry!', NULL),
(2, 3, 'Yummy', 'Experience Haarlem\'s Yummie! Festival event – \r\na culinary delight showcasing our city\'s diverse \r\nflavors! Discover exclusive festival menus at top \r\nrestaurants and savor home cooking recipes from \r\nour renowned chefs. Join us for an unforgettable \r\ncelebration of Haarlem\'s vibrant food scene!', NULL),
(3, 2, 'Stroll through history', 'Embark on an enchanting voyage through the rich tapestry of Haarlem\'s centuries-old history! Now, before you even step foot on the tour, you\'ll get a preview of the city\'s hidden gems. Our expertly guided walking tour is a thrilling 2.5-hour exploration, complete with a rejuvenating 15-minute break.', NULL),
(4, 4, 'Dance', 'Get ready to groove with Hardwel, Martin Garrix, and Tiesto in the ultimate dance extravaganza! Join us for electrifying beats and non-stop fun from August 15th to August 18th in Amsterdam. This high-energy event promises to keep you on your feet all night long.', NULL),
(5, 9, 'Gare du Nord', 'Gare du Nord, an illustrious Dutch-Belgian jazz band, found its roots in the collaborative genius of Doc (Ferdi Lancee) and Inca (Barend Fransen). Formed with a musical vision steeped in jazz nuances, the duo seamlessly blended guitar strums by Doc and saxophone melodies by Inca, setting the stage for their distinct vocal harmonies. Their musical journey, spanning over a decade, embarked in 2001, laying the foundation for an extraordinary fusion of lounge and jazz music. With an enigmatic charm, Gare du Nord\'s compositions cast a spellbinding allure, drawing audiences into a world where rhythmic intricacies intertwined with soulful melodies.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `picture` longblob DEFAULT NULL,
  `page_id` int(11) NOT NULL,
  `redirect_link` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `title`, `text`, `picture`, `page_id`, `redirect_link`) VALUES
(1, 'Gare du Nord', 'Gare du Nord is a sophisticated jazz-pop band acclaimed for their fusion of lounge, jazz, and pop elements, creating a distinctive sound that resonates with listeners worldwide.', NULL, 1, ''),
(2, 'Tatsu Haarlem', 'At Tatsu Haarlem, you can enjoy a delightful all-you-can-eat lunch and dinner seven days a week. Come by and get two hours of unlimited access to our delicious sushi and modern Japanese cuisine.', NULL, 3, ''),
(4, 'De Sint Bavokerk', 'The city of Haarlem is located around the Grote St. Bavokerk (or Sint-Bavo Cathedral). This late medieval church was built on the Grote Markt and has a height of 78 meters.', NULL, 2, ''),
(5, 'Myles Sanko', 'Myles Sanko, a soul singer extraordinaire, brings his powerful voice and emotive storytelling to the stage, delivering performances that touch hearts and uplift spirits.', NULL, 1, ''),
(9, 'Ntjam Rosie', 'Ntjam Rosie, a Haarlem native, enchants audiences with her versatile vocals and eclectic blend of jazz, soul, and African influences, creating a truly unique musical experience.', NULL, 1, NULL),
(10, 'Rilan & The Bombadiers', 'Rilan & The Bombadiers deliver energetic and charismatic performances, blending funk, pop, and jazz, creating an electrifying atmosphere that gets the crowd moving.', NULL, 1, NULL),
(11, 'Soul Six', 'Soul Six brings a contemporary twist to classic soul and jazz, infusing their performances with infectious grooves and powerful vocals that resonate with audiences of all ages.', NULL, 1, NULL),
(12, 'De Hallen', 'The Frans Hals Museum, founded in 1862 and known as the “Museum of the Golden Century,” displays Haarlem’s 16th-century architectural development.', NULL, 2, NULL),
(13, 'Grote Markt', 'Next to the church of St. Bavo there is a large square in the centre, formerly known as \'t Sant, a name originating from when the square was unpaved.', NULL, 2, NULL),
(14, 'Proveniershof', 'established in 1704, was originally the Michielsklooster, a structure built for women in 1401. After 167 years, the grounds were transferred to the city of Haarlem.', NULL, 2, NULL),
(15, 'Waalse Kerk Haarlem', 'Built in 1262, the Walloon Church is Haarlem’s oldest. Despite a fire in 1347 that led to its destruction, it was rebuilt a few years later, preserving its status as the city’s most ancient church.', NULL, 2, NULL),
(16, 'Mano Restaurant', 'Meet Kevin Kion and Daniël Damen, the culinary force behind Mano. Our menu blends global street food influences with French finesse. Join our 15-year flavorful journey today! ', NULL, 3, NULL),
(17, 'Restaurant De Zeeuw', 'Discover Restaurant De Zeeuw\'s artisanal touch, offering a sustainable dining experience with locally sourced seasonal delights, curated by skilled chefs.', NULL, 3, NULL),
(18, 'card titel Hardwel', 'Experience the electrifying energy of Harwel as he takes the stage with his signature beats.', NULL, 4, NULL),
(19, '2001', 'Doc and Inca begin their collaborative journey, crafting lounge music and signing a record deal with Play It Again Sam Records. Their debut album, \'(In Search Of) Excellounge,\' introduces audiences to their unique blend of jazz.', NULL, 9, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detail_page`
--

DROP TABLE IF EXISTS `detail_page`;
CREATE TABLE `detail_page` (
  `page_id` int(11) NOT NULL,
  `parent_page_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_page`
--

INSERT INTO `detail_page` (`page_id`, `parent_page_id`) VALUES
(9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `startTime` datetime NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `location` varchar(255) NOT NULL,
  `ticket_amount` int(11) NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `detail_page_id` int(11) DEFAULT NULL,
  `endTime` datetime NOT NULL,
  `eventType` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `startTime`, `price`, `location`, `ticket_amount`, `page_id`, `detail_page_id`, `endTime`, `eventType`) VALUES
(1, 'Gare du Nord', '2024-07-26 18:00:00', 15.00, 'Patronaat Main Hall', 200, 1, NULL, '2024-07-26 19:00:00', 1),
(2, 'Mano Restaurant', '2024-07-26 18:00:00', 10.00, 'Bakenessergracht 109', 20, 3, NULL, '2024-07-26 20:00:00', 3),
(3, 'English Tour', '2024-07-26 10:00:00', 17.50, 'Grote markt', 25, 2, NULL, '2024-07-26 12:30:00', 2),
(4, 'Rilan & The Bombadiers', '2024-07-26 19:30:00', 15.00, 'Patronaat Main Hall', 200, 1, NULL, '2024-07-26 20:30:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `eventType`
--

DROP TABLE IF EXISTS `eventType`;
CREATE TABLE `eventType` (
  `id` int(11) NOT NULL,
  `event` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
CREATE TABLE `info_texts` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `img` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
CREATE TABLE `Orders` (
  `id` varchar(23) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `paymentDate` date DEFAULT NULL,
  `checkedIn` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Orders`
--

INSERT INTO `Orders` (`id`, `event_id`, `user_id`, `quantity`, `comment`, `paymentDate`, `checkedIn`) VALUES
('65ef324f0e1836.47147373', 1, 5, 2, '', NULL, b'0'),
('65ef32a0b82d25.11475413', 1, 5, 2, '', NULL, b'0'),
('65ef33223cd3f5.54606502', 1, 5, 2, '', NULL, b'0'),
('65ef3327520141.29808749', 1, 5, 2, '', NULL, b'0'),
('65ef3328bed001.66767682', 1, 5, 2, '', NULL, b'0'),
('65ef332a5c0577.71884554', 1, 5, 2, '', NULL, b'0'),
('65ef337112cfd5.71212646', 1, 5, 2, '', NULL, b'0'),
('65ef33830ab194.64343561', 1, 5, 2, '', NULL, b'0'),
('65ef3397c73777.35846103', 1, 5, 2, '', NULL, b'0'),
('65ef34cea8eeb7.61936709', 1, 5, 2, '', NULL, b'0');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`) VALUES
(1, 'Jazz'),
(2, 'History'),
(3, 'Yummy'),
(4, 'Dance'),
(9, 'Gare du Nord');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_card`
--

DROP TABLE IF EXISTS `restaurant_card`;
CREATE TABLE `restaurant_card` (
  `id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `img` longblob DEFAULT NULL,
  `createDate` date NOT NULL DEFAULT current_timestamp(),
  `phoneNumber` int(10) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `confirmed` bit(11) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `img`, `createDate`, `phoneNumber`, `address`, `confirmed`) VALUES
(5, 'test', 'test@test.test', '$2y$10$tEK3nr8MsCqHcwGLd.JFsOBwh7.Qgs1ckXcv0zUPB668HGJ1LfsIS', 0, '', '2024-03-11', 1, 'test', b'00000000000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `detail_page`
--
ALTER TABLE `detail_page`
  ADD PRIMARY KEY (`page_id`),
  ADD UNIQUE KEY `page_id` (`page_id`),
  ADD KEY `parent_page_id` (`parent_page_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_id` (`page_id`),
  ADD KEY `event_typefk` (`eventType`),
  ADD KEY `detail_page_id` (`detail_page_id`);

--
-- Indexes for table `eventType`
--
ALTER TABLE `eventType`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `info_texts`
--
ALTER TABLE `info_texts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurant_card`
--
ALTER TABLE `restaurant_card`
  ADD KEY `card_id` (`card_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `eventType`
--
ALTER TABLE `eventType`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `info_texts`
--
ALTER TABLE `info_texts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- Constraints for table `detail_page`
--
ALTER TABLE `detail_page`
  ADD CONSTRAINT `detail_page_ibfk_1` FOREIGN KEY (`parent_page_id`) REFERENCES `pages` (`id`),
  ADD CONSTRAINT `detail_page_ibfk_2` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `event_typefk` FOREIGN KEY (`eventType`) REFERENCES `eventType` (`id`),
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`),
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`detail_page_id`) REFERENCES `detail_page` (`page_id`);

--
-- Constraints for table `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `Orders_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `Orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `restaurant_card`
--
ALTER TABLE `restaurant_card`
  ADD CONSTRAINT `restaurant_card_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
