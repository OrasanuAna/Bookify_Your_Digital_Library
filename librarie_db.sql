-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gazdă: 127.0.0.1
-- Timp de generare: nov. 18, 2024 la 11:33 AM
-- Versiune server: 10.4.32-MariaDB
-- Versiune PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Bază de date: `librarie_db`
--

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `autori`
--

CREATE TABLE `autori` (
  `id` int(11) NOT NULL,
  `nume` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `autori_carti`
--

CREATE TABLE `autori_carti` (
  `id` int(11) NOT NULL,
  `carte_id` int(11) NOT NULL,
  `autor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `carti`
--

CREATE TABLE `carti` (
  `id` int(11) NOT NULL,
  `titlu` varchar(100) NOT NULL,
  `descriere` text NOT NULL,
  `link_pdf` varchar(255) NOT NULL,
  `coperta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `utilizatori`
--

CREATE TABLE `utilizatori` (
  `id` int(11) NOT NULL,
  `nume` varchar(255) NOT NULL,
  `parola` varchar(255) NOT NULL,
  `rol` varchar(50) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `utilizatori`
--

INSERT INTO `utilizatori` (`id`, `nume`, `parola`, `rol`) VALUES
(2, 'Ana', '$2y$10$BACyOuABZxxs53Gj3YwxNugmjUouWo/NQp6cf3HCNgqNuqL1Dy7C6', 'user'),
(4, 'admin', 'admin', 'admin');

--
-- Indexuri pentru tabele eliminate
--

--
-- Indexuri pentru tabele `autori`
--
ALTER TABLE `autori`
  ADD PRIMARY KEY (`id`);

--
-- Indexuri pentru tabele `autori_carti`
--
ALTER TABLE `autori_carti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carte_id` (`carte_id`),
  ADD KEY `autor_id` (`autor_id`);

--
-- Indexuri pentru tabele `carti`
--
ALTER TABLE `carti`
  ADD PRIMARY KEY (`id`);

--
-- Indexuri pentru tabele `utilizatori`
--
ALTER TABLE `utilizatori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nume` (`nume`);

--
-- AUTO_INCREMENT pentru tabele eliminate
--

--
-- AUTO_INCREMENT pentru tabele `autori`
--
ALTER TABLE `autori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pentru tabele `autori_carti`
--
ALTER TABLE `autori_carti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pentru tabele `carti`
--
ALTER TABLE `carti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pentru tabele `utilizatori`
--
ALTER TABLE `utilizatori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constrângeri pentru tabele eliminate
--

--
-- Constrângeri pentru tabele `autori_carti`
--
ALTER TABLE `autori_carti`
  ADD CONSTRAINT `autori_carti_ibfk_1` FOREIGN KEY (`carte_id`) REFERENCES `carti` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `autori_carti_ibfk_2` FOREIGN KEY (`autor_id`) REFERENCES `autori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
