-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2025 at 01:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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

CREATE TABLE `kurssikirjautuminen` (
  `Tunnus` int(11) NOT NULL,
  `Opiskelija` int(11) NOT NULL,
  `Kurssi` int(11) NOT NULL,
  `Kirjautumispäivä` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kurssit`
--

CREATE TABLE `kurssit` (
  `Tunnus` int(11) NOT NULL,
  `Nimi` varchar(30) NOT NULL,
  `Kuvaus` text NOT NULL,
  `Alkupäivä` datetime NOT NULL,
  `Loppupäivä` datetime NOT NULL,
  `Opettaja` int(11) NOT NULL,
  `Tila` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opettajat`
--

CREATE TABLE `opettajat` (
  `Tunnusnumero` int(11) NOT NULL,
  `Etunimi` varchar(30) NOT NULL,
  `Sukunimi` varchar(30) NOT NULL,
  `Aine` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `opiskelijat` (
  `Opiskelijanumero` int(11) NOT NULL,
  `Etunimi` varchar(20) NOT NULL,
  `Sukunimi` varchar(20) NOT NULL,
  `Syntymäpäivä` date NOT NULL,
  `Vuosikurssi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `opiskelijat`
--

INSERT INTO `opiskelijat` (`Opiskelijanumero`, `Etunimi`, `Sukunimi`, `Syntymäpäivä`, `Vuosikurssi`) VALUES
(1001, 'Anna', 'Virtanen', '2003-05-12', 2),
(1002, 'Pekka', 'Korhonen', '2002-11-30', 3),
(1003, 'Mikko', 'Nieminen', '2004-03-22', 1),
(1004, 'Laura', 'Mäkinen', '2003-07-14', 2),
(1005, 'Juha', 'Lahtinen', '2002-09-01', 3),
(1006, 'Sanna', 'Heikkinen', '2004-11-09', 1),
(1007, 'Teemu', 'Salmi', '2003-01-17', 2),
(1008, 'Kaisa', 'Hämäläinen', '2002-06-05', 3),
(1009, 'Janne', 'Lehtonen', '2004-12-21', 1),
(1010, 'Mari', 'Koskinen', '2003-10-10', 2),
(1011, 'Antti', 'Saarinen', '2002-04-08', 3),
(1012, 'Heidi', 'Karjalainen', '2004-08-30', 1),
(1013, 'Jari', 'Ahonen', '2003-06-18', 2),
(1014, 'Eveliina', 'Ojala', '2002-03-25', 3),
(1015, 'Petri', 'Rantanen', '2004-10-03', 1),
(1016, 'Riikka', 'Leino', '2003-09-07', 2),
(1017, 'Tommi', 'Kallio', '2002-01-29', 3),
(1018, 'Noora', 'Turunen', '2004-06-16', 1),
(1019, 'Ville', 'Hirvonen', '2003-12-11', 2),
(1020, 'Jenni', 'Peltola', '2002-02-18', 3),
(1021, 'Timo', 'Laitinen', '2004-05-04', 1),
(1022, 'Emilia', 'Tuominen', '2003-08-23', 2),
(1023, 'Marko', 'Savolainen', '2002-12-02', 3),
(1024, 'Katja', 'Holopainen', '2004-07-27', 1),
(1025, 'Sami', 'Väisänen', '2003-02-15', 2),
(1026, 'Anni', 'Räsänen', '2002-05-26', 3),
(1027, 'Lauri', 'Koivisto', '2004-09-12', 1),
(1028, 'Elina', 'Järvinen', '2003-04-29', 2),
(1029, 'Jukka', 'Eskelinen', '2002-08-16', 3),
(1030, 'Minna', 'Hakala', '2004-01-10', 1),
(1031, 'Olli', 'Manninen', '2003-11-04', 2),
(1032, 'Sari', 'Seppälä', '2002-10-28', 3),
(1033, 'Harri', 'Niskanen', '2004-03-03', 1),
(1034, 'Marja', 'Luoma', '2003-07-19', 2),
(1035, 'Eero', 'Räisänen', '2002-06-22', 3),
(1036, 'Inka', 'Toivonen', '2004-11-15', 1),
(1037, 'Kristian', 'Kärkkäinen', '2003-01-25', 2),
(1038, 'Tuula', 'Mikkola', '2002-03-09', 3),
(1039, 'Niilo', 'Suominen', '2004-10-30', 1),
(1040, 'Heli', 'Kujala', '2003-09-16', 2),
(1041, 'Risto', 'Koponen', '2002-01-12', 3),
(1042, 'Paula', 'Hänninen', '2004-06-08', 1),
(1043, 'Matti', 'Pulli', '2003-12-27', 2),
(1044, 'Liisa', 'Kurki', '2002-04-13', 3),
(1045, 'Arto', 'Alanen', '2004-05-20', 1),
(1046, 'Henna', 'Immonen', '2003-08-08', 2),
(1047, 'Reijo', 'Kaartinen', '2002-11-05', 3),
(1048, 'Outi', 'Hartikainen', '2004-02-11', 1),
(1049, 'Kalle', 'Vainio', '2003-06-01', 2),
(1050, 'Tiina', 'Lilja', '2002-07-14', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tilat`
--

CREATE TABLE `tilat` (
  `Tunnus` int(11) NOT NULL,
  `Nimi` varchar(30) NOT NULL,
  `Kapasiteetti` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `Tunnus` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kurssit`
--
ALTER TABLE `kurssit`
  MODIFY `Tunnus` int(11) NOT NULL AUTO_INCREMENT;

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
