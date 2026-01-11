-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2026 at 01:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_projekt`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `country` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `newsletter` varchar(10) NOT NULL DEFAULT 'NE',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `country_code` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `first_name`, `last_name`, `email`, `country`, `message`, `newsletter`, `is_read`, `created_at`, `country_code`) VALUES
(2, 'Admin', 'Admin', 'admin@email.com', 'Hrvatska', 'Poruka', 'NE', 1, '2026-01-10 19:08:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country_code` varchar(2) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_code`, `name`) VALUES
('AT', 'Austrija'),
('BA', 'Bosna i Hercegovina'),
('DE', 'Njemačka'),
('FR', 'Francuska'),
('GB', 'Ujedinjeno Kraljevstvo'),
('HR', 'Hrvatska'),
('IT', 'Italija'),
('ME', 'Crna Gora'),
('RS', 'Srbija'),
('SI', 'Slovenija'),
('US', 'Sjedinjene Američke Države');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `content` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `archive` enum('Y','N') NOT NULL DEFAULT 'N',
  `user_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `description`, `content`, `image`, `date`, `archive`, `user_id`) VALUES
(4, 'Fiat ima novi model koji dolazi i uz dizelski motor', 'Fiat vraća ime Qubo kao prostrani, iznimno prilagodljiv obiteljski suto u dvije duljine, s dizelskim, benzinskim i električnim pogonom te čak 144 moguće konfiguracije sjedala', 'Fiat je ponovno oživio ime Qubo, ovaj put kao praktičan obiteljski model koji na tržište stiže za nekoliko mjeseci. Nakon što je originalni Qubo bio putnička verzija malog dostavnjaka Fiorina, novi Qubo L nastavlja istu logiku - puno prostora, jednostavnost korištenja i fleksibilnost koja bi trebala odgovarati obiteljima, urbanim vozačima i ljubiteljima avantura. Iako ga Fiat predstavlja kao potpuno novi proizvod, već na prvi pogled jasno je da Qubo L itekako dijeli gene s poznatim modelima iz Stellantis grupe. Kao i Doblo, od kojeg izravno potječe, Qubo L dolazi u dvije karoserijske varijante. Kraća verzija duga je 440 centimetara i nudi pet sjedala, dok produženi model raste na 475 centimetara te omogućuje konfiguraciju sa sedam sjedala. Platformu dijeli s Peugeotom Rifterom, Citroënom Berlingom, Opel Combom i Toyotom Proace Verso, a Fiat najavljuje impresivnu modularnost - čak 144 moguće konfiguracije sjedala zahvaljujući trima odvojenim sjedalima u drugom redu i dvama pomičnim sjedalima u trećem, koja se mogu i potpuno izvaditi. Iako Fiat nije objavio nikakvbe fotografije unutrašnjosti, pretpostavalja se da je dizajn \"posuđen\" od aktualnog Dobla. \r\n\r\nPročitajte više na: https://autostart.24sata.hr/novosti/fiat-ima-novi-model-koji-dolazi-i-uz-dizelski-motor-10364 - autostart.24sata.hr', 'img/1768068221_fiat.jpeg', '2026-01-10 19:03:41', 'N', 0),
(5, 'Nova Giulia Quadrifoglio Luna Rossa je prava rijetka poslastica iz Alfe', 'Nikakve informacije o cijeni nisu otkrivene, no sigurno je da se radi o \"paprenom iznosu\" prilagođenom platežnim mogućnostima kolekcionara koji će sigurno razgrabiti ovaj model', 'Alfa Romeo je na sajmu u Bruxellesu predstavio jedan od svojih najekskluzivnijih i najposebnijih modela posljednjih godina. Giulia Quadrifoglio Luna Rossa nastala je u sklopu projekta Bottegafuoriserie, a rezultat je suradnje s talijanskim timom Luna Rossa iz prestižnog jedriličarskog natjecanja America\'s Cup. Svega deset primjeraka bit će ručno proizvedeno, što je dovoljan pokazatelj da je riječ o automobilu kreiranom za najstrastvenije kolekcionare brenda.\r\n\r\nPročitajte više na: https://autostart.24sata.hr/novosti/nova-giulia-quadrifoglio-luna-rossa-je-prava-rijetka-poslastica-iz-alfe-10362 - autostart.24sata.hr', 'img/1768068287_alfa.jpeg', '2026-01-10 19:04:47', 'N', 0),
(6, 'Kineski proizvođač usisavača šokirao: Ovo je njihova limuzina brza poput Nevere', 'Prema riječima čelnika tvrtke, proizvodnja bi trebala započeti već krajem godine u Njemačkoj, u pogonu blizu Tesline Gigafactory', 'CES u Las Vegasu već godinama služi kao pozornica na kojoj se brišu granice između potrošačke elektronike i automobilske industrije, no rijetko tko je očekivao da će jedna tvrtka koja proizvodi kućanske uređaje privući toliku pažnju. Kineska tvrtka Dreame, poznata po svim vrstama usisavača, na CES-u je predstavila je svoj prvi automobil, koncept Kosmera Nebula 1, koji donosi nevjerojatne brojke.  Nebula 1 je izrazito nizak električni s četverim vratima. Njegova je silueta više nalik modernom superautomobilu nego klasičnoj limuzini. Nakon ranijih najava koje su dizajnom jako podsjećale na Bugatti, serijska studija djeluje zrelije i elegantnije, s jasnim utjecajima talijanske škole dizajna.\r\n\r\nPročitajte više na: https://autostart.24sata.hr/novosti/kineski-proizvodac-usisavaca-sokirao-ovo-je-njihova-limuzina-brza-poput-nevere-10350 - autostart.24sata.hr', 'img/1768068462_kineski_auto1.png', '2026-01-10 19:07:42', 'N', 0);

-- --------------------------------------------------------

--
-- Table structure for table `news_images`
--

CREATE TABLE `news_images` (
  `id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_images`
--

INSERT INTO `news_images` (`id`, `news_id`, `image_path`) VALUES
(1, 6, 'img/gal_6962a44352fb8.png'),
(2, 6, 'img/gal_6962a44353be4.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `role` enum('admin','user','editor') DEFAULT 'user',
  `country_code` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `first_name`, `last_name`, `role`, `country_code`) VALUES
(1, 'admin', '$2y$10$iwJoNFNtdhFkzWpw2d6FwOi5EKHOGK0jEB81WSe4aCL/Zl3CH5Qge', 'admin@email.com', 'Admin', 'Admin', 'admin', 'HR'),
(2, 'iivic', '$2y$10$/o7CvF45Vgc5s81j7MMkaOdy3vq3Nb.quKNeZ3tsW5c6dFS.993vC', 'ivoivic@email.com', 'Ivo', 'Ivic', 'user', 'HR'),
(4, 'editor', '$2y$10$bACPWcJbVX78ifO.iwX4/e/1.pISUh9sLr0CRIyscnEKDAQ7f6LCS', 'editor@email.com', 'Editor', 'Editor', 'editor', 'FR'),
(5, 'user', '$2y$10$TtcMlbBcByJRPhCULJufzu5yjTMnoyS7LzCHSPhaCNZzcOEv2QeRW', 'user@email.com', 'User', 'User', 'user', 'BA');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_code`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_images`
--
ALTER TABLE `news_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_id` (`news_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_code` (`country_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `news_images`
--
ALTER TABLE `news_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `news_images`
--
ALTER TABLE `news_images`
  ADD CONSTRAINT `news_images_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`country_code`) REFERENCES `countries` (`country_code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
