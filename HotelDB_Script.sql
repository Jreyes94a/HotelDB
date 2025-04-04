-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema HotelDB
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema HotelDB
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `HotelDB` DEFAULT CHARACTER SET utf8 ;
USE `HotelDB` ;

-- -----------------------------------------------------
-- Table `HotelDB`.`Hotel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `HotelDB`.`Hotel` (
  `HotelID` INT NULL,
  `Name` VARCHAR(50) NULL,
  `Address` VARCHAR(45) NULL,
  `Phone` VARCHAR(45) NULL,
  `Email` VARCHAR(45) NULL,
  `CheckIn` DATETIME NOT NULL,
  `CheckOut` DATETIME NULL,
  PRIMARY KEY (`HotelID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HotelDB`.`Staff`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `HotelDB`.`Staff` (
  `StaffID` INT NULL,
  `Hotel_HotelID` INT NULL,
  `FirstName` VARCHAR(45) NULL,
  `LastName` VARCHAR(45) NULL,
  `Role` VARCHAR(45) NULL,
  `Salary` DECIMAL(10,2) NULL,
  `DateOfBirth` DATETIME NULL,
  `Phone` INT NULL,
  `Email` VARCHAR(45) NULL,
  `HireDate` DATETIME NULL,
  PRIMARY KEY (`StaffID`, `Hotel_HotelID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HotelDB`.`Guest`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `HotelDB`.`Guest` (
  `GuestID` INT NULL,
  `FirstName` VARCHAR(45) NULL,
  `LastName` VARCHAR(45) NULL,
  `Phone` VARCHAR(45) NULL,
  `Email` VARCHAR(45) NULL,
  PRIMARY KEY (`GuestID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HotelDB`.`RoomType`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `HotelDB`.`RoomType` (
  `TypeID` INT NULL,
  `Name` VARCHAR(45) NULL,
  `Description` VARCHAR(45) NULL,
  `PricePerNight` VARCHAR(45) NULL,
  `Capacity` VARCHAR(45) NULL,
  `RoomTypecol` VARCHAR(45) NULL,
  PRIMARY KEY (`TypeID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HotelDB`.`Room`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `HotelDB`.`Room` (
  `RoomID` INT NULL,
  `HotelID` INT NULL,
  `TypeID` INT NULL,
  `Vacancy` VARCHAR(45) NULL,
  PRIMARY KEY (`RoomID`, `HotelID`, `TypeID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HotelDB`.`Booking`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `HotelDB`.`Booking` (
  `BookingID` INT NULL,
  `Guest_GuestID` INT NULL,
  `Room_RoomID` INT NULL,
  `CheckIn` DATETIME NULL,
  `CheckOut` DATETIME NULL,
  `Price` DECIMAL(10,2) NULL,
  PRIMARY KEY (`BookingID`, `Guest_GuestID`, `Room_RoomID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HotelDB`.`Payment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `HotelDB`.`Payment` (
  `PaymentID` INT NULL,
  `BookingID` INT NULL,
  `Amount` DECIMAL(10,2) NULL,
  `PaymentDate` DATE NULL,
  `PaymentMethod` VARCHAR(50) NULL,
  PRIMARY KEY (`PaymentID`, `BookingID`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
