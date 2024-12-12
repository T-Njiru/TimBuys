-- Database and Setup
CREATE DATABASE IF NOT EXISTS TimBuys;
USE TimBuys;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Customer Table
CREATE TABLE Customer (
    CustomerID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    DOB DATE NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Contact VARCHAR(20) NOT NULL,
    VerificationCode VARCHAR(10) DEFAULT NULL,
    CodeExpiry DATETIME NOT NULL,
    IsVerified TINYINT(1) DEFAULT 0,
    ResetToken VARCHAR(255) DEFAULT NULL,
    ResetTokenExpiry DATETIME DEFAULT NULL,
    Gender VARCHAR(20)

);

-- County Table
CREATE TABLE County (
    CountyID INT AUTO_INCREMENT PRIMARY KEY,
    CountyName VARCHAR(100) NOT NULL
);

-- City Table
CREATE TABLE City (
    CityID INT AUTO_INCREMENT PRIMARY KEY,
    CityName VARCHAR(100) NOT NULL
);

-- Address Table
CREATE TABLE Address (
    AddressID INT AUTO_INCREMENT PRIMARY KEY,
    HouseNo VARCHAR(20) NOT NULL,
    Street VARCHAR(100) NOT NULL,
    CustomerID INT,
    CityID INT,
    CountyID INT,
    Area VARCHAR(100) NOT NULL,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID),
    FOREIGN KEY (CityID) REFERENCES City(CityID),
    FOREIGN KEY (CountyID) REFERENCES County(CountyID)
);

-- Category Table
CREATE TABLE Category (
    CategoryID INT AUTO_INCREMENT PRIMARY KEY,
    CategoryName VARCHAR(100) NOT NULL
);

-- Insert initial data for Category
INSERT INTO Category (CategoryID, CategoryName) VALUES
(1, 'Electronics'), (2, 'Clothing'), (3, 'Home Appliances');

-- Vendor Table
CREATE TABLE Vendor (
    VendorID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Address VARCHAR(255) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Contact VARCHAR(20) NOT NULL
);

-- Insert initial data for Vendor
INSERT INTO Vendor (VendorID, Name, Address, Email, Password, Contact) VALUES
(1, 'Vendor One', '123 Street', 'vendor1@example.com', 'password123', '0712345678'),
(2, 'Vendor Two', '456 Avenue', 'vendor2@example.com', 'password123', '0723456789'),
(3, 'Vendor Three', '789 Boulevard', 'vendor3@example.com', 'password123', '0734567890');

-- Product Table
CREATE TABLE Product (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    ProductName VARCHAR(255) NOT NULL,
    CategoryID INT,
    ProductImage VARCHAR(255),
    FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID)
);

-- Insert initial data for Product
INSERT INTO Product (ProductID, ProductName, CategoryID, ProductImage) VALUES
(1, 'Smartphone', 1, ''),
(2, 'T-Shirt', 2, ''),
(3, 'Washing Machine', 3, '');

-- VendorProduct Table
CREATE TABLE VendorProduct (
    VendorProductID INT AUTO_INCREMENT PRIMARY KEY,
    VendorID INT,
    ProductID INT,
    Price DECIMAL(10,2) NOT NULL,
    Quantity INT NOT NULL,
    Description TEXT,
    FOREIGN KEY (VendorID) REFERENCES Vendor(VendorID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

-- Insert initial data for VendorProduct
INSERT INTO VendorProduct (VendorProductID, VendorID, ProductID, Price, Quantity, Description) VALUES
(1, 1, 1, 15000.00, 10, 'Latest model smartphone with advanced features.'),
(2, 2, 2, 500.00, 100, 'Comfortable cotton T-shirt in various sizes.'),
(3, 3, 3, 30000.00, 5, 'High-efficiency washing machine with 7kg capacity.');

-- Courier Table
CREATE TABLE Courier (
    CourierID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Contact VARCHAR(20) NOT NULL
);

-- VendorCourier Table
CREATE TABLE VendorCourier (
    VendorCourierID INT AUTO_INCREMENT PRIMARY KEY,
    VendorID INT,
    CourierID INT,
    FOREIGN KEY (VendorID) REFERENCES Vendor(VendorID),
    FOREIGN KEY (CourierID) REFERENCES Courier(CourierID)
);

-- Orders Table
CREATE TABLE `orders` (
    `OrderID` VARCHAR(255) PRIMARY KEY,
    `CustomerID` INT,
    `OrderDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `Address` VARCHAR(255),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

-- OrderedProduct Table
CREATE TABLE `orderedproduct` (
    `OrderedProductID` INT AUTO_INCREMENT PRIMARY KEY,
    `VendorProductID` INT,
    `OrderID` VARCHAR(255),
    `Quantity` INT NOT NULL,
    `Status` VARCHAR(255),
    FOREIGN KEY (VendorProductID) REFERENCES VendorProduct(VendorProductID),
    FOREIGN KEY (OrderID) REFERENCES orders(OrderID)
);

-- SessionID Table
CREATE TABLE `sessionid` (
    `SessionID` VARCHAR(255) PRIMARY KEY,
    `CustomerID` INT,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);
-- Transaction Table
CREATE TABLE `transaction` (
  `ReceiptID` varchar(255) PRIMARY KEY,
  `Phonenumber` varchar(255) NOT NULL,
  `Amount` int(11) NOT NULL,
  `TransactionDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Review Table
CREATE TABLE Review (
    ReviewID INT AUTO_INCREMENT PRIMARY KEY,
    Rating INT NOT NULL CHECK (Rating BETWEEN 1 AND 5),
    Comment TEXT,
    CustomerID INT,
    OrderedProductID INT,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID),
    FOREIGN KEY (OrderedProductID) REFERENCES orderedproduct(OrderedProductID)
);

-- Cart Table
CREATE TABLE Cart (
    CartID INT AUTO_INCREMENT PRIMARY KEY,
    DateCreated DATE NOT NULL,
    CustomerID INT,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

-- CartProduct Table
CREATE TABLE CartProduct (
    CartProductID INT AUTO_INCREMENT PRIMARY KEY,
    VendorProductID INT,
    Quantity INT NOT NULL,
    CartID INT,
    FOREIGN KEY (VendorProductID) REFERENCES VendorProduct(VendorProductID),
    FOREIGN KEY (CartID) REFERENCES Cart(CartID)
);

-- Newsletter Subscribers Table
CREATE TABLE newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    subscribed_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE Customer
ADD COLUMN role_as TINYINT(1) DEFAULT 0;

CREATE TABLE pendingvendors (
    id INT AUTO_INCREMENT PRIMARY KEY,  
    VendorID VARCHAR(255) NOT NULL,     
    Name VARCHAR(255) NOT NULL,       
    Email VARCHAR(255) NOT NULL,       
    Password VARCHAR(255) NOT NULL,    
    Contact VARCHAR(20) NOT NULL,      
    DateRegistered TIMESTAMP DEFAULT CURRENT_TIMESTAMP  
);
ALTER TABLE pendingvendors ADD COLUMN Address VARCHAR(255) NOT NULL;
ALTER TABLE Customer ADD COLUMN created_at DATETIME;

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert counties into the County table
INSERT INTO County (CountyName) VALUES
('Nairobi'),
('Mombasa'),
('Kisumu'),
('Nakuru'),
('Kiambu'),
('Machakos'),
('Nyeri'),
('Uasin Gishu'),
('Kakamega'),
('Meru');

-- Insert cities into the City table
INSERT INTO City (CityName) VALUES
-- Nairobi County
('Nairobi'),
-- Mombasa County
('Mombasa'),
-- Kisumu County
('Kisumu'),
-- Nakuru County
('Nakuru'),
('Naivasha'),
-- Kiambu County
('Thika'),
('Ruiru'),
-- Machakos County
('Machakos'),
('Athi River'),
-- Nyeri County
('Nyeri'),
('Karatina'),
-- Uasin Gishu County
('Eldoret'),
-- Kakamega County
('Kakamega'),
('Mumias'),
-- Meru County
('Meru'),
('Timau');
