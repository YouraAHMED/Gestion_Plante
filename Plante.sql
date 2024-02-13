-- phpMyAdmin SQL Dump
-- version 5.0.4deb2
-- https://www.phpmyadmin.net/
--
-- Hôte : mysql.info.unicaen.fr:3306
-- Généré le : lun. 13 mars 2023 à 14:18
-- Version du serveur :  10.5.11-MariaDB-1
-- Version de PHP : 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

COMMIT;




--DROP TABLE IF EXISTS `personne`;

CREATE TABLE IF NOT EXISTS `plante` (
  idP int NOT NULL  AUTO_INCREMENT,
  nom varchar(100) NOT NULL,
  categorie varchar(100) NOT NULL,
  prix double NOT NULL,
  dateP DATE NOT NULL,
  PRIMARY KEY (idP)
);

--
-- Contenu de la table `plante`
--

INSERT INTO `plante` (idP, nom, categorie, prix, dateP) VALUES
(1, 'Ficus', 'Arbre', 10, '2021-03-13'
(2, 'Rose', 'Fleurs', 20, '1977-02-24'),
(3, 'Mangue', 'Fruit', 30, '2001-08-10'),
(4, 'Aloe', 'Arbre', 40, '1999-06-17'),
(5, 'Carotte', 'Legumes', 50, '2005-10-13');
























