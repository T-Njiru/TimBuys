-- Create the database
CREATE DATABASE TimBuys;
USE TimBuys;

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
    IsVerified TINYINT(1) DEFAULT 0
);

-- County Table (replaces Province)
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

-- Vendor Table
CREATE TABLE Vendor (
    VendorID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Address VARCHAR(255) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Contact VARCHAR(20) NOT NULL
);

-- Product Table
CREATE TABLE Product (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    ProductName VARCHAR(100) NOT NULL,
    CategoryID INT,
    FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID)
);

-- VendorProduct Table
CREATE TABLE VendorProduct (
    VendorProductID INT AUTO_INCREMENT PRIMARY KEY,
    VendorID INT,
    ProductID INT,
    Price DECIMAL(10, 2) NOT NULL,
    Quantity INT NOT NULL,
    Description TEXT,
    FOREIGN KEY (VendorID) REFERENCES Vendor(VendorID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

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
CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT,
    OrderDate DATE NOT NULL,
    AddressID INT,
    VendorCourierID INT,
    TrackingID VARCHAR(50) NOT NULL,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID),
    FOREIGN KEY (AddressID) REFERENCES Address(AddressID),
    FOREIGN KEY (VendorCourierID) REFERENCES VendorCourier(VendorCourierID)
);

-- OrderedProduct Table
CREATE TABLE OrderedProduct (
    OrderedProductID INT AUTO_INCREMENT PRIMARY KEY,
    VendorProductID INT,
    OrderID INT,
    Quantity INT NOT NULL,
    FOREIGN KEY (VendorProductID) REFERENCES VendorProduct(VendorProductID),
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID)
);

-- Review Table
CREATE TABLE Review (
    ReviewID INT AUTO_INCREMENT PRIMARY KEY,
    Rating INT NOT NULL CHECK (Rating BETWEEN 1 AND 5),
    Comment TEXT,
    CustomerID INT,
    OrderedProductID INT,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID),
    FOREIGN KEY (OrderedProductID) REFERENCES OrderedProduct(OrderedProductID)
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

ALTER TABLE Customer
ADD COLUMN ResetToken VARCHAR(255) DEFAULT NULL,
ADD COLUMN ResetTokenExpiry DATETIME DEFAULT NULL,
ADD COLUMN Gender VARCHAR(20) AFTER LastName;
