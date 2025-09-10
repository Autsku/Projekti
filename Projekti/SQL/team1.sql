-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 01:00 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `team1`
--

-- --------------------------------------------------------

--
-- Table structure for table `kurssikirjautuminen`
--

DROP TABLE IF EXISTS `kurssikirjautuminen`;

CREATE TABLE `kurssikirjautuminen` (
  `Tunnus` int(11) NOT NULL,
  `Opiskelija` int(11) NOT NULL,
  `Kurssi` int(11) NOT NULL,
  `Kirjautumispaiva` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Dumping data for table `kurssikirjautuminen`
--

INSERT INTO `kurssikirjautuminen` (`Tunnus`, `Opiskelija`, `Kurssi`, `Kirjautumispaiva`) VALUES
(1, 1, 1, '2025-01-15'),
(2, 1, 2, '2025-02-01'),
(3, 1, 4, '2025-04-03'),
(4, 1, 5, '2025-04-20'),

(5, 2, 1, '2025-01-16'),
(6, 2, 2, '2025-02-05'),
(7, 2, 3, '2025-02-12'),

(8, 3, 2, '2025-02-10'),
(9, 3, 3, '2025-03-05'),

(10, 4, 2, '2025-02-11'),
(11, 4, 5, '2025-05-01'),
(12, 4, 6, '2025-05-05'),

(13, 5, 3, '2025-03-10'),
(14, 5, 1, '2025-01-20'),
(15, 5, 6, '2025-03-22'),

(16, 6, 1, '2025-01-20'),
(17, 6, 2, '2025-01-25'),
(18, 6, 3, '2025-02-20'),
(19, 6, 5, '2025-03-10'),

(20, 7, 3, '2025-03-12'),
(21, 7, 4, '2025-04-01'),
(22, 7, 8, '2025-03-15'),
(23, 7, 2, '2025-04-10'),
(24, 7, 5, '2025-04-15'),
(25, 7, 6, '2025-04-20'),

(26, 8, 2, '2025-02-15'),

(27, 9, 1, '2025-01-22'),
(28, 9, 8, '2025-03-05'),
(29, 9, 5, '2025-03-25'),

(30, 10, 3, '2025-03-18'),

(31, 11, 2, '2025-02-20'),
(32, 11, 5, '2025-05-10'),

(33, 12, 1, '2025-01-25'),
(34, 12, 3, '2025-03-10'),
(35, 12, 4, '2025-03-15'),

(36, 13, 1, '2025-02-15'),
(37, 13, 2, '2025-02-20'),
(38, 13, 3, '2025-03-23'),

(39, 14, 2, '2025-02-25'),
(40, 14, 5, '2025-05-10'),

(41, 15, 1, '2025-01-30'),

(42, 16, 4, '2025-04-05'),
(43, 16, 6, '2025-03-20'),
(44, 16, 7, '2025-02-25'),

(45, 17, 3, '2025-03-10'),
(46, 17, 4, '2025-03-15'),
(47, 17, 5, '2025-05-10'),

(48, 18, 6, '2025-03-20'),

(49, 19, 7, '2025-02-25'),
(50, 19, 1, '2025-01-15'),

(51, 20, 8, '2025-03-05'),

(52, 21, 5, '2025-02-04'),
(53, 21, 2, '2025-02-09'),

(54, 22, 6, '2025-02-05'),
(55, 22, 3, '2025-02-20'),

(56, 23, 2, '2025-02-20'),
(57, 23, 3, '2025-02-22'),
(58, 23, 7, '2025-02-06'),

(59, 24, 3, '2025-02-25'),
(60, 24, 6, '2025-03-01'),
(61, 24, 8, '2025-02-07'),

(62, 25, 1, '2025-02-08'),
(63, 25, 3, '2025-03-10'),

(64, 26, 2, '2025-02-09'),
(65, 26, 3, '2025-02-15'),
(66, 26, 4, '2025-02-20'),

(67, 27, 2, '2025-02-18'),
(68, 27, 3, '2025-02-10'),
(69, 27, 5, '2025-02-25'),

(70, 28, 2, '2025-02-15'),
(71, 28, 3, '2025-02-18'),
(72, 28, 4, '2025-02-11'),

(73, 29, 3, '2025-02-20'),
(74, 29, 5, '2025-02-12'),
(75, 29, 6, '2025-02-25'),

(76, 30, 6, '2025-02-13'),
(77, 30, 3, '2025-03-10'),
(78, 30, 1, '2025-01-15'),

(79, 31, 1, '2025-02-14'),
(80, 31, 2, '2025-02-18'),
(81, 31, 7, '2025-02-14'),

(82, 32, 1, '2025-02-16'),
(83, 32, 4, '2025-02-22'),
(84, 32, 8, '2025-02-15'),

(85, 33, 1, '2025-02-16'),
(86, 33, 5, '2025-03-01'),

(87, 34, 1, '2025-02-20'),
(88, 34, 2, '2025-02-17'),
(89, 34, 3, '2025-02-25'),

(90, 35, 2, '2025-02-18'),
(91, 35, 3, '2025-02-18'),
(92, 35, 4, '2025-02-20'),

(93, 36, 3, '2025-02-21'),
(94, 36, 4, '2025-02-19'),
(95, 36, 5, '2025-02-26'),

(96, 37, 4, '2025-02-22'),
(97, 37, 5, '2025-02-20'),
(98, 37, 6, '2025-02-28'),

(99, 38, 2, '2025-02-23'),
(100, 38, 4, '2025-02-28'),

(101, 39, 7, '2025-02-22'),

(102, 40, 8, '2025-02-23'),

(103, 41, 1, '2025-02-24'),
(104, 41, 5, '2025-03-15'),

(105, 42, 2, '2025-02-25'),

(106, 43, 3, '2025-02-26'),

(107, 44, 4, '2025-02-27'),

(108, 45, 5, '2025-02-28'),

(109, 46, 1, '2025-03-12'),
(110, 46, 2, '2025-03-13'),

(111, 47, 3, '2025-03-14'),

(112, 48, 4, '2025-03-15'),
(113, 48, 7, '2025-03-15'),

(114, 49, 5, '2025-03-16'),

(115, 50, 7, '2025-03-18'),
(116, 50, 1, '2025-03-19'),
(117, 50, 2, '2025-03-20'),
(118, 50, 8, '2025-03-21'),
(119, 50, 4, '2025-03-22'),
(120, 50, 6, '2025-03-17');

-- --------------------------------------------------------

--
-- Table structure for table `kurssit`
--

DROP TABLE IF EXISTS `kurssit`;

CREATE TABLE `kurssit` (
  `Tunnus` int(11) NOT NULL,
  `Nimi` varchar(30) NOT NULL,
  `Kuvaus` text NOT NULL,
  `Alkupaiva` date NOT NULL,
  `Loppupaiva` date NOT NULL,
  `Opettaja` int(11) NOT NULL,
  `Tila` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Dumping data for table `kurssit`
--

INSERT INTO `kurssit` (`Tunnus`, `Nimi`, `Kuvaus`, `Alkupaiva`, `Loppupaiva`, `Opettaja`, `Tila`) VALUES
(1, 'Matematiikka', 'Perus matematiikkaa', '2025-01-10', '2025-05-30', 1, 1),
(2, 'Fysiikka ', 'Perus fysiikkaa', '2025-02-01', '2025-06-15', 2, 2),
(3, 'Historia ', 'Suomen historiaa', '2025-03-05', '2025-07-01', 3, 3),
(4, 'Englanti ', 'Perus englannin kurssi', '2025-04-01', '2025-08-15', 4, 4),
(5, 'Kemia ', 'Perus kemian kurssi', '2025-05-01', '2025-09-01', 5, 5),
(6, 'Äidinkieli ', 'Suomen kieli ja kirjallisuus', '2025-03-15', '2025-07-15', 6, 6),
(7, 'Uskonto ', 'Uskonnon perusteet', '2025-02-20', '2025-06-20', 7, 7),
(8, 'Liikunta ', 'Liikunnan perusteet', '2025-03-01', '2025-07-01', 9, 8);

-- --------------------------------------------------------

--
-- Table structure for table `opettajat`
--
DROP TABLE IF EXISTS `opettajat`;

CREATE TABLE `opettajat` (
  `Tunnusnumero` int(11) NOT NULL,
  `Etunimi` varchar(30) NOT NULL,
  `Sukunimi` varchar(30) NOT NULL,
  `Aine` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Dumping data for table `opettajat`
--

INSERT INTO `opettajat` (`Tunnusnumero`, `Etunimi`, `Sukunimi`, `Aine`) VALUES
(1, 'Tommi', 'Mäkeläinen', 'Matematiikka'),
(2, 'Jaakko', 'Siltanen', 'Fysiikka'),
(3, 'Tiia', 'Savu', 'Historia'),
(4, 'Matti', 'Vuori', 'Englanti'),
(5, 'Liisa', 'Leivo', 'Kemia'),
(6, 'Tuomas', 'Lohi', 'Äidinkieli'),
(7, 'Eemeli', 'Käpy', 'Uskonto'),
(8, 'Janette', 'Tamminen', 'Matematiikka'),
(9, 'Roope', 'Tapiola', 'Liikunta'),
(10, 'Eerika', 'Lahti', 'Äidinkieli');

-- --------------------------------------------------------

--
-- Table structure for table `opiskelijat`
--
DROP TABLE IF EXISTS `opiskelijat`;

CREATE TABLE `opiskelijat` (
  `Opiskelijanumero` int(11) NOT NULL,
  `Etunimi` varchar(20) NOT NULL,
  `Sukunimi` varchar(20) NOT NULL,
  `Syntymapaiva` date NOT NULL,
  `Vuosikurssi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Dumping data for table `opiskelijat`
--

INSERT INTO `opiskelijat` (`Opiskelijanumero`, `Etunimi`, `Sukunimi`, `Syntymapaiva`, `Vuosikurssi`) VALUES
(1, 'Anna', 'Virtanen', '2003-05-12', 2),
(2, 'Pekka', 'Korhonen', '2002-11-30', 3),
(3, 'Mikko', 'Nieminen', '2004-03-22', 1),
(4, 'Laura', 'Mäkinen', '2003-07-14', 2),
(5, 'Juha', 'Lahtinen', '2002-09-01', 3),
(6, 'Sanna', 'Heikkinen', '2004-11-09', 1),
(7, 'Teemu', 'Salmi', '2003-01-17', 2),
(8, 'Kaisa', 'Hämäläinen', '2002-06-05', 3),
(9, 'Janne', 'Lehtonen', '2004-12-21', 1),
(10, 'Mari', 'Koskinen', '2003-10-10', 2),
(11, 'Antti', 'Saarinen', '2002-04-08', 3),
(12, 'Heidi', 'Karjalainen', '2004-08-30', 1),
(13, 'Jari', 'Ahonen', '2003-06-18', 2),
(14, 'Eveliina', 'Ojala', '2002-03-25', 3),
(15, 'Petri', 'Rantanen', '2004-10-03', 1),
(16, 'Riikka', 'Leino', '2003-09-07', 2),
(17, 'Tommi', 'Kallio', '2002-01-29', 3),
(18, 'Noora', 'Turunen', '2004-06-16', 1),
(19, 'Ville', 'Hirvonen', '2003-12-11', 2),
(20, 'Jenni', 'Peltola', '2002-02-18', 3),
(21, 'Timo', 'Laitinen', '2004-05-04', 1),
(22, 'Emilia', 'Tuominen', '2003-08-23', 2),
(23, 'Marko', 'Savolainen', '2002-12-02', 3),
(24, 'Katja', 'Holopainen', '2004-07-27', 1),
(25, 'Sami', 'Väisänen', '2003-02-15', 2),
(26, 'Anni', 'Räsänen', '2002-05-26', 3),
(27, 'Lauri', 'Koivisto', '2004-09-12', 1),
(28, 'Elina', 'Järvinen', '2003-04-29', 2),
(29, 'Jukka', 'Eskelinen', '2002-08-16', 3),
(30, 'Minna', 'Hakala', '2004-01-10', 1),
(31, 'Olli', 'Manninen', '2003-11-04', 2),
(32, 'Sari', 'Seppälä', '2002-10-28', 3),
(33, 'Harri', 'Niskanen', '2004-03-03', 1),
(34, 'Marja', 'Luoma', '2003-07-19', 2),
(35, 'Eero', 'Räisänen', '2002-06-22', 3),
(36, 'Inka', 'Toivonen', '2004-11-15', 1),
(37, 'Kristian', 'Kärkkäinen', '2003-01-25', 2),
(38, 'Tuula', 'Mikkola', '2002-03-09', 3),
(39, 'Niilo', 'Suominen', '2004-10-30', 1),
(40, 'Heli', 'Kujala', '2003-09-16', 2),
(41, 'Risto', 'Koponen', '2002-01-12', 3),
(42, 'Paula', 'Hänninen', '2004-06-08', 1),
(43, 'Matti', 'Pulli', '2003-12-27', 2),
(44, 'Liisa', 'Kurki', '2002-04-13', 3),
(45, 'Arto', 'Alanen', '2004-05-20', 1),
(46, 'Henna', 'Immonen', '2003-08-08', 2),
(47, 'Reijo', 'Kaartinen', '2002-11-05', 3),
(48, 'Outi', 'Hartikainen', '2004-02-11', 1),
(49, 'Kalle', 'Vainio', '2003-06-01', 2),
(50, 'Tiina', 'Lilja', '2002-07-14', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tilat`
--
DROP TABLE IF EXISTS `tilat`;

CREATE TABLE `tilat` (
  `Tunnus` int(11) NOT NULL,
  `Nimi` varchar(30) NOT NULL,
  `Kapasiteetti` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Dumping data for table `tilat`
--

INSERT INTO `tilat` (`Tunnus`, `Nimi`, `Kapasiteetti`) VALUES
(1, 'Luokka C100', 25),
(2, 'Luokka A301', 25),
(3, 'Luokka A302', 25),
(4, 'Luokka H100', 15),
(5, 'Luokka H101', 15),
(6, 'Luokka A209', 30),
(7, 'Luokka A224', 30),
(8, 'Luokka A241', 20),
(9, 'Luokka A338', 25),
(10, 'Auditorio', 60);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kurssikirjautuminen`
--
ALTER TABLE `kurssikirjautuminen`
  ADD PRIMARY KEY (`Tunnus`),
  ADD KEY `fk_opiskelija` (`Opiskelija`),
  ADD KEY `fk_kurssi` (`Kurssi`);

--
-- Indexes for table `kurssit`
--
ALTER TABLE `kurssit`
  ADD PRIMARY KEY (`Tunnus`),
  ADD KEY `fk_opettaja` (`Opettaja`),
  ADD KEY `fk_tila` (`Tila`);

--
-- Indexes for table `opettajat`
--
ALTER TABLE `opettajat`
  ADD PRIMARY KEY (`Tunnusnumero`);

--
-- Indexes for table `opiskelijat`
--
ALTER TABLE `opiskelijat`
  ADD PRIMARY KEY (`Opiskelijanumero`);

--
-- Indexes for table `tilat`
--
ALTER TABLE `tilat`
  ADD PRIMARY KEY (`Tunnus`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kurssikirjautuminen`
--
ALTER TABLE `kurssikirjautuminen`
  MODIFY `Tunnus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `kurssit`
--
ALTER TABLE `kurssit`
  MODIFY `Tunnus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `opettajat`
--
ALTER TABLE `opettajat`
  MODIFY `Tunnusnumero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `opiskelijat`
--
ALTER TABLE `opiskelijat`
  MODIFY `Opiskelijanumero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1051;

--
-- AUTO_INCREMENT for table `tilat`
--
ALTER TABLE `tilat`
  MODIFY `Tunnus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kurssikirjautuminen`
--
ALTER TABLE `kurssikirjautuminen`
  ADD CONSTRAINT `fk_kurssi` FOREIGN KEY (`Kurssi`) REFERENCES `kurssit` (`Tunnus`),
  ADD CONSTRAINT `fk_opiskelija` FOREIGN KEY (`Opiskelija`) REFERENCES `opiskelijat` (`Opiskelijanumero`);

--
-- Constraints for table `kurssit`
--
ALTER TABLE `kurssit`
  ADD CONSTRAINT `fk_opettaja` FOREIGN KEY (`Opettaja`) REFERENCES `opettajat` (`Tunnusnumero`),
  ADD CONSTRAINT `fk_tila` FOREIGN KEY (`Tila`) REFERENCES `tilat` (`Tunnus`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
