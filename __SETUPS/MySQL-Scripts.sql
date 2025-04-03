-- MySQL-Scripts.sql
-- updated: pb@2025_02_04
-- ----------------------------------------
-- [MySQL-8.4.X]
-- [STEP 01] Create Database User
-- ----------------------------------------
CREATE USER 'milaravel10app_usr'@'localhost' IDENTIFIED WITH mysql_native_password BY 'aSaQc#R+5VnkG';
GRANT USAGE ON *.* TO 'milaravel10app_usr'@'localhost';

CREATE USER 'milaravel10app_usr'@'%' IDENTIFIED WITH mysql_native_password BY 'aSaQc#R+5VnkG';
GRANT USAGE ON *.* TO 'milaravel10app_usr'@'%';
-- ----------------------------------------
-- [STEP 02] Create Database
CREATE DATABASE `milaravel10app_db_v1_0` CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_general_ci';
-- [STEP 03]
-- Grant Privileges
GRANT ALL PRIVILEGES ON `milaravel10app_db_v1_0`.* TO `milaravel10app_usr`@`localhost`;

GRANT ALL PRIVILEGES ON `milaravel10app_db_v1_0`.* TO `milaravel10app_usr`@`%`;
-- [STEP 04] flush privileges
FLUSH PRIVILEGES;
-- END
-- ----------------------------------------
