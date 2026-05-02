-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2026 at 03:54 AM
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
-- Database: `tnts_library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `publisher` varchar(100) DEFAULT NULL,
  `pub_year` int(11) DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `stocks` int(11) DEFAULT 1,
  `location` varchar(50) DEFAULT NULL,
  `format` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `isbn`, `title`, `author`, `publisher`, `pub_year`, `genre`, `stocks`, `location`, `format`, `status`) VALUES
(1, '111-111', 'Effective Java', 'Joshua Bloch', NULL, NULL, 'Software Engineering', 2, 'Shelf A', 'Paperback', 'Available'),
(2, '222-222', 'Designing Data-Intensive Applications', 'Martin Kleppmann', NULL, NULL, 'System Design', 2, 'Shelf B', 'Hardcover', 'Available'),
(3, '333-333', 'Introduction to Algorithms', 'Thomas H. Cormen', NULL, NULL, 'Computer Science', 1, 'Shelf A', 'Hardcover', 'Available'),
(4, '444-444', 'Python Crash Course', 'Eric Matthes', NULL, NULL, 'Programming', 1, 'Shelf C', 'Paperback', 'Available'),
(5, '555-555', 'The Pragmatic Programmer', 'David Thomas', NULL, NULL, 'Software Engineering', 1, 'Shelf B', 'Hardcover', 'Available'),
(6, '666-666', 'Head First Design Patterns', 'Eric Freeman', NULL, NULL, 'Software Engineering', 1, 'Shelf A', 'Paperback', 'Available'),
(7, '777-777', 'High Performance MySQL', 'Silvia Botros', NULL, NULL, 'Databases', 1, 'Shelf B', 'Paperback', 'Available'),
(8, '888-888', 'Deep Learning with Python', 'Francois Chollet', NULL, NULL, 'Artificial Intelligence', 1, 'Shelf C', 'Hardcover', 'Available'),
(9, '999-999', 'Linux Pocket Guide', 'Daniel J. Barrett', NULL, NULL, 'Operating Systems', 1, 'Shelf C', 'Paperback', 'Available'),
(10, '978-013', 'Artificial Intelligence: A Modern Approach', 'Stuart Russell', NULL, NULL, 'Artificial Intelligence', 1, 'Shelf A', 'Hardcover', 'Available'),
(11, '978-103', 'Mastering Python', 'Michael Chen', NULL, NULL, 'Programming', 8, 'Shelf C', 'Paperback', 'Available'),
(12, '978-104', 'Cloud Computing Basics', 'Sarah Jenkins', NULL, NULL, 'Technology', 4, 'Shelf A', 'Hardcover', 'Available'),
(13, '978-105', 'Artificial Intelligence Simplified', 'Kevin Park', NULL, NULL, 'Computer Science', 10, 'Shelf D', 'E-book', 'Available'),
(14, '978-106', 'The DevOps Handbook', 'Elena Rodriguez', NULL, NULL, 'Software Engineering', 2, 'Shelf B', 'Paperback', 'Available'),
(15, '978-107', 'Ethical Hacking Guide', 'David Miller', NULL, NULL, 'Cybersecurity', 6, 'Shelf E', 'Hardcover', 'Available'),
(16, '978-108', 'Web Development with React', 'Aisha Khan', NULL, NULL, 'Technology', 7, 'Shelf C', 'Paperback', 'Available'),
(17, '978-109', 'Machine Learning in Practice', 'Robert Black', NULL, NULL, 'Computer Science', 5, 'Shelf D', 'Hardcover', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `borrowers`
--

CREATE TABLE `borrowers` (
  `id` int(11) NOT NULL,
  `lrn` varchar(12) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `book_borrowed` varchar(255) DEFAULT NULL,
  `date_borrowed` datetime DEFAULT current_timestamp(),
  `due_date` date DEFAULT NULL,
  `return_date` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Borrowed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `report_title` varchar(255) DEFAULT NULL,
  `total_transactions` int(11) DEFAULT NULL,
  `report_summary` text DEFAULT NULL,
  `generated_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `lrn` varchar(12) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `grade_level` varchar(100) NOT NULL,
  `section` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `parent_contact` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`lrn`, `first_name`, `last_name`, `grade_level`, `section`, `contact_number`, `parent_contact`, `email`) VALUES
('128391100032', 'Badong', 'Derit', 'Grade 8', 'B - Tan', '09123456799', '091234567800', 'badongderit@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `tb_credentials`
--

CREATE TABLE `tb_credentials` (
  `UserID` int(11) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` varchar(50) DEFAULT 'Librarian'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_credentials`
--

INSERT INTO `tb_credentials` (`UserID`, `Email`, `Password`, `Role`) VALUES
(1, 'admin', 'admin', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indexes for table `borrowers`
--
ALTER TABLE `borrowers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `borrowers_ibfk_1` (`lrn`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `lrn` (`lrn`);

--
-- Indexes for table `tb_credentials`
--
ALTER TABLE `tb_credentials`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `borrowers`
--
ALTER TABLE `borrowers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_credentials`
--
ALTER TABLE `tb_credentials`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowers`
--
ALTER TABLE `borrowers`
  ADD CONSTRAINT `borrowers_ibfk_1` FOREIGN KEY (`lrn`) REFERENCES `students` (`lrn`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `borrowers_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
