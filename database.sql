-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `flexireserve`;
USE `flexireserve`;

-- Table: User
CREATE TABLE `User` (
    `id_user` INT AUTO_INCREMENT PRIMARY KEY,
    `userName` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
);

-- Table: area
CREATE TABLE `area` (
    `id_area` INT AUTO_INCREMENT PRIMARY KEY,
    `area_name` VARCHAR(255) NOT NULL UNIQUE,
    `area_description` TEXT,
    `creation_user` VARCHAR(255),
    `modification_user` VARCHAR(255),
    `status` VARCHAR(255) NOT NULL,
    `creation_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: schedule
CREATE TABLE `schedule` (
    `id_schedule` INT AUTO_INCREMENT PRIMARY KEY,
    `hour_ini` TIME NOT NULL,
    `hour_fin` TIME NOT NULL,
    `creation_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `creation_user` VARCHAR(255),
    `status` VARCHAR(255) NOT NULL,
    `modification_user` VARCHAR(255)
);

-- Table: event
CREATE TABLE `reservation` (
    `id_reservation` INT AUTO_INCREMENT PRIMARY KEY,
    `event_name` VARCHAR(255) NOT NULL,
    `event_description` TEXT,
    `area_id` INT NOT NULL,
    `schedule_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `date` DATE NOT NULL,
    `status` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`area_id`) REFERENCES `area`(`id_area`),
    FOREIGN KEY (`schedule_id`) REFERENCES `schedule`(`id_schedule`),
    FOREIGN KEY (`user_id`) REFERENCES `User`(`id_user`)
);