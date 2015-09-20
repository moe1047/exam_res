-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2015 at 03:36 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `exam_res`
--

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE IF NOT EXISTS `class` (
`class_id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `department_id` int(11) NOT NULL,
  `batch` int(11) NOT NULL,
  `section` varchar(3) NOT NULL,
  `shift` varchar(50) NOT NULL,
  `graduation_year` year(4) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `class_name`, `department_id`, `batch`, `section`, `shift`, `graduation_year`) VALUES
(30, 'ICT ', 11, 7, 'A', 'afternoon', 2015);

-- --------------------------------------------------------

--
-- Table structure for table `class_course`
--

CREATE TABLE IF NOT EXISTS `class_course` (
`class_course_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `cr_hours` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `class_course`
--

INSERT INTO `class_course` (`class_course_id`, `class_id`, `semester_id`, `course_id`, `cr_hours`) VALUES
(1, 30, 1, 7, 4),
(2, 30, 1, 9, 4);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
`course_id` int(11) NOT NULL,
  `course_name` varchar(50) NOT NULL,
  `course_code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `course_name`, `course_code`) VALUES
(1, 'grammar and communication skills', 'flen1000'),
(3, 'basic computer application', 'ict202'),
(5, 'civics and ethical education', 'civ201'),
(6, 'cisco', 'cis1'),
(7, 'artificial intelligence', 'ai'),
(8, 'OOSAD', 'OS1'),
(9, 'ASP', 'ict01');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
`dep_id` int(11) NOT NULL,
  `dep_name` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dep_id`, `dep_name`, `location`) VALUES
(11, 'ICT', 'jig');

-- --------------------------------------------------------

--
-- Table structure for table `mark`
--

CREATE TABLE IF NOT EXISTS `mark` (
  `mark` varchar(5) NOT NULL,
  `fromm` tinyint(4) NOT NULL,
  `too` tinyint(4) NOT NULL,
  `points` float DEFAULT NULL,
`mark_id` int(11) NOT NULL,
  `mark_group_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `mark`
--

INSERT INTO `mark` (`mark`, `fromm`, `too`, `points`, `mark_id`, `mark_group_id`) VALUES
('A+', 95, 100, 4, 11, 1),
('A+', 90, 100, 4, 18, 3),
('A', 90, 94, 4, 20, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mark_group`
--

CREATE TABLE IF NOT EXISTS `mark_group` (
`mark_group_id` int(11) NOT NULL,
  `mark_group_name` varchar(50) NOT NULL,
  `remark` varchar(60) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `mark_group`
--

INSERT INTO `mark_group` (`mark_group_id`, `mark_group_name`, `remark`) VALUES
(1, 'standard', ''),
(3, 'ASp', '');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE IF NOT EXISTS `results` (
`grade_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `result` int(11) NOT NULL,
  `submitted_by` int(100) NOT NULL,
  `submitted_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `remark` varchar(50) DEFAULT NULL,
  `class_course_id` int(11) NOT NULL,
  `mark_group_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`grade_id`, `student_id`, `result`, `submitted_by`, `submitted_date`, `remark`, `class_course_id`, `mark_group_id`) VALUES
(1, 4693, 93, 1, '2015-08-22 08:53:15', '', 1, 1),
(2, 4697, 97, 1, '2015-08-22 08:53:34', '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE IF NOT EXISTS `semester` (
`semester_id` int(11) NOT NULL,
  `semester_name` varchar(50) NOT NULL,
  `ac_year_from` year(4) DEFAULT NULL,
  `ac_year_to` year(4) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`semester_id`, `semester_name`, `ac_year_from`, `ac_year_to`) VALUES
(1, 'semester1', 2014, 2015);

-- --------------------------------------------------------

--
-- Table structure for table `semester_registration`
--

CREATE TABLE IF NOT EXISTS `semester_registration` (
`sms_reg_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `remark` varchar(100) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `semester_registration`
--

INSERT INTO `semester_registration` (`sms_reg_id`, `semester_id`, `student_id`, `remark`) VALUES
(1, 1, 4697, 'NULL'),
(2, 1, 4693, 'NULL');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `student_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `school` varchar(50) NOT NULL,
  `roll_No` varchar(50) NOT NULL,
  `graduated_year` year(4) NOT NULL,
  `graduation_grade` varchar(50) NOT NULL,
  `phone_number` bigint(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `pwd` varchar(100) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `first_name`, `middle_name`, `last_name`, `school`, `roll_No`, `graduated_year`, `graduation_grade`, `phone_number`, `class_id`, `pwd`, `remark`, `active`) VALUES
(4693, 'aisha', 'yusuf', 'hussein', 'ileys', '4567', 2015, 'A', 2524711110, 30, '$2y$10$8l8SzIXoB5od0Sf1..iZzeUKZkQpkbxuD7Ev0JMXGn0tsgox9QqJG', 'another one', 1),
(4697, 'hassan', 'adam', 'ali', 'nuradin', '6554', 1992, 'A', 2524711110, 30, '$2y$10$8l8SzIXoB5od0Sf1..iZzeUKZkQpkbxuD7Ev0JMXGn0tsgox9QqJG', 'a remark', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`user_seq` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `pwd` varchar(200) DEFAULT NULL,
  `acc_group` char(1) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `full_name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_seq`, `user_id`, `pwd`, `acc_group`, `created_date`, `full_name`) VALUES
(1, 'moe', '$2y$10$26sEwyhqRGpwxHEFFKNeM.cLuqTyUqPet63zGJ1AoHRB0RLdp/LBK', 'a', '2015-08-19 22:40:30', 'mohamed abdullah');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class`
--
ALTER TABLE `class`
 ADD PRIMARY KEY (`class_id`), ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `class_course`
--
ALTER TABLE `class_course`
 ADD PRIMARY KEY (`class_course_id`), ADD UNIQUE KEY `class_id` (`class_id`,`semester_id`,`class_course_id`), ADD UNIQUE KEY `class_id_2` (`class_id`,`semester_id`,`course_id`), ADD UNIQUE KEY `class_id_3` (`class_id`,`course_id`), ADD KEY `semester_id` (`semester_id`), ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
 ADD PRIMARY KEY (`course_id`), ADD UNIQUE KEY `course_name` (`course_name`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
 ADD PRIMARY KEY (`dep_id`);

--
-- Indexes for table `mark`
--
ALTER TABLE `mark`
 ADD PRIMARY KEY (`mark_id`), ADD UNIQUE KEY `mark_group_id` (`mark_group_id`,`mark`);

--
-- Indexes for table `mark_group`
--
ALTER TABLE `mark_group`
 ADD PRIMARY KEY (`mark_group_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
 ADD PRIMARY KEY (`grade_id`), ADD KEY `fk_user` (`submitted_by`), ADD KEY `class_course_id` (`class_course_id`), ADD KEY `mark_group_id` (`mark_group_id`), ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
 ADD PRIMARY KEY (`semester_id`), ADD UNIQUE KEY `semester_name` (`semester_name`,`ac_year_from`,`ac_year_to`);

--
-- Indexes for table `semester_registration`
--
ALTER TABLE `semester_registration`
 ADD PRIMARY KEY (`sms_reg_id`), ADD UNIQUE KEY `semester_id` (`semester_id`,`student_id`), ADD KEY `student_id` (`student_id`), ADD KEY `semester_id_2` (`semester_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
 ADD PRIMARY KEY (`student_id`), ADD KEY `student_class_FK` (`class_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`user_seq`), ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `class_course`
--
ALTER TABLE `class_course`
MODIFY `class_course_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
MODIFY `dep_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `mark`
--
ALTER TABLE `mark`
MODIFY `mark_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `mark_group`
--
ALTER TABLE `mark_group`
MODIFY `mark_group_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
MODIFY `semester_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `semester_registration`
--
ALTER TABLE `semester_registration`
MODIFY `sms_reg_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `user_seq` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `class`
--
ALTER TABLE `class`
ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`dep_id`);

--
-- Constraints for table `class_course`
--
ALTER TABLE `class_course`
ADD CONSTRAINT `class_course_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`semester_id`),
ADD CONSTRAINT `class_course_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`),
ADD CONSTRAINT `class_course_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

--
-- Constraints for table `mark`
--
ALTER TABLE `mark`
ADD CONSTRAINT `mark_ibfk_1` FOREIGN KEY (`mark_group_id`) REFERENCES `mark_group` (`mark_group_id`);

--
-- Constraints for table `results`
--
ALTER TABLE `results`
ADD CONSTRAINT `fk_submittedBy` FOREIGN KEY (`submitted_by`) REFERENCES `user` (`user_seq`),
ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`class_course_id`) REFERENCES `class_course` (`class_course_id`),
ADD CONSTRAINT `results_ibfk_3` FOREIGN KEY (`mark_group_id`) REFERENCES `mark_group` (`mark_group_id`),
ADD CONSTRAINT `results_ibfk_4` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `semester_registration`
--
ALTER TABLE `semester_registration`
ADD CONSTRAINT `semester_registration_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`semester_id`),
ADD CONSTRAINT `semester_registration_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
ADD CONSTRAINT `student_class_FK` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
