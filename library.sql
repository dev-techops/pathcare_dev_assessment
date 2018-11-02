-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema library_system
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema library_system
-- -----------------------------------------------------
CREATE DATABASE IF NOT EXISTS `library_system` DEFAULT CHARACTER SET utf8 ;
USE `library_system` ;

-- -----------------------------------------------------
-- Table `library_system`.`book`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `library_system`.`book` (
  `bookID` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `author` VARCHAR(255) NOT NULL,
  `yearPublished` YEAR(4) NOT NULL,
  `synopsis` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`bookID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `library_system`.`member`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `library_system`.`member` (
  `memberID` INT(11) NOT NULL AUTO_INCREMENT,
  `memberName` VARCHAR(255) NOT NULL,
  `memberUsername` VARCHAR(255) NOT NULL,
  `memberPassword` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`memberID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `library_system`.`book_loans`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `library_system`.`book_loans` (
  `loanID` INT(11) NOT NULL AUTO_INCREMENT,
  `bookID` INT(11) NOT NULL,
  `memberID` INT(11) NOT NULL,
  `dateLoaned` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`loanID`),
  INDEX `book_id_idx` (`bookID` ASC),
  INDEX `member_id_idx` (`memberID` ASC),
  CONSTRAINT `book_id`
    FOREIGN KEY (`bookID`)
    REFERENCES `library_system`.`book` (`bookID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `member_id`
    FOREIGN KEY (`memberID`)
    REFERENCES `library_system`.`member` (`memberID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Routines
-- -----------------------------------------------------
-- -----------------------------------------------------
-- INSERTS
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Add a new book record
-- -----------------------------------------------------
DROP PROCEDURE IF EXISTS `library_system`.`newBook`;

DELIMITER //
CREATE PROCEDURE `newBook`
	(IN bookTitle VARCHAR(255),
	 IN bookAuthor VARCHAR(255),
     IN year_published YEAR(4),
	 IN bookSynopsis VARCHAR(500))
BEGIN
        
	INSERT INTO  `library_system`.`book`
    (`title`,
	 `author`,
	 `yearPublished`,
	 `synopsis`)
    VALUES
    (bookTitle,
	 bookAuthor,
	 year_published,
	 bookSynopsis);
	 
END
//
DELIMITER ;


-- -----------------------------------------------------
-- Add a new member record
-- -----------------------------------------------------
DROP PROCEDURE IF EXISTS `library_system`.`newMember`;

DELIMITER //
CREATE PROCEDURE `newMember`
	(IN name VARCHAR(255),
	 IN username VARCHAR(255),
	 IN password VARCHAR(255))
BEGIN
        
	INSERT INTO  `library_system`.`member`
    (`memberName`,
	 `memberUsername`,
	 `memberPassword`)
    VALUES
    (name,
	 username,
	 password);
	 
END
//
DELIMITER ;


-- -----------------------------------------------------
-- Add a new book loan record
-- -----------------------------------------------------
DROP PROCEDURE IF EXISTS `library_system`.`newBookLoan`;

DELIMITER //
CREATE PROCEDURE `newBookLoan`
	(IN bkID INT(11),
	 IN mbrID INT(11))
BEGIN
        
	INSERT INTO  `library_system`.`book_loans`
    (`bookID`,
	 `memberID`)
    VALUES
    (bkID,
	 mbrID);
	 
END
//
DELIMITER ;


-- -----------------------------------------------------
-- SELECTS
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Read selected book
-- -----------------------------------------------------
DROP PROCEDURE IF EXISTS `library_system`.`readSelectedBook`;

DELIMITER //
CREATE PROCEDURE `readSelectedBook`
	(IN bkID INT(11)) 

BEGIN 
	
	SELECT *
	FROM `library_system`.`book`
	WHERE `bookID` = bkID;
	
END
//
DELIMITER ;


-- -----------------------------------------------------
-- Read all books
-- -----------------------------------------------------
DROP PROCEDURE IF EXISTS `library_system`.`readAllBooks`;

DELIMITER //
CREATE PROCEDURE `readAllBooks`() 

BEGIN 
	
	SELECT *
	FROM `library_system`.`book`
	ORDER BY `bookID` ASC;
	
END
//
DELIMITER ;


-- -----------------------------------------------------
-- Read all books by title or author
-- -----------------------------------------------------
DROP PROCEDURE IF EXISTS `library_system`.`searchBooks`;

DELIMITER //
CREATE PROCEDURE `searchBooks`
	(IN searchValue VARCHAR(255)) 

BEGIN 
	
	SELECT *
	FROM `library_system`.`book`
	WHERE `title` LIKE CONCAT('%', searchValue, '%')
	OR `author` LIKE CONCAT('%', searchValue, '%');
	
END
//
DELIMITER ;


-- -----------------------------------------------------
-- Check if user is registered
-- -----------------------------------------------------
DROP PROCEDURE IF EXISTS `library_system`.`checkUserReg`;

DELIMITER //
CREATE PROCEDURE `checkUserReg`
	(IN username VARCHAR(255)) 

BEGIN 
	
	SELECT *
	FROM `library_system`.`member`
	WHERE `memberUsername` = username
	ORDER BY `memberName` ASC;
	
END
//
DELIMITER ;


-- -----------------------------------------------------
-- Member Login
-- -----------------------------------------------------
DROP PROCEDURE IF EXISTS `library_system`.`memberLogin`;

DELIMITER //
CREATE PROCEDURE `memberLogin`
	(IN username VARCHAR(255),
	 IN password VARCHAR(255)) 

BEGIN 
	
	SELECT *
	FROM `library_system`.`member`
	WHERE `memberUsername` = username
	AND `memberPassword` = password;
	
END
//
DELIMITER ;


-- -----------------------------------------------------
-- Read all members
-- -----------------------------------------------------
DROP PROCEDURE IF EXISTS `library_system`.`readAllMembers`;

DELIMITER //
CREATE PROCEDURE `readAllMembers`() 

BEGIN 
	
	SELECT *
	FROM `library_system`.`member`
	ORDER BY `memberName` ASC;
	
END
//
DELIMITER ;


-- -----------------------------------------------------
-- Read all book loans
-- -----------------------------------------------------
DROP PROCEDURE IF EXISTS `library_system`.`readAllBookLoans`;

DELIMITER //
CREATE PROCEDURE `readAllBookLoans`() 

BEGIN 
	
	SELECT *
	FROM `book_loans`, `member`, `book`
	WHERE `book`.`bookID` = `book_loans`.`bookID`
	AND `member`.`memberID` = `book_loans`.`memberID`
	ORDER BY `loanID` ASC;
	
END
//
DELIMITER ;


-- -----------------------------------------------------
-- UPDATES
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Update book details
-- -----------------------------------------------------
DROP PROCEDURE IF EXISTS `library_system`.`updateBook`;

DELIMITER //
CREATE PROCEDURE `updateBook` 
	(IN bkID INT(11),
	 IN newTitle VARCHAR(255),
	 IN newAuthor VARCHAR(255),
     IN newPublished YEAR(4),
	 IN newSynopsis VARCHAR(500)) 
	 
BEGIN 
	
	UPDATE `library_system`.`book`
	SET `title` = newTitle, `author` = newAuthor, `yearPublished` = newPublished, `synopsis` = newSynopsis
	WHERE (`bookID` = bkID); 
	
END
//
DELIMITER ;


-- -----------------------------------------------------
-- DELETES
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Delete book record
-- -----------------------------------------------------
DROP PROCEDURE IF EXISTS `library_system`.`delBook`;

DELIMITER //
CREATE PROCEDURE `delBook`
	(IN bkID INT(11))
	
BEGIN 
	
	DELETE
	FROM `library_system`.`book`
	WHERE (`bookID` = bkID);
	
END
//
DELIMITER ;


-- -----------------------------------------------------
-- Delete member record
-- -----------------------------------------------------
DROP PROCEDURE IF EXISTS `library_system`.`delMember`;

DELIMITER //
CREATE PROCEDURE `delMember`
	(IN mbrID INT(11))
	
BEGIN 
	
	DELETE
	FROM `library_system`.`member`
	WHERE (`memberID` = mbrID);
	
END
//
DELIMITER ;


-- -----------------------------------------------------
-- Delete loan record
-- -----------------------------------------------------
DROP PROCEDURE IF EXISTS `library_system`.`delLoan`;

DELIMITER //
CREATE PROCEDURE `delLoan`
	(IN loan_id INT(11))
	
BEGIN 
	
	DELETE
	FROM `library_system`.`book_loans`
	WHERE (`loanID` = loan_id);
	
END
//
DELIMITER ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
