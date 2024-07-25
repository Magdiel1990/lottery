-- MySQL dump 10.13  Distrib 8.0.32, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: loto
-- ------------------------------------------------------
-- Server version	8.0.28

DROP DATABASE IF EXISTS `loto`;

CREATE DATABASE `loto`;

USE `loto`;

--
-- Table structure for table `numbers`
--

CREATE TABLE `numbers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `number` int NOT NULL,
  `position` int NOT NULL,
  `date` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  CHECK (`number` >= 1 AND `number` <= 40),
  CHECK (`position` >= 1 AND `position` <= 6)
);