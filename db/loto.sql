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


INSERT INTO `numbers` VALUES (775,10,1,'2024-07-24 04:00:00'),(776,15,2,'2024-07-24 04:00:00'),(777,18,3,'2024-07-24 04:00:00'),(778,24,4,'2024-07-24 04:00:00'),(779,31,5,'2024-07-24 04:00:00'),(780,34,6,'2024-07-24 04:00:00'),(781,7,1,'2024-07-20 04:00:00'),(782,14,2,'2024-07-20 04:00:00'),(783,19,3,'2024-07-20 04:00:00'),(784,23,4,'2024-07-20 04:00:00'),(785,36,5,'2024-07-20 04:00:00'),(786,37,6,'2024-07-20 04:00:00'),(787,6,1,'2024-07-17 04:00:00'),(788,9,2,'2024-07-17 04:00:00'),(789,10,3,'2024-07-17 04:00:00'),(790,11,4,'2024-07-17 04:00:00'),(791,32,5,'2024-07-17 04:00:00'),(792,39,6,'2024-07-17 04:00:00'),(793,13,1,'2024-07-13 04:00:00'),(794,16,2,'2024-07-13 04:00:00'),(795,29,3,'2024-07-13 04:00:00'),(796,31,4,'2024-07-13 04:00:00'),(797,32,5,'2024-07-13 04:00:00'),(798,33,6,'2024-07-13 04:00:00'),(799,3,1,'2024-07-10 04:00:00'),(800,5,2,'2024-07-10 04:00:00'),(801,7,3,'2024-07-10 04:00:00'),(802,21,4,'2024-07-10 04:00:00'),(803,29,5,'2024-07-10 04:00:00'),(804,30,6,'2024-07-10 04:00:00'),(805,12,1,'2024-07-06 04:00:00'),(806,17,2,'2024-07-06 04:00:00'),(807,19,3,'2024-07-06 04:00:00'),(808,23,4,'2024-07-06 04:00:00'),(809,34,5,'2024-07-06 04:00:00'),(810,35,6,'2024-07-06 04:00:00'),(811,1,1,'2024-07-03 04:00:00'),(812,3,2,'2024-07-03 04:00:00'),(813,11,3,'2024-07-03 04:00:00'),(814,20,4,'2024-07-03 04:00:00'),(815,28,5,'2024-07-03 04:00:00'),(816,31,6,'2024-07-03 04:00:00'),(817,6,1,'2024-06-29 04:00:00'),(818,8,2,'2024-06-29 04:00:00'),(819,13,3,'2024-06-29 04:00:00'),(820,25,4,'2024-06-29 04:00:00'),(821,35,5,'2024-06-29 04:00:00'),(822,39,6,'2024-06-29 04:00:00'),(823,2,1,'2024-06-26 04:00:00'),(824,3,2,'2024-06-26 04:00:00'),(825,4,3,'2024-06-26 04:00:00'),(826,12,4,'2024-06-26 04:00:00'),(827,23,5,'2024-06-26 04:00:00'),(828,30,6,'2024-06-26 04:00:00'),(829,6,1,'2024-06-22 04:00:00'),(830,16,2,'2024-06-22 04:00:00'),(831,25,3,'2024-06-22 04:00:00'),(832,26,4,'2024-06-22 04:00:00'),(833,27,5,'2024-06-22 04:00:00'),(834,33,6,'2024-06-22 04:00:00'),(835,2,1,'2024-06-19 04:00:00'),(836,14,2,'2024-06-19 04:00:00'),(837,22,3,'2024-06-19 04:00:00'),(838,33,4,'2024-06-19 04:00:00'),(839,35,5,'2024-06-19 04:00:00'),(840,39,6,'2024-06-19 04:00:00'),(841,3,1,'2024-06-15 04:00:00'),(842,6,2,'2024-06-15 04:00:00'),(843,12,3,'2024-06-15 04:00:00'),(844,14,4,'2024-06-15 04:00:00'),(845,18,5,'2024-06-15 04:00:00'),(846,27,6,'2024-06-15 04:00:00'),(847,1,1,'2024-06-12 04:00:00'),(848,6,2,'2024-06-12 04:00:00'),(849,11,3,'2024-06-12 04:00:00'),(850,22,4,'2024-06-12 04:00:00'),(851,32,5,'2024-06-12 04:00:00'),(852,39,6,'2024-06-12 04:00:00'),(853,5,1,'2024-06-08 04:00:00'),(854,6,2,'2024-06-08 04:00:00'),(855,7,3,'2024-06-08 04:00:00'),(856,8,4,'2024-06-08 04:00:00'),(857,30,5,'2024-06-08 04:00:00'),(858,33,6,'2024-06-08 04:00:00'),(859,8,1,'2024-06-05 04:00:00'),(860,19,2,'2024-06-05 04:00:00'),(861,20,3,'2024-06-05 04:00:00'),(862,27,4,'2024-06-05 04:00:00'),(863,29,5,'2024-06-05 04:00:00'),(864,36,6,'2024-06-05 04:00:00'),(865,5,1,'2024-06-01 04:00:00'),(866,7,2,'2024-06-01 04:00:00'),(867,17,3,'2024-06-01 04:00:00'),(868,27,4,'2024-06-01 04:00:00'),(869,29,5,'2024-06-01 04:00:00'),(870,40,6,'2024-06-01 04:00:00'),(871,3,1,'2024-05-29 04:00:00'),(872,18,2,'2024-05-29 04:00:00'),(873,21,3,'2024-05-29 04:00:00'),(874,22,4,'2024-05-29 04:00:00'),(875,28,5,'2024-05-29 04:00:00'),(876,40,6,'2024-05-29 04:00:00'),(877,5,1,'2024-05-25 04:00:00'),(878,10,2,'2024-05-25 04:00:00'),(879,12,3,'2024-05-25 04:00:00'),(880,15,4,'2024-05-25 04:00:00'),(881,29,5,'2024-05-25 04:00:00'),(882,38,6,'2024-05-25 04:00:00'),(883,1,1,'2024-05-22 04:00:00'),(884,4,2,'2024-05-22 04:00:00'),(885,9,3,'2024-05-22 04:00:00'),(886,21,4,'2024-05-22 04:00:00'),(887,25,5,'2024-05-22 04:00:00'),(888,26,6,'2024-05-22 04:00:00'),(889,5,1,'2024-05-18 04:00:00'),(890,9,2,'2024-05-18 04:00:00'),(891,13,3,'2024-05-18 04:00:00'),(892,15,4,'2024-05-18 04:00:00'),(893,18,5,'2024-05-18 04:00:00'),(894,22,6,'2024-05-18 04:00:00'),(895,1,1,'2024-05-15 04:00:00'),(896,2,2,'2024-05-15 04:00:00'),(897,18,3,'2024-05-15 04:00:00'),(898,20,4,'2024-05-15 04:00:00'),(899,21,5,'2024-05-15 04:00:00'),(900,28,6,'2024-05-15 04:00:00'),(901,2,1,'2024-05-11 04:00:00'),(902,3,2,'2024-05-11 04:00:00'),(903,27,3,'2024-05-11 04:00:00'),(904,29,4,'2024-05-11 04:00:00'),(905,33,5,'2024-05-11 04:00:00'),(906,37,6,'2024-05-11 04:00:00'),(907,11,1,'2024-05-08 04:00:00'),(908,12,2,'2024-05-08 04:00:00'),(909,28,3,'2024-05-08 04:00:00'),(910,29,4,'2024-05-08 04:00:00'),(911,35,5,'2024-05-08 04:00:00'),(912,40,6,'2024-05-08 04:00:00'),(913,5,1,'2024-05-04 04:00:00'),(914,12,2,'2024-05-04 04:00:00'),(915,22,3,'2024-05-04 04:00:00'),(916,23,4,'2024-05-04 04:00:00'),(917,27,5,'2024-05-04 04:00:00'),(918,31,6,'2024-05-04 04:00:00'),(919,12,1,'2024-05-01 04:00:00'),(920,18,2,'2024-05-01 04:00:00'),(921,19,3,'2024-05-01 04:00:00'),(922,20,4,'2024-05-01 04:00:00'),(923,37,5,'2024-05-01 04:00:00'),(924,40,6,'2024-05-01 04:00:00'),(925,2,1,'2024-04-27 04:00:00'),(926,8,2,'2024-04-27 04:00:00'),(927,11,3,'2024-04-27 04:00:00'),(928,13,4,'2024-04-27 04:00:00'),(929,20,5,'2024-04-27 04:00:00'),(930,24,6,'2024-04-27 04:00:00'),(931,6,1,'2024-04-24 04:00:00'),(932,8,2,'2024-04-24 04:00:00'),(933,9,3,'2024-04-24 04:00:00'),(934,17,4,'2024-04-24 04:00:00'),(935,30,5,'2024-04-24 04:00:00'),(936,38,6,'2024-04-24 04:00:00'),(937,2,1,'2024-04-20 04:00:00'),(938,6,2,'2024-04-20 04:00:00'),(939,7,3,'2024-04-20 04:00:00'),(940,15,4,'2024-04-20 04:00:00'),(941,21,5,'2024-04-20 04:00:00'),(942,29,6,'2024-04-20 04:00:00'),(943,1,1,'2024-04-17 04:00:00'),(944,15,2,'2024-04-17 04:00:00'),(945,17,3,'2024-04-17 04:00:00'),(946,23,4,'2024-04-17 04:00:00'),(947,34,5,'2024-04-17 04:00:00'),(948,39,6,'2024-04-17 04:00:00'),(949,3,1,'2024-04-13 04:00:00'),(950,7,2,'2024-04-13 04:00:00'),(951,9,3,'2024-04-13 04:00:00'),(952,12,4,'2024-04-13 04:00:00'),(953,15,5,'2024-04-13 04:00:00'),(954,28,6,'2024-04-13 04:00:00'),(961,2,1,'2024-04-10 04:00:00'),(962,8,2,'2024-04-10 04:00:00'),(963,11,3,'2024-04-10 04:00:00'),(964,19,4,'2024-04-10 04:00:00'),(965,23,5,'2024-04-10 04:00:00'),(966,29,6,'2024-04-10 04:00:00'),(967,3,1,'2024-04-06 04:00:00'),(968,9,2,'2024-04-06 04:00:00'),(969,14,3,'2024-04-06 04:00:00'),(970,23,4,'2024-04-06 04:00:00'),(971,25,5,'2024-04-06 04:00:00'),(972,27,6,'2024-04-06 04:00:00'),(973,1,1,'2024-04-03 04:00:00'),(974,6,2,'2024-04-03 04:00:00'),(975,7,3,'2024-04-03 04:00:00'),(976,15,4,'2024-04-03 04:00:00'),(977,25,5,'2024-04-03 04:00:00'),(978,28,6,'2024-04-03 04:00:00'),(979,11,1,'2024-03-30 04:00:00'),(980,19,2,'2024-03-30 04:00:00'),(981,31,3,'2024-03-30 04:00:00'),(982,37,4,'2024-03-30 04:00:00'),(983,38,5,'2024-03-30 04:00:00'),(984,40,6,'2024-03-30 04:00:00'),(985,6,1,'2024-03-27 04:00:00'),(986,17,2,'2024-03-27 04:00:00'),(987,24,3,'2024-03-27 04:00:00'),(988,32,4,'2024-03-27 04:00:00'),(989,34,5,'2024-03-27 04:00:00'),(990,40,6,'2024-03-27 04:00:00'),(991,7,1,'2024-03-23 04:00:00'),(992,10,2,'2024-03-23 04:00:00'),(993,13,3,'2024-03-23 04:00:00'),(994,18,4,'2024-03-23 04:00:00'),(995,20,5,'2024-03-23 04:00:00'),(996,39,6,'2024-03-23 04:00:00'),(997,4,1,'2024-03-20 04:00:00'),(998,15,2,'2024-03-20 04:00:00'),(999,17,3,'2024-03-20 04:00:00'),(1000,19,4,'2024-03-20 04:00:00'),(1001,25,5,'2024-03-20 04:00:00'),(1002,33,6,'2024-03-20 04:00:00'),(1003,10,1,'2024-03-16 04:00:00'),(1004,11,2,'2024-03-16 04:00:00'),(1005,12,3,'2024-03-16 04:00:00'),(1006,15,4,'2024-03-16 04:00:00'),(1007,32,5,'2024-03-16 04:00:00'),(1008,37,6,'2024-03-16 04:00:00'),(1009,8,1,'2024-03-13 04:00:00'),(1010,14,2,'2024-03-13 04:00:00'),(1011,22,3,'2024-03-13 04:00:00'),(1012,26,4,'2024-03-13 04:00:00'),(1013,35,5,'2024-03-13 04:00:00'),(1014,40,6,'2024-03-13 04:00:00'),(1015,5,1,'2024-03-09 04:00:00'),(1016,7,2,'2024-03-09 04:00:00'),(1017,18,3,'2024-03-09 04:00:00'),(1018,33,4,'2024-03-09 04:00:00'),(1019,38,5,'2024-03-09 04:00:00'),(1020,40,6,'2024-03-09 04:00:00'),(1021,22,1,'2024-03-06 04:00:00'),(1022,24,2,'2024-03-06 04:00:00'),(1023,29,3,'2024-03-06 04:00:00'),(1024,33,4,'2024-03-06 04:00:00'),(1025,36,5,'2024-03-06 04:00:00'),(1026,38,6,'2024-03-06 04:00:00'),(1027,6,1,'2024-03-02 04:00:00'),(1028,9,2,'2024-03-02 04:00:00'),(1029,14,3,'2024-03-02 04:00:00'),(1030,17,4,'2024-03-02 04:00:00'),(1031,36,5,'2024-03-02 04:00:00'),(1032,37,6,'2024-03-02 04:00:00'),(1033,7,1,'2024-02-28 04:00:00'),(1034,8,2,'2024-02-28 04:00:00'),(1035,15,3,'2024-02-28 04:00:00'),(1036,17,4,'2024-02-28 04:00:00'),(1037,25,5,'2024-02-28 04:00:00'),(1038,30,6,'2024-02-28 04:00:00'),(1039,2,1,'2024-02-24 04:00:00'),(1040,10,2,'2024-02-24 04:00:00'),(1041,15,3,'2024-02-24 04:00:00'),(1042,18,4,'2024-02-24 04:00:00'),(1043,31,5,'2024-02-24 04:00:00'),(1044,39,6,'2024-02-24 04:00:00'),(1045,7,1,'2024-02-21 04:00:00'),(1046,13,2,'2024-02-21 04:00:00'),(1047,20,3,'2024-02-21 04:00:00'),(1048,28,4,'2024-02-21 04:00:00'),(1049,30,5,'2024-02-21 04:00:00'),(1050,33,6,'2024-02-21 04:00:00'),(1051,3,1,'2024-02-17 04:00:00'),(1052,14,2,'2024-02-17 04:00:00'),(1053,15,3,'2024-02-17 04:00:00'),(1054,17,4,'2024-02-17 04:00:00'),(1055,18,5,'2024-02-17 04:00:00'),(1056,37,6,'2024-02-17 04:00:00'),(1057,9,1,'2024-02-14 04:00:00'),(1058,11,2,'2024-02-14 04:00:00'),(1059,15,3,'2024-02-14 04:00:00'),(1060,22,4,'2024-02-14 04:00:00'),(1061,27,5,'2024-02-14 04:00:00'),(1062,39,6,'2024-02-14 04:00:00'),(1063,5,1,'2024-02-10 04:00:00'),(1064,12,2,'2024-02-10 04:00:00'),(1065,14,3,'2024-02-10 04:00:00'),(1066,17,4,'2024-02-10 04:00:00'),(1067,30,5,'2024-02-10 04:00:00'),(1068,40,6,'2024-02-10 04:00:00'),(1069,7,1,'2024-02-07 04:00:00'),(1070,11,2,'2024-02-07 04:00:00'),(1071,24,3,'2024-02-07 04:00:00'),(1072,25,4,'2024-02-07 04:00:00'),(1073,28,5,'2024-02-07 04:00:00'),(1074,38,6,'2024-02-07 04:00:00'),(1075,1,1,'2024-02-03 04:00:00'),(1076,7,2,'2024-02-03 04:00:00'),(1077,9,3,'2024-02-03 04:00:00'),(1078,23,4,'2024-02-03 04:00:00'),(1079,28,5,'2024-02-03 04:00:00'),(1080,37,6,'2024-02-03 04:00:00');
