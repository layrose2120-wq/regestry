-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2026 at 04:22 AM
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
-- Database: `student_users`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_course`
--

CREATE TABLE `tbl_course` (
  `ID` int(11) NOT NULL,
  `COURSE` varchar(50) NOT NULL,
  `DESCRIPTION` varchar(255) NOT NULL,
  `SAVED_BY` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_course`
--

INSERT INTO `tbl_course` (`ID`, `COURSE`, `DESCRIPTION`, `SAVED_BY`) VALUES
(4, 'BSIT', 'Bachelor of Science in Information Technology', 0),
(5, 'BSIS', 'Bachelor of Science in Information Systems', 0),
(6, 'BSIS', '', 0),
(7, 'BSIS', '', 0),
(8, '1', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_instructors`
--

CREATE TABLE `tbl_instructors` (
  `ID` int(11) NOT NULL,
  `SALUTATION` varchar(10) NOT NULL,
  `FIRSTNAME` varchar(50) NOT NULL,
  `MIDDLENAME` varchar(50) DEFAULT '',
  `LASTNAME` varchar(50) NOT NULL,
  `PHOTO` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_password_resets`
--

CREATE TABLE `tbl_password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_schoolyear`
--

CREATE TABLE `tbl_schoolyear` (
  `ID` int(11) NOT NULL,
  `SCHOOLYEAR` varchar(50) NOT NULL,
  `SAVED_BY` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_schoolyear`
--

INSERT INTO `tbl_schoolyear` (`ID`, `SCHOOLYEAR`, `SAVED_BY`) VALUES
(1, '2027-2028', 0),
(2, '2026-2027', 0),
(3, '2025-2026', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_section`
--

CREATE TABLE `tbl_section` (
  `ID` int(11) NOT NULL,
  `SECTION` varchar(50) NOT NULL,
  `SAVED_BY` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_section`
--

INSERT INTO `tbl_section` (`ID`, `SECTION`, `SAVED_BY`) VALUES
(1, 'A', 0),
(2, 'B', 0),
(3, 'C', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subject`
--

CREATE TABLE `tbl_subject` (
  `ID` int(11) NOT NULL,
  `COURSE` varchar(100) NOT NULL,
  `YEARLEVEL` varchar(100) NOT NULL,
  `TERM` varchar(100) NOT NULL,
  `CODE` varchar(50) NOT NULL,
  `TITLE` varchar(255) NOT NULL,
  `LECTURE` int(11) NOT NULL,
  `LABORATORY` int(11) NOT NULL,
  `CREDIT` int(11) NOT NULL,
  `GRADE` varchar(50) DEFAULT NULL,
  `PER_REQUISITE` varchar(255) DEFAULT NULL,
  `SAVED_BY` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_subject`
--

INSERT INTO `tbl_subject` (`ID`, `COURSE`, `YEARLEVEL`, `TERM`, `CODE`, `TITLE`, `LECTURE`, `LABORATORY`, `CREDIT`, `GRADE`, `PER_REQUISITE`, `SAVED_BY`) VALUES
(1, '1', '1', '1', '1', '1', 1, 1, 1, '1', '1', 0),
(2, '1', '1', '1', '1', '1', 1, 1, 1, '1', '1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subjects`
--

CREATE TABLE `tbl_subjects` (
  `ID` int(11) NOT NULL,
  `SUBJECTCODE` varchar(50) NOT NULL,
  `SUBJECTNAME` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_subjects`
--

INSERT INTO `tbl_subjects` (`ID`, `SUBJECTCODE`, `SUBJECTNAME`) VALUES
(1, 'IT101', 'Introduction to Computing'),
(2, 'IT102', 'Programming Fundamentals');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_term`
--

CREATE TABLE `tbl_term` (
  `ID` int(11) NOT NULL,
  `TERM` varchar(50) NOT NULL,
  `SAVED_BY` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_term`
--

INSERT INTO `tbl_term` (`ID`, `TERM`, `SAVED_BY`) VALUES
(1, '1st Semester', 0),
(2, '2nd Semester', 0),
(3, 'Summer', 0),
(4, '1st Semester', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `ID` int(11) NOT NULL,
  `FULLNAME` varchar(100) NOT NULL,
  `EMAIL` varchar(255) DEFAULT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  `USERTYPEID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`ID`, `FULLNAME`, `EMAIL`, `USERNAME`, `PASSWORD`, `USERTYPEID`) VALUES
(1, 'System Administrator', NULL, 'admin', 'admin123', 1),
(2, 'Juan Dela Cruz', NULL, 'student1', '$2y$10$hfOXw30ViYYMvuOqyAYGMutMpWU60d6YJER7F7TWusGgR21whIj.C', 5),
(3, 'Ahron Josh Pesimo', 'ajpesimo@gmail.com', 'ahronjosh22', '$2y$10$Zg3oanK9.jVG97VEsGylbOZ9DTIELY8DmqsA7Gj.Sow4wDNy1AMOe', 5),
(4, 'Carl James Pesimo', '', 'carljames24', '$2y$10$dcSG/L8TYGW0gAL2hGbvxus8mRjDZ3M6vJAxYcWr8syPDAa5q0tG6', 5),
(5, 'Ahron Pesimo', 'ajpesimo@gmail.com', 'ahronjosh213', '$2y$10$AaGT2/rL09S8WuiDHUtziuOuNoG6UscBLozRBOcSIu0NQiv9EJXEu', 5),
(6, 'pesimo', 'ajpesimo22@gmail.com', 'ahronjosh25', '$2y$10$daZ/mXf0s1Axcka0NWCfde5JDdmh1WGYyAZy6fRqGdsuipFD1OXyG', 5),
(7, 'Admin One', NULL, 'admin1', '$2y$10$HsK2m9qFRRukXmz2pasN7u.oqpclvQmSqrrg9wOaekuKimrFiuOV.', 1),
(8, 'Admin Two', NULL, 'admin2', 'password123', 1),
(9, 'Admin Three', NULL, 'admin3', 'password123', 1),
(10, 'Registrar One', NULL, 'reg1', '$2y$10$tIpktPWzQBSer6eObXQavOPBPtXSqfhsrYUHRGZAfzlV1G4rYxzO.', 2),
(11, 'Registrar Two', NULL, 'reg2', 'password123', 2),
(12, 'Registrar Three', NULL, 'reg3', 'password123', 2),
(13, 'Dean One', NULL, 'dean1', '$2y$10$X0LlIGi5hD8awyoHtFO/F.AigoVNnvmtyTFBmaz71IJmOVi2Hvib.', 3),
(14, 'Dean Two', NULL, 'dean2', 'password123', 3),
(15, 'Dean Three', NULL, 'dean3', 'password123', 3),
(16, 'Instructor One', NULL, 'inst1', '$2y$10$4p5Rb0HNNLGSB8oeyyr3guGRoT3mBYgEGURQBMJjsCgM7BhTFQoUq', 4),
(17, 'Instructor Two', NULL, 'inst2', 'password123', 4),
(18, 'Instructor Three', NULL, 'inst3', 'password123', 4),
(19, 'Student One', NULL, 'stud1', '$2y$10$MPjhGRbJXCk4qmvJ.hCPnOtdg0sjOTSinc9O1btRcH.GKKIeMAUFW', 5),
(20, 'Student Two', NULL, 'stud2', 'password123', 5),
(21, 'Student Three', NULL, 'stud3', 'password123', 5),
(26, 'ron josh', 'ronjosh@gmail.com', 'josh', '$2y$10$Y2aEPbWceFWc.Z/f1w2JLOhKgJBzoqkKfsVT65z5LI.6DVESod4Gu', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_usertype`
--

CREATE TABLE `tbl_usertype` (
  `ID` int(11) NOT NULL,
  `USERTYPE` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_usertype`
--

INSERT INTO `tbl_usertype` (`ID`, `USERTYPE`) VALUES
(1, 'Admin'),
(2, 'Registrar'),
(3, 'Program Head'),
(4, 'Professor'),
(5, 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_yearlevel`
--

CREATE TABLE `tbl_yearlevel` (
  `ID` int(11) NOT NULL,
  `YEARLEVEL` varchar(50) NOT NULL,
  `SAVED_BY` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_yearlevel`
--

INSERT INTO `tbl_yearlevel` (`ID`, `YEARLEVEL`, `SAVED_BY`) VALUES
(1, '1st Year', 0),
(2, '2nd Year', 0),
(3, '3rd Year', 0),
(4, '4th Year', 0),
(5, '4th Year', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_course`
--
ALTER TABLE `tbl_course`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_instructors`
--
ALTER TABLE `tbl_instructors`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_password_resets`
--
ALTER TABLE `tbl_password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `token` (`token`),
  ADD KEY `email_2` (`email`);

--
-- Indexes for table `tbl_schoolyear`
--
ALTER TABLE `tbl_schoolyear`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_section`
--
ALTER TABLE `tbl_section`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_subject`
--
ALTER TABLE `tbl_subject`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_term`
--
ALTER TABLE `tbl_term`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `USERTYPEID` (`USERTYPEID`);

--
-- Indexes for table `tbl_usertype`
--
ALTER TABLE `tbl_usertype`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_yearlevel`
--
ALTER TABLE `tbl_yearlevel`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_course`
--
ALTER TABLE `tbl_course`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_instructors`
--
ALTER TABLE `tbl_instructors`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_password_resets`
--
ALTER TABLE `tbl_password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_schoolyear`
--
ALTER TABLE `tbl_schoolyear`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_section`
--
ALTER TABLE `tbl_section`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_subject`
--
ALTER TABLE `tbl_subject`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_subjects`
--
ALTER TABLE `tbl_subjects`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_term`
--
ALTER TABLE `tbl_term`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_usertype`
--
ALTER TABLE `tbl_usertype`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_yearlevel`
--
ALTER TABLE `tbl_yearlevel`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD CONSTRAINT `tbl_users_ibfk_1` FOREIGN KEY (`USERTYPEID`) REFERENCES `tbl_usertype` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
