-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Gegenereerd op: 10 mrt 2024 om 13:36
-- Serverversie: 10.11.2-MariaDB-1:10.11.2+maria~ubu2204
-- PHP-versie: 8.1.18

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

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `intro` text DEFAULT NULL,
  `picture` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `banners`
--

INSERT INTO `banners` (`id`, `page_id`, `title`, `intro`, `picture`) VALUES
(1, 1, 'Jazz', 'Experience the soulful diversity at Haarlem Festival Jazz with an electrifying lineup of vibrant jazz styles. From the improvisational fusion of Gumbo Kings to Evolve\'s avant-garde beats, and Ntjam Rosie\'s captivating neo-soul, immerse yourself in a tapestry of musical brilliance. Groove to Wicked Jazz Sounds\' infectious rhythms, savor Tom Thomsom Assemble\'s dynamic compositions, and be enchanted by Jonna Frazer\'s spellbinding vocals. Let Fox & The Mayors\' melodic tales captivate, Uncle Sue\'s nostalgia linger, and Chris Allen\'s smooth jazz mastery mesmerize. Feel Myles Sanko\'s soulful tunes, embrace Ruis Soundsystem\'s experimental beats, and bask in The Family XL\'s expansive melodies. Join us in Haarlem for an unforgettable celebration of jazz\'s rich tapestry!', NULL),
(2, 3, 'Yummy', 'Experience Haarlem\'s Yummie! Festival event – \r\na culinary delight showcasing our city\'s diverse \r\nflavors! Discover exclusive festival menus at top \r\nrestaurants and savor home cooking recipes from \r\nour renowned chefs. Join us for an unforgettable \r\ncelebration of Haarlem\'s vibrant food scene!', NULL),
(3, 2, 'Stroll through history', 'Embark on an enchanting voyage through the rich tapestry of Haarlem\'s centuries-old history! Now, before you even step foot on the tour, you\'ll get a preview of the city\'s hidden gems. Our expertly guided walking tour is a thrilling 2.5-hour exploration, complete with a rejuvenating 15-minute break.', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `picture` longblob DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `cards`
--

INSERT INTO `cards` (`id`, `title`, `text`, `picture`, `page_id`) VALUES
(1, 'Gare du Nord', 'Gare du Nord is a sophisticated jazz-pop band acclaimed for their fusion of lounge, jazz, and pop elements, creating a distinctive sound that resonates with listeners worldwide.', NULL, 1),
(2, 'Tatsu Haarlem', 'At Tatsu Haarlem, you can enjoy a delightful all-you-can-eat lunch and dinner seven days a week. Come by and get two hours of unlimited access to our delicious sushi and modern Japanese cuisine.', NULL, 3),
(4, 'De Sint Bavokerk', 'The city of Haarlem is located around the Grote St. Bavokerk (or Sint-Bavo Cathedral). This late medieval church was built on the Grote Markt and has a height of 78 meters.', NULL, 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `startTime` datetime NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `location` varchar(255) NOT NULL,
  `ticket_amount` int(11) NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `endTime` datetime NOT NULL,
  `eventType` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `events`
--

INSERT INTO `events` (`id`, `title`, `startTime`, `price`, `location`, `ticket_amount`, `page_id`, `endTime`, `eventType`) VALUES
(1, 'Gare du Nord', '2024-07-26 18:00:00', 15.00, 'Patronaat Main Hall', 200, 1, '2024-07-26 19:00:00', 1),
(2, 'Mano Restaurant', '2024-07-26 18:00:00', 10.00, 'Bakenessergracht 109', 20, 3, '2024-07-26 20:00:00', 3),
(3, 'English Tour', '2024-07-26 10:00:00', 17.50, 'Grote markt', 25, 2, '2024-07-26 12:30:00', 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `eventType`
--

CREATE TABLE `eventType` (
  `id` int(11) NOT NULL,
  `event` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `eventType`
--

INSERT INTO `eventType` (`id`, `event`) VALUES
(1, 'Jazz'),
(2, 'History'),
(3, 'Yummy'),
(4, 'Dance');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `info_texts`
--

CREATE TABLE `info_texts` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `info_texts`
--

INSERT INTO `info_texts` (`id`, `page_id`, `title`, `content`) VALUES
(1, 2, 'Flexible Departures', 'Available every Thursday, Friday, and Saturday, our tours provide multiple departure times each day for your convenience. Discover the magic of Haarlem, starting from the awe-inspiring \'Church of St. Bavo,\' an iconic landmark that sets the stage for your adventure.');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Orders`
--

CREATE TABLE `Orders` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `paymentDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `pages`
--

INSERT INTO `pages` (`id`, `name`) VALUES
(1, 'Jazz'),
(2, 'History'),
(3, 'Yummy'),
(4, 'Dance');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `createDate` date NOT NULL DEFAULT current_timestamp(),
  `picture` TEXT DEFAULT NULL,
  `confirmed` BOOLEAN NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexen voor tabel `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexen voor tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_id` (`page_id`),
  ADD KEY `event_typefk` (`eventType`);

--
-- Indexen voor tabel `eventType`
--
ALTER TABLE `eventType`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `info_texts`
--
ALTER TABLE `info_texts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexen voor tabel `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `eventType`
--
ALTER TABLE `eventType`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `info_texts`
--
ALTER TABLE `info_texts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `Orders`
--
ALTER TABLE `Orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `banners`
--
ALTER TABLE `banners`
  ADD CONSTRAINT `banners_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

--
-- Beperkingen voor tabel `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

--
-- Beperkingen voor tabel `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `event_typefk` FOREIGN KEY (`eventType`) REFERENCES `eventType` (`id`),
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

--
-- Beperkingen voor tabel `info_texts`
--
ALTER TABLE `info_texts`
  ADD CONSTRAINT `info_texts_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`);

--
-- Beperkingen voor tabel `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `Orders_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `Orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

