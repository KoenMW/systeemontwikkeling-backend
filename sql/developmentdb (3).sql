-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Gegenereerd op: 11 mrt 2024 om 12:32
-- Serverversie: 11.1.3-MariaDB-1:11.1.3+maria~ubu2204
-- PHP-versie: 8.2.12

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
(3, 2, 'Stroll through history', 'Embark on an enchanting voyage through the rich tapestry of Haarlem\'s centuries-old history! Now, before you even step foot on the tour, you\'ll get a preview of the city\'s hidden gems. Our expertly guided walking tour is a thrilling 2.5-hour exploration, complete with a rejuvenating 15-minute break.', NULL),
(4, 4, 'Dance', 'Get ready to groove with Hardwel, Martin Garrix, and Tiesto in the ultimate dance extravaganza! Join us for electrifying beats and non-stop fun from August 15th to August 18th in Amsterdam. This high-energy event promises to keep you on your feet all night long.', NULL);

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
(4, 'De Sint Bavokerk', 'The city of Haarlem is located around the Grote St. Bavokerk (or Sint-Bavo Cathedral). This late medieval church was built on the Grote Markt and has a height of 78 meters.', NULL, 2),
(5, 'Hardwel', 'Experience the electrifying energy of Harwel as he takes the stage with his signature beats.', NULL, 4);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'bread'),
(3, 'vegetables');

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
(3, 'English Tour', '2024-07-26 10:00:00', 17.50, 'Grote markt', 25, 2, '2024-07-26 12:30:00', 2),
(6, 'Hardwel', '2024-07-27 22:00:00', 75.00, 'Lichtfabriek', 100, 4, '2024-07-27 02:00:00', 4);

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
(1, 2, 'Flexible Departures', 'Available every Thursday, Friday, and Saturday, our tours provide multiple departure times each day for your convenience. Discover the magic of Haarlem, starting from the awe-inspiring \'Church of St. Bavo,\' an iconic landmark that sets the stage for your adventure.'),
(2, 4, 'Flexible Schedule', 'Choose from multiple departure times on each event day to suit your schedule. Dance the night away as we explore the vibrant nightlife of Amsterdam and immerse ourselves in the pulsating rhythm of the city.');

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
-- Tabelstructuur voor tabel `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` varchar(8000) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `description`, `image`, `category_id`) VALUES
(1, 'Ciabatta', 2.50, 'Ciabatta (which translates to slipper!) is an Italian bread made with wheat flour, salt, yeast, and water. Though it\'s texture and crust vary slightly throughout Italy, the essential ingredients remain the same. Ciabatta is best for sandwiches and paninis, naturally.', 'https://hips.hearstapps.com/hmg-prod.s3.amazonaws.com/images/957759184-1529703875.jpg?crop=1.00xw:0.645xh;0,0.104xh&resize=980:*', 1),
(2, 'Whole Wheat Bread', 2.00, 'Unlike white bread, whole-wheat bread is made from flour that uses almost the entire wheat grain—with the bran and germ in tact. This means more nutrients and fiber per slice! ', 'https://hips.hearstapps.com/hmg-prod.s3.amazonaws.com/images/whole-wheat-bread-horizontal-1-jpg-1590195849.jpg?crop=0.735xw:0.735xh;0.187xw,0.128xh&resize=980:*', 1),
(3, 'Artichoke', 1.50, 'Artichokes contain an unusual organic acid called cynarin which affects taste and may be the reason why water appears to taste sweet after eating artichokes. The flavour of wine is similarly altered and many wine experts believe that wine shouldn’t accompany artichokes.', 'https://www.vegetables.co.nz/assets/vegetables/_resampled/FillWyI0MDAiLCIzMDAiXQ/artichokes-globe.png', 3),
(4, 'Asparagus ', 3.00, 'Asparagus originated in the Eastern Mediterranean and was a favourite of the Greeks and Romans who used it as a medicine. Varieties of asparagus grow wild in parts of Europe, Turkey, Africa, Middle East and Asia.', 'https://www.vegetables.co.nz/assets/vegetables/_resampled/FillWyI0MDAiLCIzMDAiXQ/asparagus.png', 3);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`) VALUES
(1, 'username', '$2y$10$DQlV0u9mFmtOWsOdxXX9H.4kgzEB3E8o97s.S.Pdy4klUAdBvtVh.', 'username@password.com');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'omar@omar', '$2y$10$miHTzXit0b8PWceKqYcgC.uQOIGFybTp8fR5X9/TMMsWwhiyMnB32', 0, NULL, NULL),
(3, 'nick.schaap127@gmail.com', 'nick', 0, NULL, NULL);

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
-- Indexen voor tabel `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

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
-- Indexen voor tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_category` (`category_id`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `eventType`
--
ALTER TABLE `eventType`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `info_texts`
--
ALTER TABLE `info_texts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT voor een tabel `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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

--
-- Beperkingen voor tabel `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
