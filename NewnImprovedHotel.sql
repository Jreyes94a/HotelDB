CREATE DATABASE Hotel_DB;

USE Hotel_DB;


CREATE TABLE IF NOT EXISTS Hotel (
    HotelID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(50),
    Address VARCHAR(45),
    Phone VARCHAR(45),
    Email VARCHAR(45)
);


CREATE TABLE IF NOT EXISTS RoomType (
    TypeID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(45),
    Description VARCHAR(255),
    BasePrice DECIMAL(10, 2),
    Capacity INT
);




CREATE TABLE IF NOT EXISTS Room (
    RoomID INT PRIMARY KEY AUTO_INCREMENT,
    HotelID INT,
    TypeID INT,
    RoomNumber VARCHAR(10),
    Vacancy BOOLEAN,
    FOREIGN KEY (HotelID) REFERENCES Hotel(HotelID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (TypeID) REFERENCES RoomType(TypeID) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE IF NOT EXISTS Guest (
    GuestID INT PRIMARY KEY AUTO_INCREMENT,
    FirstName VARCHAR(45),
    LastName VARCHAR(45),
    Phone VARCHAR(45),
    Email VARCHAR(45)
);

select*from guest;


CREATE TABLE IF NOT EXISTS Booking (
    BookingID INT PRIMARY KEY AUTO_INCREMENT,
    GuestID INT,
    RoomID INT,
    Checkin DATETIME,
    Checkout DATETIME,
    NumberOfGuests INT,
    BookingDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    SpecialRequests TEXT,
    TotalPrice DECIMAL(10, 2),
    FOREIGN KEY (GuestID) REFERENCES Guest(GuestID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (RoomID) REFERENCES Room(RoomID) ON DELETE CASCADE ON UPDATE CASCADE
);

select*from booking;	


CREATE TABLE IF NOT EXISTS Payment (
    PaymentID INT PRIMARY KEY AUTO_INCREMENT,
    BookingID INT,
    PaymentDate DATE,
    Amount DECIMAL(10, 2),
    PaymentMethod VARCHAR(50),
    FOREIGN KEY (BookingID) REFERENCES Booking(BookingID) ON DELETE CASCADE ON UPDATE CASCADE
);

select*from payment;


CREATE TABLE IF NOT EXISTS Staff (
    StaffID INT PRIMARY KEY AUTO_INCREMENT,
    HotelID INT,
    FirstName VARCHAR(45),
    LastName VARCHAR(45),
    Role VARCHAR(45),
    Salary DECIMAL(10, 2),
    DateOfBirth DATE,
    Phone VARCHAR(20),
    Email VARCHAR(45),
    HireDate DATE,
    FOREIGN KEY (HotelID) REFERENCES Hotel(HotelID) ON DELETE CASCADE ON UPDATE CASCADE
);


SELECT 
    Room.RoomID, 
    Room.RoomNumber, 
    RoomType.Name AS RoomTypeName, 
    Hotel.Name AS HotelName, 
    RoomType.BasePrice
FROM 
    Room
JOIN 
    RoomType ON Room.TypeID = RoomType.TypeID
JOIN 
    Hotel ON Room.HotelID = Hotel.HotelID;


SELECT 
    Booking.BookingID, 
    Guest.FirstName, 
    Guest.LastName, 
    Room.RoomNumber, 
    Booking.Checkin, 
    Booking.Checkout
FROM 
    Booking
JOIN 
    Guest ON Booking.GuestID = Guest.GuestID
JOIN 
    Room ON Booking.RoomID = Room.RoomID;


SELECT 
    Payment.PaymentID, 
    Guest.FirstName, 
    Guest.LastName, 
    Payment.Amount, 
    Payment.PaymentMethod
FROM 
    Payment
JOIN 
    Booking ON Payment.BookingID = Booking.BookingID
JOIN 
    Guest ON Booking.GuestID = Guest.GuestID;


SELECT 
    Staff.StaffID,
    Staff.FirstName,
    Staff.LastName,
    Staff.Role,
    Hotel.Name AS HotelName
FROM
    Staff
JOIN
    Hotel ON Staff.HotelID = Hotel.HotelID;


SELECT
    Room.RoomID,
    RoomType.Name as RoomTypeName,
    Hotel.Name as HotelName,
    RoomType.BasePrice
FROM
    Room
JOIN
    RoomType ON Room.TypeID = RoomType.TypeID
JOIN
    Hotel ON Room.HotelID = Hotel.HotelID
WHERE
    Room.Vacancy = TRUE;
    
    select * from Guest;
    
    select * from RoomType;

insert into RoomType (Name, BasePrice)
values('Single Bedroom', 200);

insert into RoomType (Name, BasePrice)
Values ('Double Bedroom',300);


insert into RoomType (Name, BasePrice)
values('City View', 400);


SELECT * FROM RoomType WHERE Name = 'Double Bedroom';
SELECT * FROM Room;

SELECT * FROM RoomType WHERE Name = 'Double Bedroom';

SELECT * FROM RoomType;
SELECT * FROM Room;

INSERT INTO Room (TypeID, RoomNumber, Vacancy) VALUES ('2', '201', TRUE);
insert into ROOM (TypeID, RoomNumber,Vacancy) Values('1','101',true);
insert into ROOM (TypeID, RoomNumber,Vacancy) Values('3','301',true);





SELECT TypeID FROM RoomType WHERE Name = 'Double Bedroom';
