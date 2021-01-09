-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2017 at 06:10 PM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exam`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `UserID` varchar(10) NOT NULL,
  `Password` varchar(15) NOT NULL,
  `Email` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`UserID`, `Password`, `Email`) VALUES
('Admin', 'Password', 'vikranthjan@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `depatment`
--

CREATE TABLE `depatment` (
  `DeptName` varchar(30) NOT NULL,
  `Dept` char(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `Name` char(30) NOT NULL,
  `UserID` varchar(10) NOT NULL,
  `Designation` varchar(20) NOT NULL,
  `Dept` char(5) NOT NULL,
  `Phone` bigint(10) NOT NULL,
  `Email` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `Name` varchar(30) NOT NULL,
  `UserID` varchar(15) NOT NULL,
  `Gender` char(7) NOT NULL,
  `DateOfBirth` date NOT NULL,
  `Qualification` varchar(10) NOT NULL,
  `Branch` varchar(20) NOT NULL,
  `Year` int(1) NOT NULL,
  `College` varchar(20) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Phone` bigint(10) DEFAULT NULL,
  `Password` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`Name`, `UserID`, `Gender`, `DateOfBirth`, `Qualification`, `Branch`, `Year`, `College`, `Email`, `Phone`, `Password`) VALUES
('J.V.Vikranth', 'Y14IT835', 'Male', '1997-11-16', 'B.Tech', 'I.T', 3, 'RVR&JC', 'vikranthjan@gmail.com', 9493302533, '!1Vikranth'),
('B.Prakash', 'Y14IT812', 'Male', '1997-06-25', 'B.Tech', 'I.T', 3, 'RVR&JC', 'prakashb747@gmail.com', 9490645354, '!1Prakash');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `SubjectName` varchar(30) NOT NULL,
  `Subjt` varchar(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `teachingsubjects`
--

CREATE TABLE `teachingsubjects` (
  `UserID` varchar(10) NOT NULL,
  `Subjt1` varchar(5) NOT NULL,
  `Subjt2` varchar(5) DEFAULT NULL,
  `Subjt3` varchar(5) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD UNIQUE KEY `UserID`` (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `depatment`
--
ALTER TABLE `depatment`
  ADD PRIMARY KEY (`Dept`),
  ADD UNIQUE KEY `DeptName` (`DeptName`),
  ADD UNIQUE KEY `Dept` (`Dept`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`Subjt`);

--
-- Indexes for table `teachingsubjects`
--
ALTER TABLE `teachingsubjects`
  ADD PRIMARY KEY (`UserID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
