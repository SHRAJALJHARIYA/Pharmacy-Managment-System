-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2025 at 04:04 PM
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
-- Database: `pharmadb`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `ID` int(10) NOT NULL,
  `task` varchar(5000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`ID`, `task`) VALUES
(1674, 'Shrajal Jhariya Logged In On '),
(1675, 'Shrajal Jhariya Logged In On '),
(1676, 'Shrajal Jhariya Logged In On '),
(1677, 'user1  Logged In On '),
(1678, 'Shrajal Jhariya Logged In On '),
(1672, 'Shrajal Jhariya Logged In On '),
(1673, 'user1  Logged In On '),
(1669, 'user1  Updated his Profile On 2025-04-28 14:46:54'),
(1670, 'user1  Updated his Profile On 2025-04-28 14:47:11'),
(1671, 'user1  Updated his Profile On 2025-04-28 14:47:21'),
(1538, 'Shrajal Jhariya Updated his Profile On 2025-04-10 09:52:54'),
(1668, 'user1  Updated his Profile On 2025-04-28 14:46:39'),
(1540, 'suresh Logged In On 2025-04-10 09:55:23'),
(1541, 'Shrajal Jhariya Logged In On 2025-04-10 16:58:53'),
(1542, 'Shrajal Jhariya Logged In On 2025-04-10 17:33:17'),
(1543, 'Shrajal Jhariya Logged In On 2025-04-10 17:55:24'),
(1544, 'Shrajal Jhariya Logged In On 2025-04-11 15:40:56'),
(1545, 'Shrajal Jhariya Deleted Category On 2025-04-14 09:46:46'),
(1546, 'Shrajal Jhariya Deleted Category On 2025-04-14 09:46:49'),
(1547, 'Shrajal Jhariya Deleted Category On 2025-04-14 09:46:54'),
(1548, 'Shrajal Jhariya Backup database On 2025-04-14 10:42:32'),
(1549, 'Shrajal Jhariya Backup database On 2025-04-14 10:43:58'),
(1550, 'Shrajal Jhariya Logged In On 2025-04-14 15:00:13'),
(1551, 'user3 Logged In On 2025-04-15 10:20:09'),
(1552, 'Shrajal Jhariya Logged In On 2025-04-15 10:21:53'),
(1553, 'Shrajal Jhariya Deleted Supplier On 2025-04-15 11:33:34'),
(1554, 'Shrajal Jhariya Deleted Supplier On 2025-04-15 11:33:37'),
(1555, 'Shrajal Jhariya Deleted Product On 2025-04-15 11:37:45'),
(1556, 'Shrajal Jhariya Deleted Supplier On 2025-04-15 11:49:02'),
(1557, 'Shrajal Jhariya Deleted Supplier On 2025-04-15 11:49:05'),
(1558, 'Shrajal Jhariya Deleted Supplier On 2025-04-15 13:44:24'),
(1559, 'Shrajal Jhariya Deleted Supplier On 2025-04-15 13:50:02'),
(1560, 'Shrajal Jhariya Deleted Supplier On 2025-04-15 13:50:05'),
(1561, 'Shrajal Jhariya Deleted Supplier On 2025-04-15 13:51:05'),
(1562, 'Shrajal Jhariya Deleted Supplier On 2025-04-15 13:51:39'),
(1563, 'Shrajal Jhariya Deleted Supplier On 2025-04-15 13:53:45'),
(1564, 'Shrajal Jhariya Deleted Supplier On 2025-04-15 13:55:27'),
(1565, 'admin@gmail.com Deleted Supplier On 2025-04-15 13:58:08'),
(1566, 'admin@gmail.com Deleted Supplier On 2025-04-15 13:58:13'),
(1567, 'admin@gmail.com Deleted Supplier On 2025-04-15 13:58:25'),
(1568, 'admin@gmail.com Deleted Supplier On 2025-04-15 14:01:55'),
(1569, 'Shrajal Jhariya Deleted User On 2025-04-15 14:03:03'),
(1570, 'Shrajal Jhariya Deleted Category On 2025-04-15 14:14:34'),
(1571, 'Shrajal Jhariya Deleted Category On 2025-04-15 14:16:18'),
(1572, 'Shrajal Jhariya Deleted Category On 2025-04-15 14:16:24'),
(1573, 'Shrajal Jhariya Updated his Photo On 2025-04-15 20:21:51'),
(1574, 'Shrajal Jhariya Updated his Photo On 2025-04-15 20:22:04'),
(1575, 'Shrajal Jhariya Deleted Category On 2025-04-16 11:45:11'),
(1576, 'Shrajal Jhariya Deleted Category On 2025-04-16 11:45:15'),
(1577, 'Shrajal Jhariya Logged In On 2025-04-16 12:15:16'),
(1578, 'Shrajal Jhariya Deleted Product On 2025-04-16 12:16:25'),
(1579, 'admin@gmail.com Backup database On 2025-04-16 12:45:51'),
(1580, 'admin@gmail.com Backup database On 2025-04-16 12:47:36'),
(1581, 'Shrajal Jhariya Deleted Product On 2025-04-16 12:52:20'),
(1582, 'admin@gmail.com Backup database On 2025-04-16 12:52:32'),
(1583, 'user2 Logged In On 2025-04-16 13:06:09'),
(1584, 'user2 Updated his Photo On 2025-04-16 13:06:42'),
(1585, 'Shrajal Jhariya Logged In On 2025-04-16 13:07:16'),
(1586, 'user1 Logged In On 2025-04-16 13:09:44'),
(1587, 'Shrajal Jhariya Logged In On 2025-04-16 13:11:04'),
(1588, 'Shrajal Jhariya Deleted Product On 2025-04-16 13:22:16'),
(1589, 'Shrajal Jhariya Logged In On 2025-04-16 16:24:04'),
(1590, 'Shrajal Jhariya Logged In On 2025-04-16 17:44:09'),
(1591, 'Shrajal Jhariya Logged In On 2025-04-16 17:44:30'),
(1592, 'Shrajal Jhariya Logged In On 2025-04-16 17:55:14'),
(1593, 'Shrajal Jhariya Logged In On 2025-04-16 17:57:22'),
(1594, 'Shrajal Jhariya Logged Out On 2025-04-16 18:03:39'),
(1595, ' Logged In On 2025-04-16 18:03:46'),
(1596, 'Shrajal Jhariya Logged Out On 2025-04-16 18:07:38'),
(1597, 'Shrajal Jhariya Logged In On 2025-04-16 18:07:44'),
(1598, 'Shrajal Jhariya Logged Out On 2025-04-16 18:10:49'),
(1599, 'Shrajal Jhariya Logged In On 2025-04-16 18:10:54'),
(1600, 'Shrajal Jhariya Logged Out On 2025-04-16 18:18:00'),
(1601, 'Shrajal Jhariya Logged In On 2025-04-16 18:18:06'),
(1602, 'Shrajal Jhariya Deleted Drug On 2025-04-16 18:45:10'),
(1603, 'Shrajal Jhariya Deleted Drug On 2025-04-16 18:45:20'),
(1604, 'Shrajal Jhariya Deleted Drug On 2025-04-16 18:45:25'),
(1605, 'Shrajal Jhariya Changed Password On 2025-04-16 19:18:45'),
(1606, 'Shrajal Jhariya Logged In On 2025-04-16 19:19:20'),
(1607, 'temp singh  Logged In On 2025-04-16 19:20:57'),
(1608, 'temp singh  Logged In On 2025-04-16 19:21:21'),
(1609, 'Shrajal Jhariya Logged In On 2025-04-17 09:29:40'),
(1610, 'Shrajal Jhariya Changed Password On 2025-04-17 15:26:51'),
(1611, 'Shrajal Jhariya Logged In On 2025-04-17 15:27:16'),
(1612, 'Shrajal Jhariya Deleted Product On 2025-04-22 09:18:25'),
(1613, 'Shrajal Jhariya Deleted User On 2025-04-23 06:43:21'),
(1614, 'Shrajal Jhariya Deleted User On 2025-04-23 06:43:29'),
(1615, 'Shrajal Jhariya Deleted Drug On 2025-04-23 07:31:37'),
(1616, 'Shrajal Jhariya Deleted Drug On 2025-04-23 07:34:44'),
(1617, 'Shrajal Jhariya Deleted Drug On 2025-04-23 07:34:49'),
(1618, 'Shrajal Jhariya Deleted Drug On 2025-04-23 07:34:54'),
(1619, 'Shrajal Jhariya Logged In On 2025-04-23 07:38:27'),
(1620, 'Shrajal Jhariya Deleted Drug On 2025-04-23 07:39:18'),
(1621, 'Shrajal Jhariya Deleted Drug On 2025-04-23 07:39:27'),
(1622, 'Shrajal Jhariya Deleted Drug On 2025-04-23 07:39:30'),
(1623, 'Shrajal Jhariya Deleted Drug On 2025-04-23 07:39:33'),
(1624, 'Shrajal Jhariya Deleted Drug On 2025-04-23 07:39:36'),
(1625, 'Shrajal Jhariya Deleted Drug On 2025-04-23 07:39:39'),
(1626, 'Shrajal Jhariya Logged In On 2025-04-23 11:21:42'),
(1627, 'admin@gmail.com Backup database On 2025-04-23 12:12:49'),
(1628, 'Shrajal Jhariya Deleted Drug On 2025-04-23 15:54:44'),
(1629, 'Shrajal Jhariya Deleted Drug On 2025-04-23 15:54:48'),
(1630, 'Shrajal Jhariya Deleted Drug On 2025-04-23 15:54:51'),
(1631, 'Shrajal Jhariya Deleted Drug On 2025-04-23 19:34:40'),
(1632, 'Shrajal Jhariya Logged In On 2025-04-23 19:54:20'),
(1633, 'Shrajal Jhariya Logged In On 2025-04-23 20:13:29'),
(1634, 'Shrajal Jhariya Logged In On 2025-04-23 20:17:57'),
(1635, 'Shrajal Jhariya Logged In On 2025-04-24 03:53:42'),
(1636, 'Shrajal Jhariya Logged In On 2025-04-24 04:05:21'),
(1637, 'User Shrajal Jhariya logged in'),
(1638, 'User Shrajal Jhariya logged in'),
(1639, 'Shrajal Jhariya Logged In On 2025-04-24 04:21:53'),
(1640, 'Shrajal Jhariya Logged In On 2025-04-24 04:30:14'),
(1641, 'Shrajal Jhariya Logged In On '),
(1642, 'Shrajal Jhariya Logged In On '),
(1643, 'Shrajal Jhariya Logged In On '),
(1644, 'Shrajal Jhariya Deleted Product On 2025-04-24 10:15:51'),
(1645, 'admin@gmail.com Backup database On 2025-04-24 10:52:51'),
(1646, 'admin@gmail.com Backup database On 2025-04-24 11:00:35'),
(1647, 'Shrajal Jhariya Logged In On '),
(1648, 'Shrajal Jhariya Deleted Product On 2025-04-24 11:15:12'),
(1649, 'Shrajal Jhariya Deleted Product On 2025-04-24 11:15:23'),
(1650, 'Shrajal Jhariya Deleted Product On 2025-04-24 11:15:31'),
(1651, 'Shrajal Jhariya Deleted Product On 2025-04-24 11:20:26'),
(1652, 'Shrajal Jhariya Deleted Product On 2025-04-24 11:40:18'),
(1653, 'Shrajal Jhariya Deleted Product On 2025-04-24 11:40:24'),
(1654, 'Shrajal Jhariya Deleted Product On 2025-04-24 11:40:29'),
(1655, 'Shrajal Jhariya Deleted Product On 2025-04-24 11:40:32'),
(1656, 'Shrajal Jhariya Deleted Product On 2025-04-24 11:40:38'),
(1657, 'Shrajal Jhariya Deleted Product On 2025-04-24 11:40:45'),
(1658, 'Shrajal Jhariya Deleted Product On 2025-04-24 11:40:51'),
(1659, 'sohan Lal Jhariya  Logged In On '),
(1660, 'sohan Lal Jhariya  Deleted Drug On 2025-04-24 12:29:42'),
(1661, 'Shrajal Jhariya Logged In On '),
(1662, 'Shrajal Jhariya Logged In On '),
(1663, 'user1 Logged In On '),
(1664, 'user1 Updated his Photo On 2025-04-27 10:33:04'),
(1665, 'Shrajal Jhariya Logged In On '),
(1666, 'Shrajal Jhariya Logged In On '),
(1667, 'user1 Logged In On ');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerID` int(11) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(30) DEFAULT NULL,
  `district` varchar(30) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `createdOn` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerID`, `fullName`, `email`, `mobile`, `address`, `city`, `district`, `status`, `createdOn`) VALUES
(1, 'Ravi Sharma', 'ravi.sharma@example.com', 2147483647, '12 MG Road', 'Indore', 'Indore', 'active', '2025-03-31 18:30:00'),
(2, 'Anjali Verma', 'anjali.verma@example.com', 2147483647, '55 Civil Lines', 'Bhopal', 'Bhopal', 'active', '2025-04-01 18:30:00'),
(3, 'Amit Kumar', 'amit.kumar@example.com', 2147483647, '303 Nehru Nagar', 'Lucknow', 'Lucknow', 'inactive', '2025-04-02 18:30:00'),
(4, 'Priya Joshi', 'priya.joshi@example.com', 2147483647, '76 Rajendra Nagar', 'Pune', 'Pune', 'active', '2025-04-03 18:30:00'),
(5, 'Sandeep Singh', 'sandeep.singh@example.com', 2147483647, '88 Sector 15', 'Chandigarh', 'Chandigarh', 'active', '2025-04-04 18:30:00'),
(6, 'Meena Desai', 'meena.desai@example.com', 2147483647, '21 Marine Drive', 'Mumbai', 'Mumbai', 'inactive', '2025-04-05 18:30:00'),
(7, 'Rahul Patel', 'rahul.patel@example.com', 2147483647, 'Green Park', 'Ahmedabad', 'Ahmedabad', 'active', '2025-04-06 18:30:00'),
(8, 'Neha Gupta', 'neha.gupta@example.com', 2147483647, 'Banjara Hills', 'Hyderabad', 'Hyderabad', 'active', '2025-04-07 18:30:00'),
(9, 'Mohit Jain', 'mohit.jain@example.com', 2147483647, '123 Park Street', 'Kolkata', 'Kolkata', 'inactive', '2025-04-08 18:30:00'),
(10, 'Swati Kapoor', 'swati.kapoor@example.com', 2147483647, 'Sector 62', 'Noida', 'Gautam Buddha Nagar', 'active', '2025-04-09 18:30:00'),
(51, 'its me', 'i@gmail.com', 2147483647, 'D', 'D', 'D', 'Active', '2025-04-24 08:19:56'),
(52, 'akshy kumar', '3@gmai.com', 2147483647, 'aa', 'aa', 'a', 'Active', '2025-04-24 08:22:31');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `saleID` int(11) NOT NULL,
  `customerName` varchar(255) NOT NULL,
  `drugName` varchar(255) NOT NULL,
  `saleDate` date NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `unitPrice` float(10,0) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`saleID`, `customerName`, `drugName`, `saleDate`, `quantity`, `unitPrice`) VALUES
(122, 'Anjali Verma', 'Aloe Vera Gel', '2025-04-28', 5, 150),
(123, 'Mohit Jain', 'Benadryl Syrup', '2025-04-28', 10, 11),
(124, 'Neha Gupta', 'Multivitamin Tablets', '2025-04-28', 7, 350),
(125, 'Sandeep Singh', 'Vapor Rub 50g', '2025-04-28', 9, 70);

-- --------------------------------------------------------

--
-- Table structure for table `tblcart`
--

CREATE TABLE `tblcart` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcart`
--

INSERT INTO `tblcart` (`id`, `session_id`, `product_id`, `product_name`, `quantity`, `price`, `total_price`, `created_at`) VALUES
(1, '6vtfi0qbr9uofe75hl8tl8qodj', NULL, NULL, NULL, NULL, 0.00, '2025-04-16 17:30:37');

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `ID` int(4) NOT NULL,
  `category_name` varchar(33) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`ID`, `category_name`) VALUES
(2, 'Antibiotics'),
(6, 'Cough & Cold'),
(7, 'Vitamins & Supplements'),
(8, 'Diabetes Care'),
(10, 'Skin Care');

-- --------------------------------------------------------

--
-- Table structure for table `tblgroup`
--

CREATE TABLE `tblgroup` (
  `ID` int(4) NOT NULL,
  `groupname` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblgroup`
--

INSERT INTO `tblgroup` (`ID`, `groupname`) VALUES
(1, 'Super Admin'),
(2, 'Admin'),
(4, 'Staff'),
(7, 'Accountant');

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct`
--

CREATE TABLE `tblproduct` (
  `ID` int(4) NOT NULL,
  `productID` varchar(15) NOT NULL,
  `product_name` varchar(45) NOT NULL,
  `category` varchar(45) NOT NULL,
  `expirydate` varchar(25) NOT NULL,
  `qty` varchar(10) NOT NULL,
  `price` varchar(12) NOT NULL,
  `photo` varchar(3000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblproduct`
--

INSERT INTO `tblproduct` (`ID`, `productID`, `product_name`, `category`, `expirydate`, `qty`, `price`, `photo`) VALUES
(2, 'P002', 'Amoxicillin 250mg', 'Antibiotics', '2026-04-15', '20', '14', 'uploadImage/Screenshot 2025-04-24 at 14-46-15 Amoxicillin 250mg - Google Search.png'),
(4, 'AB103', 'Azithromycin 500mg', 'Antibiotics', '2025-12-30', '120', '180', 'uploadImage/Azithromycin 500mg.png'),
(5, 'AB104', 'Doxycycline 100mg', 'Antibiotics', '2027-01-05', '90', '130', 'uploadImage/Doxycycline 100mg.png'),
(6, 'P006', 'Benadryl Syrup', 'Cough & Cold', '2026-06-15', '50', '11', 'uploadImage/Screenshot 2025-04-24 at 14-45-28 Benadryl Syrup - Google Search.png'),
(7, 'P007', 'Supradyn Daily', 'Vitamins & Supplements', '2026-07-01', '59', '11', 'uploadImage/Screenshot 2025-04-24 at 14-39-15 Supradyn Daily - Google Search.png'),
(9, 'VS504', 'Omega-3 Capsules', 'Vitamins & Supplements', '2026-08-25', '60', '400', 'uploadImage/omega3.png'),
(11, 'CC202', 'Cold Tablets', 'Cough & Cold', '2026-01-15', '75', '50', 'uploadImage/Cold Tablets.png'),
(12, 'CC203', 'Nasal Spray', 'Cough & Cold', '2025-09-18', '50', '120', 'uploadImage/Nasal Spray.png'),
(13, 'CC204', 'Vapor Rub 50g', 'Cough & Cold', '2027-03-22', '31', '70', 'uploadImage/Vapor Rub 50g.png'),
(15, 'DC302', 'Test Strips (50)', 'Diabetes Care', '2026-11-30', '100', '41', 'uploadImage/test-strip.png'),
(17, 'DC304', 'Sugar-Free Tablets', 'Diabetes Care', '2027-07-15', '200', '180', 'uploadImage/sugerfree-tablates.png'),
(18, 'SC401', 'Moisturizing Cream', 'Skin Care', '2026-12-10', '70', '250', 'uploadImage/Moisturizing Cream.png'),
(19, 'SC402', 'Sunscreen SPF 50', 'Skin Care', '2026-05-20', '60', '300', 'uploadImage/Sunscreen SPF 50.png'),
(20, 'SC403', 'Aloe Vera Gel', 'Skin Care', '2027-08-30', '10', '150', 'uploadImage/alovera gel.png'),
(21, 'SC404', 'Anti-Acne Face Wash', 'Skin Care', '2025-11-11', '50', '220', 'uploadImage/Face Wash - Google Search.png'),
(22, 'VS501', 'Multivitamin Tablets', 'Vitamins & Supplements', '2027-04-01', '93', '350', 'uploadImage/Multivitamin Tablets.png'),
(23, 'VS502', 'Vitamin C Chewables', 'Vitamins & Supplements', '2026-09-12', '90', '180', 'uploadImage/Vitamin C Chewables.png'),
(25, 'CC201', 'Cough Syrup 100ml', 'Cough & Cold', '2025-10-12', '60', '95', 'uploadImage/Cough Syrup 100ml.png');

-- --------------------------------------------------------

--
-- Table structure for table `tblstock`
--

CREATE TABLE `tblstock` (
  `purchaseID` int(11) NOT NULL,
  `productID` int(12) NOT NULL,
  `stockDate` date NOT NULL,
  `drugName` varchar(255) NOT NULL,
  `unitPrice` float NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `expiryDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstock`
--

INSERT INTO `tblstock` (`purchaseID`, `productID`, `stockDate`, `drugName`, `unitPrice`, `quantity`, `expiryDate`) VALUES
(8, 0, '2025-03-22', 'Azithromycin 500mg', 20, 70, '2026-07-12'),
(14, 0, '2025-03-28', 'Benadryl Syrup', 65, 30, '2026-06-15'),
(19, 0, '2025-04-02', 'Supradyn Daily', 25, 40, '2026-07-01'),
(208, 0, '2025-04-24', 'Supradyn Daily', 11, 19, '2026-07-01'),
(210, 0, '2025-04-24', 'Benadryl Syrup', 11, 2, '2026-06-15');

-- --------------------------------------------------------

--
-- Table structure for table `tblsupplier`
--

CREATE TABLE `tblsupplier` (
  `ID` int(4) NOT NULL,
  `supplier_name` varchar(35) NOT NULL,
  `address` varchar(44) NOT NULL,
  `contact_no` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsupplier`
--

INSERT INTO `tblsupplier` (`ID`, `supplier_name`, `address`, `contact_no`) VALUES
(1, 'MediCare Distributors', 'Plot 12, Sanjay Nagar, Delhi', '9810011122'),
(2, 'HealthFirst Pharma', 'Sadar Bazar, Agra', '9834455667'),
(3, 'Sunrise Medical Supplies', 'Anna Salai, Chennai', '9845098450'),
(4, 'Wellness Pharma', 'BT Road, Kolkata', '9741234567'),
(5, 'Apollo Suppliers', 'JP Nagar, Bengaluru', '9887766554'),
(7, 'MedPlus Partners', 'Law Garden, Ahmedabad', '9512345678'),
(8, 'Lifeline Traders', 'Gandhi Nagar, Jaipur', '9696969696'),
(9, 'Aarogya Enterprises', 'Sector 17, Chandigarh', '9876543210'),
(10, 'PharmaLink India', 'Charminar Area, Hyderabad', '9345678912');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(5) NOT NULL,
  `email` varchar(30) NOT NULL,
  `fullname` varchar(40) NOT NULL,
  `password` varchar(15) NOT NULL,
  `lastaccess` datetime DEFAULT NULL,
  `last_ip` varchar(30) NOT NULL,
  `groupname` varchar(30) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `status` varchar(1) NOT NULL,
  `photo` varchar(5000) NOT NULL,
  `timezone` varchar(50) DEFAULT 'Asia/Kolkata'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `email`, `fullname`, `password`, `lastaccess`, `last_ip`, `groupname`, `phone`, `status`, `photo`, `timezone`) VALUES
(1, 'admin@gmail.com', 'Shrajal Jhariya', '1', '2025-05-03 04:41:09', 'NA', 'Super Admin', '08067361023', '1', 'uploadImage/jha-riya.jpg', 'Asia/Kolkata'),
(3, 'user1@gmail.com', 'user1 ', 'user1', '2025-05-03 04:48:15', 'NA', 'Staff', '9922334455', '1', 'uploadImage/Screenshot 2025-04-24 at 16-54-58 old user image - Google Search.png', 'Asia/Kolkata'),
(7, 'sljhariya@gmail.com', 'SL Jhariya ', '1', '2025-04-24 13:08:41', 'NA', 'User', '8085317866', '1', 'uploadImage/Screenshot 2025-04-24 at 16-54-58 old user image - Google Search.png', 'Asia/Kolkata');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerID`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`saleID`);

--
-- Indexes for table `tblcart`
--
ALTER TABLE `tblcart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblgroup`
--
ALTER TABLE `tblgroup`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblproduct`
--
ALTER TABLE `tblproduct`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblstock`
--
ALTER TABLE `tblstock`
  ADD PRIMARY KEY (`purchaseID`);

--
-- Indexes for table `tblsupplier`
--
ALTER TABLE `tblsupplier`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1679;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `saleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `tblcart`
--
ALTER TABLE `tblcart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `ID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `tblgroup`
--
ALTER TABLE `tblgroup`
  MODIFY `ID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblproduct`
--
ALTER TABLE `tblproduct`
  MODIFY `ID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tblstock`
--
ALTER TABLE `tblstock`
  MODIFY `purchaseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;

--
-- AUTO_INCREMENT for table `tblsupplier`
--
ALTER TABLE `tblsupplier`
  MODIFY `ID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
