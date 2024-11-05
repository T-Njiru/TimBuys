-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2024 at 11:39 AM
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
-- Database: `timbuys`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `AddressID` int(11) NOT NULL,
  `HouseNo` varchar(20) NOT NULL,
  `Street` varchar(100) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `CityID` int(11) DEFAULT NULL,
  `CountyID` int(11) DEFAULT NULL,
  `Area` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `CartID` int(11) NOT NULL,
  `DateCreated` datetime NOT NULL DEFAULT current_timestamp(),
  `CustomerID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`CartID`, `DateCreated`, `CustomerID`) VALUES
(1, '2024-10-14 15:27:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cartproduct`
--

CREATE TABLE `cartproduct` (
  `CartProductID` int(11) NOT NULL,
  `VendorProductID` int(11) DEFAULT NULL,
  `Quantity` int(11) NOT NULL,
  `CartID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CategoryID`, `CategoryName`) VALUES
(1, 'foods'),
(2, 'fashion');

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `CityID` int(11) NOT NULL,
  `CityName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `county`
--

CREATE TABLE `county` (
  `CountyID` int(11) NOT NULL,
  `CountyName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courier`
--

CREATE TABLE `courier` (
  `CourierID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Contact` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `DOB` date DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Contact` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `FirstName`, `LastName`, `DOB`, `Email`, `Password`, `Contact`) VALUES
(1, 'jeff', 'jefferson', '2018-10-01', 'magic@magic.com', 'jeff135', '+2541234567');

-- --------------------------------------------------------

--
-- Table structure for table `orderedproduct`
--

CREATE TABLE `orderedproduct` (
  `OrderedProductID` int(11) NOT NULL,
  `VendorProductID` int(11) DEFAULT NULL,
  `OrderID` varchar(255) DEFAULT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderedproduct`
--

INSERT INTO `orderedproduct` (`OrderedProductID`, `VendorProductID`, `OrderID`, `Quantity`) VALUES
(23, 1, 'TBY32118', 1),
(24, 1, 'TBY46475', 1),
(25, 2, 'TBY46475', 1),
(28, 1, 'TBY50440', 1),
(29, 2, 'TBY50440', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` varchar(255) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `CustomerID`, `OrderDate`, `Address`) VALUES
('TBY32118', 1, '2024-11-04 22:03:00', '123 Location, Place'),
('TBY46475', 1, '2024-11-04 22:05:39', '123 Location, Place'),
('TBY50440', 1, '2024-11-04 22:19:51', 'Strathmore School');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(100) NOT NULL,
  `CategoryID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ProductID`, `ProductName`, `CategoryID`) VALUES
(1, 'Indomie Chicken Flavor', 1),
(2, 'Shoes', 2);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `ReviewID` int(11) NOT NULL,
  `Rating` int(11) DEFAULT NULL CHECK (`Rating` between 1 and 5),
  `Comment` text DEFAULT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `OrderedProductID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessionid`
--

CREATE TABLE `sessionid` (
  `SessionID` varchar(255) NOT NULL,
  `CustomerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessionid`
--

INSERT INTO `sessionid` (`SessionID`, `CustomerID`) VALUES
('5a4eum5fum1m9j0drqc1p2938u', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `ReceiptID` varchar(255) NOT NULL,
  `Phonenumber` varchar(255) NOT NULL,
  `Amount` int(11) NOT NULL,
  `TransactionDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`ReceiptID`, `Phonenumber`, `Amount`, `TransactionDate`) VALUES
('NdfJ7RT61SV', '25411111111', 1, '2019-12-19 10:21:15');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `VendorID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Contact` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`VendorID`, `Name`, `Address`, `Email`, `Password`, `Contact`) VALUES
(1, 'Jeff', 'Home', 'Jeff@Home.com', 'Password', '1234567890');

-- --------------------------------------------------------

--
-- Table structure for table `vendorcourier`
--

CREATE TABLE `vendorcourier` (
  `VendorCourierID` int(11) NOT NULL,
  `VendorID` int(11) DEFAULT NULL,
  `CourierID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendorproduct`
--

CREATE TABLE `vendorproduct` (
  `VendorProductID` int(11) NOT NULL,
  `VendorID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendorproduct`
--

INSERT INTO `vendorproduct` (`VendorProductID`, `VendorID`, `ProductID`, `Price`, `Quantity`, `Description`) VALUES
(1, 1, 1, 1000.00, 0, 'Easy to Cook'),
(2, 1, 2, 3000.00, 50, 'Theyre good to wear');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`AddressID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `CityID` (`CityID`),
  ADD KEY `CountyID` (`CountyID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `cartproduct`
--
ALTER TABLE `cartproduct`
  ADD PRIMARY KEY (`CartProductID`),
  ADD KEY `VendorProductID` (`VendorProductID`),
  ADD KEY `CartID` (`CartID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`CityID`);

--
-- Indexes for table `county`
--
ALTER TABLE `county`
  ADD PRIMARY KEY (`CountyID`);

--
-- Indexes for table `courier`
--
ALTER TABLE `courier`
  ADD PRIMARY KEY (`CourierID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `orderedproduct`
--
ALTER TABLE `orderedproduct`
  ADD PRIMARY KEY (`OrderedProductID`),
  ADD KEY `VendorProductID` (`VendorProductID`),
  ADD KEY `orderedproduct_ibfk_2` (`OrderID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`ReviewID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `OrderedProductID` (`OrderedProductID`);

--
-- Indexes for table `sessionid`
--
ALTER TABLE `sessionid`
  ADD PRIMARY KEY (`SessionID`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`ReceiptID`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`VendorID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `vendorcourier`
--
ALTER TABLE `vendorcourier`
  ADD PRIMARY KEY (`VendorCourierID`),
  ADD KEY `VendorID` (`VendorID`),
  ADD KEY `CourierID` (`CourierID`);

--
-- Indexes for table `vendorproduct`
--
ALTER TABLE `vendorproduct`
  ADD PRIMARY KEY (`VendorProductID`),
  ADD KEY `VendorID` (`VendorID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `AddressID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `CartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cartproduct`
--
ALTER TABLE `cartproduct`
  MODIFY `CartProductID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `CityID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `county`
--
ALTER TABLE `county`
  MODIFY `CountyID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courier`
--
ALTER TABLE `courier`
  MODIFY `CourierID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orderedproduct`
--
ALTER TABLE `orderedproduct`
  MODIFY `OrderedProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `ReviewID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `VendorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendorcourier`
--
ALTER TABLE `vendorcourier`
  MODIFY `VendorCourierID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendorproduct`
--
ALTER TABLE `vendorproduct`
  MODIFY `VendorProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`),
  ADD CONSTRAINT `address_ibfk_2` FOREIGN KEY (`CityID`) REFERENCES `city` (`CityID`),
  ADD CONSTRAINT `address_ibfk_3` FOREIGN KEY (`CountyID`) REFERENCES `county` (`CountyID`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`);

--
-- Constraints for table `cartproduct`
--
ALTER TABLE `cartproduct`
  ADD CONSTRAINT `cartproduct_ibfk_1` FOREIGN KEY (`VendorProductID`) REFERENCES `vendorproduct` (`VendorProductID`),
  ADD CONSTRAINT `cartproduct_ibfk_2` FOREIGN KEY (`CartID`) REFERENCES `cart` (`CartID`);

--
-- Constraints for table `orderedproduct`
--
ALTER TABLE `orderedproduct`
  ADD CONSTRAINT `orderedproduct_ibfk_1` FOREIGN KEY (`VendorProductID`) REFERENCES `vendorproduct` (`VendorProductID`),
  ADD CONSTRAINT `orderedproduct_ibfk_2` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`OrderedProductID`) REFERENCES `orderedproduct` (`OrderedProductID`);

--
-- Constraints for table `vendorcourier`
--
ALTER TABLE `vendorcourier`
  ADD CONSTRAINT `vendorcourier_ibfk_1` FOREIGN KEY (`VendorID`) REFERENCES `vendor` (`VendorID`),
  ADD CONSTRAINT `vendorcourier_ibfk_2` FOREIGN KEY (`CourierID`) REFERENCES `courier` (`CourierID`);

--
-- Constraints for table `vendorproduct`
--
ALTER TABLE `vendorproduct`
  ADD CONSTRAINT `vendorproduct_ibfk_1` FOREIGN KEY (`VendorID`) REFERENCES `vendor` (`VendorID`),
  ADD CONSTRAINT `vendorproduct_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
