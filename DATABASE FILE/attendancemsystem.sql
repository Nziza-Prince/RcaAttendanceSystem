-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2022 at 09:57 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendancemsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`) VALUES
(1, 'Admin', '', 'admin@mail.com', 'D00F5D5217896FB7FD601412CB890830');

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `admissionNo` varchar(255) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmId` varchar(10) NOT NULL,
  `sessionTermId` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL CHECK (`status` IN ('0', '1', '2')),
  `dateTimeTaken` datetime NOT NULL,
  `teacherId` varchar(255) NOT NULL,
  `timeTaken` time NOT NULL,
  `attendanceId` int(10) NOT NULL,
  `teacherwti` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  INDEX (`admissionNo`),
  INDEX (`classId`),
  INDEX (`classArmId`),
  INDEX (`sessionTermId`),
  INDEX (`dateTimeTaken`),
  INDEX (`teacherId`),
  UNIQUE KEY (`admissionNo`, `classId`, `classArmId`, `sessionTermId`, `dateTimeTaken`, `attendanceId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




--
-- Dumping data for table `tblattendance`
--

INSERT INTO `tblattendance` (`admissionNo`, `classId`, `classArmId`, `sessionTermId`, `status`, `dateTimeTaken`, `teacherId`, `timeTaken`, `attendanceId`) VALUES
('ASDFLKJ', '1', '2', '1', '1', '2020-11-01 08:00:00', 'TCH001', '08:00:00', 1),
('AMS148', '4', '6', '1', '0', '2022-06-06 10:00:00', 'TCH003', '10:00:00', 2);


-- --------------------------------------------------------

--
-- Table structure for table `tblclass`
--

CREATE TABLE `tblclass` (
  `Id` int(10) NOT NULL,
  `className` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblclass`
--

INSERT INTO `tblclass` (`Id`, `className`) VALUES
(1, 'Year One'),
(2, 'Year Two'),
(3, 'Year Three');

-- --------------------------------------------------------

--
-- Table structure for table `tblclassarms`
--

CREATE TABLE `tblclassarms` (
  `Id` int(10) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmName` varchar(255) NOT NULL,
  `isAssigned` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblclassarms`
--

INSERT INTO `tblclassarms` (`Id`, `classId`, `classArmName`, `isAssigned`) VALUES
(1, '1', 'A', '0'),
(2, '1', 'B', '0'),
(3, '1', 'C', '0'),
(4, '2', 'A', '0'),
(5, '2', 'B', '0'),
(6, '2', 'C', '0'),
(7, '2', 'D', '0'),
(8, '3', 'A', '0'),
(9, '3', 'B', '0'),
(10, '3', 'C', '0'),
(11, '3', 'D', '0');


-- --------------------------------------------------------

--
-- Table structure for table `tblclassteacher`
--

CREATE TABLE `tblclassteacher` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNo` varchar(50) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmId` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblclassteacher`
--

INSERT INTO `tblclassteacher` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`, `phoneNo`, `classId`, `classArmId`, `dateCreated`) VALUES
(6, 'Teacher', 'Sample', 'teacher@mail.com', 'teach@123', '0100000030', '1', '1', '2022-10-07');

-- --------------------------------------------------------

--
-- Table structure for table `tblsessionterm`
--

CREATE TABLE `tblsessionterm` (
  `Id` int(10) NOT NULL,
  `sessionName` varchar(50) NOT NULL,
  `termId` varchar(50) NOT NULL,
  `isActive` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblsessionterm`
--

INSERT INTO `tblsessionterm` (`Id`, `sessionName`, `termId`, `isActive`, `dateCreated`) VALUES
(1, '2021/2022', '1', '1', '2022-10-31'),
(3, '2021/2022', '2', '0', '2022-10-31');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `Id` int(10) DEFAULT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `admissionNumber` varchar(255) DEFAULT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmId` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblstudents`
--
INSERT INTO `tblstudents` (`Id`, `firstName`, `lastName`, `classId`, `classArmId`, `admissionNumber`) VALUES
(1, 'MUGISHA', 'Ivan Bright', '1', '1', '1'),
(2, 'UHIRIWE', 'Chrisostom', '1', '1', '2'),
(3, 'NIYOBYOSE', 'Isaac Preciuex', '1', '1', '3'),
(4, 'TUYUBAHE', 'Edouard', '1', '1', '4'),
(5, 'HAPPY', 'David', '1', '1', '5'),
(6, 'RUKUNDO', 'Furaha Divin', '1', '1', '6'),
(7, 'CYIZERE', 'Happy', '1', '1', '7'),
(8, 'NKUNDA', 'Isabella', '1', '1', '8'),
(9, 'UMUTONI', 'Uwase Sandra', '1', '1', '9'),
(10, 'IRAKOZE', 'Gikundiro Anitha', '1', '1', '10'),
(11, 'UWASE', 'Teta Paola', '1', '1', '11'),
(12, 'IRIBAGIZA', 'Fanny', '1', '1', '12'),
(13, 'KANEZA', 'Amandine', '1', '1', '13'),
(14, 'NSHUTI ISHIMWE', 'Joseph Angelo', '1', '1', '14'),
(15, 'MUHIZI', 'Lilian Brian', '1', '1', '15'),
(16, 'RUTAGANIRA', 'Ntwali Yanis', '1', '1', '16'),
(17, 'UWASE', 'Sonia', '1', '1', '17'),
(18, 'BYIRINGIRO', 'Aloys', '1', '1', '18'),
(19, 'NIYONKURU', 'Darius', '1', '1', '19'),
(20, 'IRAKOZE', 'Prince Bonheur', '1', '1', '20'),
(21, 'UWASE', 'Utuje Sandrine', '1', '1', '21'),
(22, 'MUNEZERO', 'Impano Christella', '1', '1', '22'),
(23, 'ISHEMA', 'Shimwa Shoulamite', '1', '1', '23'),
(24, 'IGIHOZO', 'Belise', '1', '1', '24'),
(25, 'IYONEZA', 'Larissa Prisca', '1', '1', '25'),
(26, 'IRAKOZE', 'Murasira Berard', '1', '1', '26'),
(27, 'UWUMUGISHA', 'Heloise Rugie', '1', '1', '27'),
(28, 'ABAYO', 'Moise', '1', '2', '28'),
(29, 'ARISANGA ISHIMWE', 'Roger', '1', '2', '29'),
(30, 'BANA', 'Emmy Tresor', '1', '2', '30'),
(31, 'BYIRINGIRO', 'Emmanuel', '1', '2', '31'),
(32, 'CYUBAHIRO', 'Don Durkheim', '1', '2', '32'),
(33, 'GIHOZO RUKUNDO', 'Benise', '1', '2', '33'),
(34, 'IGIRIMPUHWE', 'Noah', '1', '2', '34'),
(35, 'IGITANGAZA', 'Noble Prince', '1', '2', '35'),
(36, 'IKIREZI UNEJUMUTIMA', 'Honorine', '1', '2', '36'),
(37, 'IMANISINGIZWE KAMANA', 'Adeodatus Clare', '1', '2', '37'),
(38, 'IMPANO', 'Blessed Winner', '1', '2', '38'),
(39, 'IRASUBIZA', 'Divine', '1', '2', '39'),
(40, 'IRASUBIZA MUCYO', 'Bertrand', '1', '2', '40'),
(41, 'ISHARA', 'Gold', '1', '2', '41'),
(42, 'ISHIMWE', 'Prince Arnaud', '1', '2', '42'),
(43, 'ISHIMWE', 'Amani Samuel', '1', '2', '43'),
(44, 'IZERE', 'Louise', '1', '2', '44'),
(45, 'IZERE', 'Anna', '1', '2', '45'),
(46, 'KABANDA', 'Jordan', '1', '2', '46'),
(47, 'KANEZA', 'Michele Phoenix', '1', '2', '47'),
(48, 'KAYINAMURA', 'Geofrey', '1', '2', '48'),
(49, 'KEZA', 'Delice', '1', '2', '49'),
(50, 'MUSHIMIYIMANA', 'Henriette', '1', '2', '50'),
(51, 'NIYONKURU', 'Goria', '1', '2', '51'),
(52, 'SIMBI', 'Kelia', '1', '2', '52'),
(53, 'TETA', 'Landra', '1', '2', '53'),
(54, 'TWARIMITSWE', 'Aaron', '1', '2', '54'),
(55, 'WIHOGORA', 'Florence', '1', '2', '55'),
(56, 'AHIMBAZWE MPUHWE', 'Divine Nikita', '1', '3', '56'),
(57, 'ATUMANYIRE', 'Winny Darlen', '1', '3', '57'),
(58, 'BIENVENUE ALLIANCE', 'Dieu d"Amour', '1', '3', '58'),
(59, 'BUGINGO', 'Eric Derick', '1', '3', '59'),
(60, 'BYIRINGIRO', 'Samuel', '1', '3', '60'),
(61, 'CYUZUZO IRAFASHA', 'Sandra', '1', '3', '61'),
(62, 'GANWA', 'Anne Laure', '1', '3', '62'),
(63, 'HATEGEKIMANA', 'Michael Ivan', '1', '3', '63'),
(64, 'HITAYEZU', 'Frank Duff', '1', '3', '64'),
(65, 'IRAKOZE MUKAMA', 'Zion Eloheeka', '1', '3', '65'),
(66, 'ISHIMWE', 'Arlene', '1', '3', '66'),
(67, 'ISHIMWE NZIZA', 'Angelique', '1', '3', '67'),
(68, 'IZERE', 'Joshua', '1', '3', '68'),
(69, 'KALIZA', 'Esther', '1', '3', '69'),
(70, 'KAWACU RUGIRANEZA', 'Arnaud Kennedy', '1', '3', '70'),
(71, 'MUGISHA INEZA', 'Nora', '1', '3', '71'),
(72, 'NDANYUZWE UHIRWA SHAMI', 'Melissa', '1', '3', '72'),
(73, 'NDIZEYE', 'Herve', '1', '3', '73'),
(74, 'NSHUTI MULINDWA', 'Christian', '1', '3', '74'),
(75, 'NTWALI', 'Sasha', '1', '3', '75'),
(76, 'RUSINDANA', 'Tehila', '1', '3', '76'),
(77, 'SHEMA', 'Alain Barsime', '1', '3', '77'),
(78, 'TETA', 'Angel Bless', '1', '3', '78'),
(79, 'UMURERWA', 'Bonnette', '1', '3', '79'),
(80, 'ZIGIRUMUGABE', 'Louis Miguel', '1', '3', '80'),
(81, 'AGAHIRE', 'Nikita', '2', '4', '81'),
(82, 'BIZIMANA ISINGIZWE', 'Christian', '2', '4', '82'),
(83, 'BUGIRI WILSON', 'Goal', '2', '4', '83'),
(84, 'FURAHA NIYONGIRA', 'Celia', '2', '4', '84'),
(85, 'IRADUKUNDA', 'Hope', '2', '4', '85'),
(86, 'IRERE', 'Emmanuel', '2', '4', '86'),
(87, 'IRISA GIRAMATA', 'Kellia', '2', '4', '87'),
(88, 'ISHIMWE', 'Jolie Princesse', '2', '4', '88'),
(89, 'ISHIMWE HIRWA', 'Beni Samuel', '2', '4', '89'),
(90, 'IYAMUREMYE ISHIMWE', 'Sergine', '2', '4', '90'),
(91, 'KWIZERA', 'Aimable', '2', '4', '91'),
(92, 'MANZI GATETE', 'Melvin', '2', '4', '92'),
(93, 'MAZIMPAKA MIGUEL', 'Gloire Marie', '2', '4', '93'),
(94, 'MUGISHA', 'Gemimah Gloire', '2', '4', '94'),
(95, 'MUKUNDWA', 'Aurore', '2', '4', '95'),
(96, 'MUTAGOMA', 'Patience Isimbi', '2', '4', '96'),
(97, 'NDAHIRO MUKUNZI', 'Loicke Bedo', '2', '4', '97'),
(98, 'NDUWINGOMA', 'Marie Ange Gabriella', '2', '4', '98'),
(99, 'NGABO', 'Oreste', '2', '4', '99'),
(100, 'NIBEZA AMAHORO', 'Nicole', '2', '4', '100'),
(101, 'NIYODUSHIMA', 'Belyse', '2', '4', '101'),
(102, 'NIYUBAHWE UWACU', 'Annick', '2', '4', '102'),
(103, 'NSHIMIYIMANA IHIRWE', 'Patrick', '2', '4', '103'),
(104, 'NYIRABISABO KARANGWA', 'Edvine', '2', '4', '104'),
(105, 'NYUMBAYIRE', 'Laurent', '2', '4', '105'),
(106, 'RUTAGANDA', 'Salim', '2', '4', '106'),
(107, 'RWABURINDI', 'Jean Calvin', '2', '4', '107'),
(108, 'TUYUBAHE', 'Ashrafu', '2', '4', '108'),
(109, 'UWONKUNDA', 'Mahinga Rodin', '2', '4', '109'),
(110, 'UWERA', 'Sylvie', '2', '4', '110'),
(111, 'ABIJURU CHANCE', 'Regine', '2', '5', '111'),
(112, 'BAGABO', 'Bonny', '2', '5', '112'),
(113, 'DUFITIMANA', 'Theoneste', '2', '5', '113'),
(114, 'FORGIVENESS', 'Peace Love', '2', '5', '114'),
(115, 'GANZA', 'Chael', '2', '5', '115'),
(116, 'GANZA', 'Eliane Mac Monia', '2', '5', '116'),
(117, 'IGIRANEZA AKAYO', 'Keren', '2', '5', '117'),
(118, 'IRAKOZE NEZERWA', 'Princess', '2', '5', '118'),
(119, 'IRADUKUNDA', 'Joyeuse', '2', '5', '119'),
(120, 'IRANZI', 'Dianah', '2', '5', '120'),
(121, 'IZERE SHEMA', 'Leandre', '2', '5', '121'),
(122, 'KWISHIMA', 'Alain', '2', '5', '122'),
(123, 'KWIZERA', 'Olivier', '2', '5', '123'),
(124, 'MANZI', 'Prince Babou', '2', '5', '124'),
(125, 'MUGISHA', 'Pacifique', '2', '5', '125'),
(126, 'MUGISHA', 'Pascal', '2', '5', '126'),
(127, 'MUGISHA', 'Samuella', '2', '5', '127'),
(128, 'MUHIRE', 'Jessica', '2', '5', '128'),
(129, 'MUHUMURE', 'Bonheur Christian', '2', '5', '129'),
(130, 'MUNEZA', 'Jean Dieudonne', '2', '5', '130'),
(131, 'NKILIYE RUBUTO', 'Yvan', '2', '5', '131'),
(132, 'NKOTANYI NZIZA', 'Prince', '2', '5', '132'),
(133, 'NISHIMWE NDINDABAHIZI', 'Hope', '2', '5', '133'),
(134, 'NIYITANGA', 'Honore', '2', '5', '134'),
(135, 'RUKUNDO BAHATI', 'Samuel', '2', '5', '135'),
(136, 'SHEJA MANENE', 'Junior', '2', '5', '136'),
(137, 'SITAMUSAHAU', 'Ruth', '2', '5', '137'),
(138, 'TURINUMUGISHA GASORE', 'Corene', '2', '5', '138'),
(139, 'URUSARO', 'Narasha', '2', '5', '139'),
(140, 'UWASE', 'Kevine', '2', '5', '140'),
(141, 'UMURUNGI', 'Olga', '2', '6', '141'),
(142, 'ABARUREMA HIRWA', 'Emma Reponse', '2', '6', '142'),
(143, 'AGASARO NDINDA', 'Kessia', '2', '6', '143'),
(144, 'AKEZA', 'Aimee Princesse', '2', '6', '144'),
(145, 'ASIMWE', 'Landry', '2', '6', '145'),
(146, 'ASINGIZWE', 'Benite', '2', '6', '146'),
(147, 'GISA', 'Fred', '2',  '6', '147'),
(148, 'HATUMA', 'Charles', '2', '6', '148'),
(149, 'IGABE MURANGWA', 'Brillante', '2', '6', '149'),
(150, 'IHIMBAZWE NIYIKORA', 'Kevine', '2', '6', '150'),
(151, 'IRASUBIZA', 'Saly Nelson', '2', '6', '151'),
(152, 'KENDY', 'Neilla Gisa', '2', '6', '152'),
(153, 'KIRENGA', 'Kenny', '2', '6', '153'),
(154, 'KWIZERA', 'Albert', '2', '6', '154'),
(155, 'MUGISHA', 'Prosper', '2', '6', '155'),
(156, 'MUHIRWA', 'Reine Kheira', '2', '6', '156'),
(157, 'NIBISHAKA', 'Raphael', '2', '6', '157'),
(158, 'NISHIMWE', 'Cynthia Marie', '2', '6', '158'),
(159, 'NISHIMWE UMUTONIWASE', 'Divine', '2', '6', '159'),
(160, 'NIYOBYOSE', 'Paulin', '2', '6', '160'),
(161, 'NTARE KAYITARE', 'Prince', '2', '6', '161'),
(162, 'NTWARI', 'David', '2', '6', '162'),
(163, 'SEWASE', 'Angel', '2', '6', '163'),
(164, 'SHAMI HIMBAZA', 'Paradie Emmanuella', '2', '6', '164'),
(165, 'TESI', 'Tracy', '2', '6', '165'),
(166, 'TUYISHIME', 'Christian', '2', '6', '166'),
(167, 'UMUMARARUNGU GANZA', 'Darlene', '2', '6', '167'),
(168, 'USANASE', 'Nice Josiane', '2', '6', '168'),
(169, 'UWAYO', 'Ange Kevine', '2', '6', '169'),
(170, 'ABAYO HIRWA', 'Jovin', '2', '7', '170'),
(171, 'AMANI', 'Patrick', '2', '7', '171'),
(172, 'BYUKUSENGE', 'Andre', '2', '7', '172'),
(173, 'DUSHIMIRE', 'Aine', '2', '7', '173'),
(174, 'GANZA RWABUHAMA', 'Danny Mike', '2', '7', '174'),
(175, 'HUMURA', 'Elvin', '2', '7', '175'),
(176, 'IRASUBIZA NTWARI', 'Gloria', '2', '7', '176'),
(177, 'ISHIMWE TETA', 'Liana', '2', '7', '177'),
(178, 'ISIMBI', 'Hyguette', '2', '7', '178'),
(179, 'ISHEMA NKERABAHIZI', 'Love', '2', '7', '179'),
(180, 'ISHIMWE', 'Benitha', '2', '7', '180'),
(181, 'IZABAYO', 'Nadine', '2', '7', '181'),
(182, 'KAGABO', 'Irene Lucky', '2', '7', '182'),
(183, 'KAMAHORO LINDA', 'Kellia', '2', '7', '183'),
(184, 'KIREZI', 'Livia', '2', '7', '184'),
(185, 'MICO', 'Faith', '2', '7', '185'),
(186, 'MIGISHA', 'Ivan', '2', '7', '186'),
(187, 'MUCYO', 'Ivan', '2', '7', '187'),
(188, 'MUGABE INEZA', 'Promesse', '2', '7', '188'),
(189, 'MUGISHA', 'Chrispin', '2', '7', '189'),
(190, 'MUZORA TETA', 'Ciara', '2', '7', '190'),
(191, 'NINSIIMA', 'Angella', '2', '7', '191'),
(192, 'NKUNDABAGENZI', 'Jeremie', '2', '7', '192'),
(193, 'NTWALI', 'Isimbi Vieira', '2', '7', '193'),
(194, 'RUTAYISIRE', 'Gael', '2', '7', '194'),
(195, 'RUYANGE', 'Arnold', '2', '7', '195'),
(196, 'SENGA GLOIRE MARGUERITE', 'Marie', '2', '7', '196'),
(197, 'TETA GATERA', 'Raissa', '2', '7', '197'),
(198, 'UHIRWE', 'Esther Hope', '2', '7', '198'),
(199, 'UWAYO', 'Pascaline', '2', '7', '199'),
(200, 'AKIMANA', 'Viateur', '3', '8', '200'),
(201, 'BELLA', 'Auda Beta', '3', '8', '201'),
(202, 'BIKESHA', 'Cyuzuzo Accarie Davine', '3', '8', '202'),
(203, 'DUHIRIMANA', 'Odile', '3', '8', '203'),
(204, 'DUSABE', 'Iradukunda Elissa', '3', '8', '204'),
(205, 'FILS', 'Alliance Dieudonne', '3', '8', '205'),
(206, 'HIRWA', 'Ghislain', '3', '8', '206'),
(207, 'IBIKORANEZA', 'Dieudonne', '3', '8', '207'),
(208, 'IKIRENGA', 'Mugisha Herve', '3', '8', '208'),
(209, 'INEZA', 'Lucky Believe', '3', '8', '209'),
(210, 'IRADUKUNDA', 'Mustafa', '3', '8', '210'),
(211, 'IRATUZI', 'Benie Giramata', '3', '8', '211'),
(212, 'JABIRO', 'Christelle', '3', '8', '212'),
(213, 'KALINDA', 'Sammy', '3', '8', '213'),
(214, 'KARABO', 'Ineza Emmy Gretta', '3', '8', '214'),
(215, 'MICO', 'Dan', '3', '8', '215'),
(216, 'MUCYO', 'Honorine', '3', '8', '216'),
(217, 'MUGISHA', 'Noel', '3', '8', '217'),
(218, 'MUGISHA', 'Regis', '3', '8', '218'),
(219, 'MUGISHA', 'Yves', '3', '8', '219'),
(220, 'MUKARUSINE', 'Lilian', '3', '8', '220'),
(221, 'MUSHONGANONO', 'Teta Sangwa Assia', '3', '8', '221'),
(222, 'MWESIGYE', 'Teta Linda', '3', '8', '222'),
(223, 'NSENGIYUMVA', 'Nicola', '3', '8', '223'),
(224, 'NSHIMYUMUKIZA', 'Jean De Dieu', '3', '8', '224'),
(225, 'SHIMA', 'Lisa', '3', '8', '225'),
(226, 'UMULISA', 'Ornella', '3', '8', '226'),
(227, 'UMWARI', 'Denyse', '3', '8', '227'),
(228, 'UWAJURU', 'Singizwa Ella', '3', '8', '228'),
(229, 'UWASE', 'Agnes', '3', '8', '229'),
(230, 'ABAYISENGA', 'Aime Pacifique', '3', '9', '230'),
(231, 'ASIFIWE', 'Marie Angele', '3', '9', '231'),
(232, 'BARASINGIZA', 'Yasmine', '3', '9', '232'),
(233, 'BIZIMANA', 'Louange Lidvine', '3', '9', '233'),
(234, 'BYUMVUHORE', 'Aimable', '3', '9', '234'),
(235, 'GITOLI', 'Remy Claudien', '3', '9', '235'),
(236, 'HABIMANA', 'Tony Herve', '3', '9', '236'),
(237, 'HAKIZIMANA', 'Yves', '3', '9', '237'),
(238, 'IMBABAZI', 'Faith', '3', '9', '238'),
(239, 'INEZA', 'Bella Ariane', '3', '9', '239'),
(240, 'INEZA', 'Niyongira Bernice', '3', '9', '240'),
(241, 'INEZAYE MUKIZA', 'Edolyne Exaucee', '3', '9', '241'),
(242, 'IRISA', 'Shimirwa Rolande', '3', '9', '242'),
(243, 'ISAMAZA', 'Sylvain', '3', '9', '243'),
(244, 'ISHEMA', 'Blessing Gianna', '3', '9', '244'),
(245, 'ISHIMWE', 'Angela Lorie', '3', '9', '245'),
(246, 'ISIMBI', 'Nina Henriette', '3', '9', '246'),
(247, 'MUCYO', 'Moses', '3', '9', '247'),
(248, 'MUGABE', 'Jean Aime', '3', '9', '248'),
(249, 'MUGISHA', 'Shami Innocent', '3', '9', '249'),
(250, 'MUSIMENTA', 'Gloria', '3', '9', '250'),
(251, 'NUMWALI', 'Lydia', '3', '9', '251'),
(252, 'NYIRINGABO', 'David', '3', '9', '252'),
(253, 'RUDASESWA', 'Thierry', '3', '9', '253'),
(254, 'RUKUNDO', 'Siborurema Christian', '3', '9', '254'),
(255, 'SEBERA', 'Jonas', '3', '9', '255'),
(256, 'TUYISHIME', 'Naome', '3', '9', '256'),
(257, 'UHIRIWE', 'Anne Leslie', '3', '9', '257'),
(258, 'USANASE', 'Alleluia Queen Doris', '3', '9', '258'),
(259, 'UWASE', 'Seminega Vanessa', '3', '9', '259'),
(260, 'BAGUMIRE', 'Heritier', '3', '10', '260'),
(261, 'BATETE', 'Ange Nadette', '3', '10', '261'),
(262, 'CYIZA', 'Kenny Debrice', '3', '10', '262'),
(263, 'FADHILI', 'Josue', '3', '10', '263'),
(264, 'GANZA', 'Aimee Daniella', '3', '10', '264'),
(265, 'HIRWA', 'Niyonizeye Joric Paladi', '3', '10', '265'),
(266, 'HIRWA', 'Rukundo Hope', '3', '10', '266'),
(267, 'IHIRWE', 'Agasaro Sandra', '3', '10', '267'),
(268, 'INEZA', 'Bella Blandine', '3', '10', '268'),
(269, 'INEZA', 'Cinta Castella', '3', '10', '269'),
(270, 'INEZA', 'Munyaneza Celia', '3', '10', '270'),
(271, 'INEZA', 'Nicole', '3', '10', '271'),
(272, 'ISHEMA', 'Mudahinyuka Hugues', '3', '10', '272'),
(273, 'ISHIMWE', 'Chloe', '3', '10', '273'),
(274, 'ISHIMWE', 'Mugisha Benjamin', '3', '10', '274'),
(275, 'ITANGAMAHORO', 'Divine', '3', '10', '275'),
(276, 'MBABAZI', 'Louange Liza', '3', '10', '276'),
(277, 'MIGISHA', 'Akuzwe Gisele', '3', '10', '277'),
(278, 'MWUNGERE', 'Elite', '3', '10', '278'),
(279, 'NIYITEGEKA', 'Remy Tresor', '3', '10', '279'),
(280, 'NKUNDABAGENZI', 'Bruce', '3', '10', '280'),
(281, 'NKURUNZIZA', 'Hirwa Andy Melvin', '3', '10', '281'),
(282, 'NZABERA', 'Mike Peter', '3', '10', '282'),
(283, 'NZIRORERA', 'Divin', '3', '10', '283'),
(284, 'RUTAGENGWA', 'Isimbi Marie Deborah', '3', '10', '284'),
(285, 'SHEMA', 'Frank', '3', '10', '285'),
(286, 'SIBOMANA', 'Edouard', '3', '10', '286'),
(287, 'UWIMANA', 'Remy Chiessa', '3', '10', '287'),
(288, 'UWIMFURA', 'Zamzam', '3', '10', '288'),
(289, 'UWUMVIYIMANA', 'Asterie', '3', '10', '289'),
(290, 'AKUZWE', 'Sheja Edwige', '3', '11', '290'),
(291, 'BIGIRABAGABO', 'Blaise', '3', '11', '291'),
(292, 'HIRWA', 'Sangwa Sean', '3', '11', '292'),
(293, 'INEZA', 'Gloria', '3', '11', '293'),
(294, 'IRADUKUNDA', 'Bertin', '3', '11', '294'),
(295, 'IRIZA', 'Joella', '3', '11', '295'),
(296, 'KUNDWA', 'Iriza Celia', '3', '11', '296'),
(297, 'IRUMVA', 'Regis Dieu Merci', '3', '11', '297'),
(298, 'ISHIMWE', 'Fabrice', '3', '11', '298'),
(299, 'ISHIMWE', 'Justin', '3', '11', '299'),
(300, 'ITURUSHIMBABAZI', 'Peace Exaucee', '3', '11', '300'),
(301, 'KAYUMBA', 'Jean Marie Vianney', '3', '11', '301'),
(302, 'KIREZI', 'Audrey', '3', '11', '302'),
(303, 'MICOMYIZA', 'Latifa', '3', '11', '303'),
(304, 'MUHIRWA', 'Verygood', '3', '11', '304'),
(305, 'MUNYANEZA', 'Ishimwe Peace', '3', '11', '305'),
(306, 'MUVUNYI', 'Nzivugira Arsene', '3', '11', '306'),
(307, 'NDUWAYO', 'Nathan', '3', '11', '307'),
(308, 'RUKUNDO', 'Prince', '3', '11', '308'),
(309, 'RUSIZANA', 'Mutoni Belinda', '3', '11', '309'),
(310, 'SHIMWA KIREZI', 'Nicaise', '3', '11', '310'),
(311, 'SHIMWAMANA', 'Henri Tresor', '3', '11', '311'),
(312, 'SIBOMANA', 'Elissa', '3', '11', '312'),
(313, 'TUYIZERE', 'Kevin', '3', '11', '313'),
(314, 'UGANASE', 'Ishimwe Arsene', '3', '11', '314'),
(315, 'UMUTONI', 'Kaze Joanna Michelle', '3', '11', '315'),
(316, 'UMUTONI', 'Rita', '3', '11', '316'),
(317, 'USANASE', 'Sheja Joie Darlene', '3', '11', '317'),
(318, 'UWINEZA', 'Honorine', '3', '11', '318');



-- --------------------------------------------------------

--
-- Table structure for table `tblterm`
--

CREATE TABLE `tblterm` (
  `Id` int(10) NOT NULL,
  `termName` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblterm`
--

INSERT INTO `tblterm` (`Id`, `termName`) VALUES
(1, 'First'),
(2, 'Second'),
(3, 'Third');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblattendance`
--

--
-- Indexes for table `tblclass`
--
ALTER TABLE `tblclass`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclassteacher`
--
ALTER TABLE `tblclassteacher`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblterm`
--
ALTER TABLE `tblterm`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblattendance`
--



--
-- AUTO_INCREMENT for table `tblclass`
--
ALTER TABLE `tblclass`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblclassteacher`
--
ALTER TABLE `tblclassteacher`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tblterm`
--
ALTER TABLE `tblterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
