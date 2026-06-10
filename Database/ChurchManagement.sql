-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 19, 2024 at 01:39 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ChurchManagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `DonationCategories`
--

CREATE TABLE `DonationCategories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `DonationCategories`
--

INSERT INTO `DonationCategories` (`CategoryID`, `CategoryName`, `Description`) VALUES
(1, 'Church Improvement', 'This Donation is intended for the development of the church.');

-- --------------------------------------------------------

--
-- Table structure for table `DonationCategoryLinks`
--

CREATE TABLE `DonationCategoryLinks` (
  `LinkID` int(11) NOT NULL,
  `DonationID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Donations`
--

CREATE TABLE `Donations` (
  `DonationID` int(11) NOT NULL,
  `MemberID` int(11) NOT NULL,
  `MinistryID` int(11) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `DonationDate` date DEFAULT curdate(),
  `IsRecurring` tinyint(1) DEFAULT 0,
  `Frequency` enum('Weekly','Monthly','Quarterly','Annually') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Donations`
--

INSERT INTO `Donations` (`DonationID`, `MemberID`, `MinistryID`, `Amount`, `DonationDate`, `IsRecurring`, `Frequency`) VALUES
(1, 1, 1, 100.00, '2024-11-18', 0, NULL),
(2, 2, 2, 50.00, '2024-11-18', 1, 'Monthly');

-- --------------------------------------------------------

--
-- Table structure for table `Events`
--

CREATE TABLE `Events` (
  `EventID` int(11) NOT NULL,
  `EventName` varchar(100) NOT NULL,
  `EventType` varchar(50) NOT NULL,
  `EventDate` date NOT NULL,
  `EventTime` time NOT NULL,
  `Description` text DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Events`
--

INSERT INTO `Events` (`EventID`, `EventName`, `EventType`, `EventDate`, `EventTime`, `Description`, `CreatedBy`) VALUES
(1, 'Sanchez Burial', 'Other', '2024-11-23', '08:00:00', 'Ilubong', 1);

-- --------------------------------------------------------

--
-- Table structure for table `FinancialReports`
--

CREATE TABLE `FinancialReports` (
  `ReportID` int(11) NOT NULL,
  `ReportDate` date DEFAULT curdate(),
  `TotalDonations` decimal(10,2) NOT NULL,
  `TotalExpenses` decimal(10,2) NOT NULL,
  `NetIncome` decimal(10,2) GENERATED ALWAYS AS (`TotalDonations` - `TotalExpenses`) STORED,
  `CreatedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `FinancialReports`
--

INSERT INTO `FinancialReports` (`ReportID`, `ReportDate`, `TotalDonations`, `TotalExpenses`, `CreatedBy`) VALUES
(1, '2024-11-18', 150.00, 50.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Members`
--

CREATE TABLE `Members` (
  `MemberID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `JoinDate` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Members`
--

INSERT INTO `Members` (`MemberID`, `FirstName`, `LastName`, `Email`, `Phone`, `JoinDate`) VALUES
(1, 'John', 'Doe', 'john.doe@example.com', '1234567890', '2024-11-18'),
(2, 'Jane', 'Smith', 'jane.smith@example.com', '0987654321', '2024-11-18');

-- --------------------------------------------------------

--
-- Table structure for table `Ministries`
--

CREATE TABLE `Ministries` (
  `MinistryID` int(11) NOT NULL,
  `MinistryName` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL,
  `CreatedDate` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Ministries`
--

INSERT INTO `Ministries` (`MinistryID`, `MinistryName`, `Description`, `CreatedDate`) VALUES
(1, 'Youth Ministry', 'Engaging youth in spiritual growth and community service.', '2024-11-18'),
(2, 'Music Ministry', 'Leading worship through music and song.', '2024-11-18');

-- --------------------------------------------------------

--
-- Table structure for table `Receipts`
--

CREATE TABLE `Receipts` (
  `ReceiptID` int(11) NOT NULL,
  `DonationID` int(11) NOT NULL,
  `ReceiptDate` date DEFAULT curdate(),
  `ReceiptFile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('admin','user') DEFAULT 'user',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`UserID`, `Username`, `Password`, `Role`, `CreatedAt`) VALUES
(1, 'admin', '$2y$10$ymOL7N8PKitZlS7WfgAVp.yIWdRUjkkkx2sOpVSVajCCthydOfazK', 'admin', '2024-11-18 16:26:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `DonationCategories`
--
ALTER TABLE `DonationCategories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `DonationCategoryLinks`
--
ALTER TABLE `DonationCategoryLinks`
  ADD PRIMARY KEY (`LinkID`),
  ADD KEY `DonationID` (`DonationID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `Donations`
--
ALTER TABLE `Donations`
  ADD PRIMARY KEY (`DonationID`),
  ADD KEY `MemberID` (`MemberID`),
  ADD KEY `MinistryID` (`MinistryID`);

--
-- Indexes for table `Events`
--
ALTER TABLE `Events`
  ADD PRIMARY KEY (`EventID`),
  ADD UNIQUE KEY `EventDate` (`EventDate`,`EventTime`),
  ADD KEY `CreatedBy` (`CreatedBy`);

--
-- Indexes for table `FinancialReports`
--
ALTER TABLE `FinancialReports`
  ADD PRIMARY KEY (`ReportID`),
  ADD KEY `CreatedBy` (`CreatedBy`);

--
-- Indexes for table `Members`
--
ALTER TABLE `Members`
  ADD PRIMARY KEY (`MemberID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `Ministries`
--
ALTER TABLE `Ministries`
  ADD PRIMARY KEY (`MinistryID`);

--
-- Indexes for table `Receipts`
--
ALTER TABLE `Receipts`
  ADD PRIMARY KEY (`ReceiptID`),
  ADD KEY `DonationID` (`DonationID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `DonationCategories`
--
ALTER TABLE `DonationCategories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `DonationCategoryLinks`
--
ALTER TABLE `DonationCategoryLinks`
  MODIFY `LinkID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Donations`
--
ALTER TABLE `Donations`
  MODIFY `DonationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Events`
--
ALTER TABLE `Events`
  MODIFY `EventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `FinancialReports`
--
ALTER TABLE `FinancialReports`
  MODIFY `ReportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Members`
--
ALTER TABLE `Members`
  MODIFY `MemberID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Ministries`
--
ALTER TABLE `Ministries`
  MODIFY `MinistryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Receipts`
--
ALTER TABLE `Receipts`
  MODIFY `ReceiptID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `DonationCategoryLinks`
--
ALTER TABLE `DonationCategoryLinks`
  ADD CONSTRAINT `donationcategorylinks_ibfk_1` FOREIGN KEY (`DonationID`) REFERENCES `Donations` (`DonationID`) ON DELETE CASCADE,
  ADD CONSTRAINT `donationcategorylinks_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `DonationCategories` (`CategoryID`) ON DELETE CASCADE;

--
-- Constraints for table `Donations`
--
ALTER TABLE `Donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`MemberID`) REFERENCES `Members` (`MemberID`) ON DELETE CASCADE,
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`MinistryID`) REFERENCES `Ministries` (`MinistryID`) ON DELETE CASCADE;

--
-- Constraints for table `Events`
--
ALTER TABLE `Events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`CreatedBy`) REFERENCES `Members` (`MemberID`) ON DELETE SET NULL;

--
-- Constraints for table `FinancialReports`
--
ALTER TABLE `FinancialReports`
  ADD CONSTRAINT `financialreports_ibfk_1` FOREIGN KEY (`CreatedBy`) REFERENCES `Members` (`MemberID`) ON DELETE SET NULL;

--
-- Constraints for table `Receipts`
--
ALTER TABLE `Receipts`
  ADD CONSTRAINT `receipts_ibfk_1` FOREIGN KEY (`DonationID`) REFERENCES `Donations` (`DonationID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
