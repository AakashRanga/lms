-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2025 at 05:27 AM
-- Server version: 8.0.41
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

CREATE TABLE `assignment` (
  `ass_id` int NOT NULL,
  `c_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `instruction` varchar(255) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `marks` varchar(255) NOT NULL,
  `due_date` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `c_id` int NOT NULL,
  `course_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `course_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`c_id`, `course_name`, `course_code`, `status`, `created_at`) VALUES
(1, 'Data Structures', 'DSA02', 'active', '2025-09-15 04:53:28'),
(2, 'Data Base Management System', 'CSA03', 'active', '2025-09-15 05:05:38'),
(3, 'Embedded Systems', 'EEA01', 'inactive', '2025-09-15 05:38:23'),
(8, 'Data Handling', 'DSA05', 'active', '2025-09-15 06:23:33'),
(9, 'Big Data', 'CSG81', 'active', '2025-09-15 06:26:38');

-- --------------------------------------------------------

--
-- Table structure for table `course_material`
--

CREATE TABLE `course_material` (
  `cm_id` int NOT NULL,
  `course_code` varchar(255) NOT NULL,
  `c_id` varchar(255) NOT NULL,
  `module` text NOT NULL,
  `practise_qst` text NOT NULL,
  `faculty_id` int NOT NULL,
  `launch_course_id` int NOT NULL,
  `created_on` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `launch_courses`
--

CREATE TABLE `launch_courses` (
  `id` int NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_code` varchar(50) NOT NULL,
  `c_id` varchar(255) NOT NULL,
  `seat_allotment` int NOT NULL,
  `duration` varchar(50) NOT NULL,
  `department` varchar(100) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `course_type` varchar(50) NOT NULL,
  `slot` varchar(255) NOT NULL,
  `faculty_id` varchar(255) DEFAULT NULL,
  `faculty_name` varchar(150) NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `launch_courses`
--

INSERT INTO `launch_courses` (`id`, `course_name`, `course_code`, `c_id`, `seat_allotment`, `duration`, `department`, `branch`, `course_type`, `slot`, `faculty_id`, `faculty_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Data Structures', 'DSA02', '1', 40, '3', 'Null', 'Null', 'elective', 'A', '2', 'Dhatchayani', 'pending', '2025-09-17 10:33:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lms_login`
--

CREATE TABLE `lms_login` (
  `u_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `reg_no` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `admin_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lms_login`
--

INSERT INTO `lms_login` (`u_id`, `name`, `email`, `reg_no`, `password`, `mobile`, `department`, `user_type`, `admin_status`, `created_at`) VALUES
(1, 'Aakash', 'ak@gmail.com', '21617', '123', '1234567890', 'ML', 'Student', 'approved', '2025-09-16 04:25:19'),
(2, 'Dhatchayani', 'dhatchu@gmail.com', '22980', '123', '1234567890', 'CSE', 'Faculty', 'approved', '2025-09-16 04:29:21'),
(3, 'Admin', 'ceo@gmail.com', '007', '123', '1234567890', 'CSE', 'Admin', 'approved', '2025-09-16 04:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `mid` int NOT NULL,
  `cm_id` int NOT NULL,
  `chapter_no` varchar(255) NOT NULL,
  `chapter_title` varchar(255) NOT NULL,
  `materials` varchar(255) NOT NULL,
  `flipped_class` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `practise_question`
--

CREATE TABLE `practise_question` (
  `p_id` int NOT NULL,
  `cm_id` int NOT NULL,
  `question` text NOT NULL,
  `option1` varchar(255) NOT NULL,
  `option2` varchar(255) NOT NULL,
  `option3` varchar(255) NOT NULL,
  `option4` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `co_level` varchar(255) NOT NULL,
  `c_id` int NOT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_assignment`
--

CREATE TABLE `student_assignment` (
  `s_ass_id` int NOT NULL,
  `ass_id` int NOT NULL,
  `c_id` int NOT NULL,
  `title` int NOT NULL,
  `instruction` int NOT NULL,
  `notes` int NOT NULL,
  `marks` int NOT NULL,
  `submission_date` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_course_approval`
--

CREATE TABLE `student_course_approval` (
  `stu_ap_id` int NOT NULL,
  `launch_c_id` varchar(255) DEFAULT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `student_reg_no` varchar(255) DEFAULT NULL,
  `slot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'waiting',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student_course_approval`
--

INSERT INTO `student_course_approval` (`stu_ap_id`, `launch_c_id`, `student_name`, `student_reg_no`, `slot`, `status`, `created_at`, `updated_at`) VALUES
(1, '1', 'Sai Amrish', '21617', 'A', 'approved', '2025-09-17 05:22:32', '2025-09-17 05:22:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`ass_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `course_material`
--
ALTER TABLE `course_material`
  ADD PRIMARY KEY (`cm_id`);

--
-- Indexes for table `launch_courses`
--
ALTER TABLE `launch_courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lms_login`
--
ALTER TABLE `lms_login`
  ADD PRIMARY KEY (`u_id`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `practise_question`
--
ALTER TABLE `practise_question`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `student_assignment`
--
ALTER TABLE `student_assignment`
  ADD PRIMARY KEY (`s_ass_id`);

--
-- Indexes for table `student_course_approval`
--
ALTER TABLE `student_course_approval`
  ADD PRIMARY KEY (`stu_ap_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignment`
--
ALTER TABLE `assignment`
  MODIFY `ass_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `c_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `course_material`
--
ALTER TABLE `course_material`
  MODIFY `cm_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `launch_courses`
--
ALTER TABLE `launch_courses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lms_login`
--
ALTER TABLE `lms_login`
  MODIFY `u_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `mid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `practise_question`
--
ALTER TABLE `practise_question`
  MODIFY `p_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_assignment`
--
ALTER TABLE `student_assignment`
  MODIFY `s_ass_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_course_approval`
--
ALTER TABLE `student_course_approval`
  MODIFY `stu_ap_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
