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
  `HotelID` INT NOT NULL,
  `Name` VARCHAR(50) NOT NULL,
  `Address` VARCHAR(45) NOT NULL,
  `Phone` VARCHAR(45) NOT NULL,
  `Email` VARCHAR(45) NOT NULL,
  `CheckIn` DATETIME NOT NULL,
  `CheckOut` DATETIME NULL,
  PRIMARY KEY (`HotelID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HotelDB`.`Staff`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `HotelDB`.`Staff` (
  `StaffID` INT NOT NULL,
  `Hotel_HotelID` INT NOT NULL,
  `FirstName` VARCHAR(45) NULL,
  `LastName` VARCHAR(45) NULL,
  `Role` VARCHAR(45) NULL,
  `Salary` DECIMAL(10,2) NULL,
  `DateOfBirth` DATETIME NULL,
  `Phone` INT NULL,
  `Email` VARCHAR(45) NULL,
  `HireDate` DATETIME NULL,
  PRIMARY KEY (`StaffID`, `Hotel_HotelID`),
  INDEX `fk_Staff_Hotel1_idx` (`Hotel_HotelID` ASC) VISIBLE,
  CONSTRAINT `fk_Staff_Hotel1`
    FOREIGN KEY (`Hotel_HotelID`)
    REFERENCES `HotelDB`.`Hotel` (`HotelID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HotelDB`.`Guest`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `HotelDB`.`Guest` (
  `GuestID` INT NOT NULL,
  `FirstName` VARCHAR(45) NOT NULL,
  `LastName` VARCHAR(45) NOT NULL,
  `Phone` VARCHAR(45) NOT NULL,
  `Email` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`GuestID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HotelDB`.`RoomType`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `HotelDB`.`RoomType` (
  `TypeID` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `Description` VARCHAR(45) NOT NULL,
  `PricePerNight` VARCHAR(45) NOT NULL,
  `Capacity` VARCHAR(45) NOT NULL,
  `RoomTypecol` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`TypeID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HotelDB`.`Room`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `HotelDB`.`Room` (
  `RoomID` INT NOT NULL,
  `HotelID` INT NOT NULL,
  `TypeID` INT NOT NULL,
  `Vacancy` VARCHAR(45) NULL,
  PRIMARY KEY (`RoomID`, `HotelID`, `TypeID`),
  INDEX `fk_Room_Hotel1_idx` (`HotelID` ASC) VISIBLE,
  INDEX `fk_Room_RoomType1_idx` (`TypeID` ASC) VISIBLE,
  CONSTRAINT `fk_Room_Hotel1`
    FOREIGN KEY (`HotelID`)
    REFERENCES `HotelDB`.`Hotel` (`HotelID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Room_RoomType1`
    FOREIGN KEY (`TypeID`)
    REFERENCES `HotelDB`.`RoomType` (`TypeID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HotelDB`.`Booking`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `HotelDB`.`Booking` (
  `BookingID` INT NOT NULL,
  `Guest_GuestID` INT NOT NULL,
  `Room_RoomID` INT NOT NULL,
  `CheckIn` DATETIME NOT NULL,
  `CheckOut` DATETIME NOT NULL,
  `Price` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`BookingID`, `Guest_GuestID`, `Room_RoomID`),
  INDEX `fk_Booking_Room1_idx` (`Room_RoomID` ASC) VISIBLE,
  INDEX `fk_Booking_Guest1_idx` (`Guest_GuestID` ASC) VISIBLE,
  CONSTRAINT `fk_Booking_Room1`
    FOREIGN KEY (`Room_RoomID`)
    REFERENCES `HotelDB`.`Room` (`RoomID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Booking_Guest1`
    FOREIGN KEY (`Guest_GuestID`)
    REFERENCES `HotelDB`.`Guest` (`GuestID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `HotelDB`.`Payment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `HotelDB`.`Payment` (
  `PaymentID` INT NOT NULL,
  `BookingID` INT NOT NULL,
  `Amount` DECIMAL(10,2) NOT NULL,
  `PaymentDate` DATE NOT NULL,
  `PaymentMethod` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`PaymentID`, `BookingID`),
  INDEX `fk_Payment_Booking1_idx` (`BookingID` ASC) VISIBLE,
  CONSTRAINT `fk_Payment_Booking1`
    FOREIGN KEY (`BookingID`)
    REFERENCES `HotelDB`.`Booking` (`BookingID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
