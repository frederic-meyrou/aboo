-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Client: 127.0.0.1
-- Généré le: Jeu 12 Décembre 2013 à 13:16
-- Version du serveur: 5.6.11
-- Version de PHP: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `test`;

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `test_multi_sets`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `test_multi_sets`()
    DETERMINISTIC
begin
        select user() as first_col;
        select user() as first_col, now() as second_col;
        select user() as first_col, now() as second_col, now() as third_col;
        end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `abonnement`
--

DROP TABLE IF EXISTS `abonnement`;
CREATE TABLE IF NOT EXISTS `abonnement` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `periodicitee` smallint(6) NOT NULL DEFAULT '1',
  `montant` decimal(10,2) DEFAULT NULL,
  `mois` int(11) DEFAULT NULL COMMENT '1..12',
  `commentaire` varchar(1024) DEFAULT NULL,
  `mois_1` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_3` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_4` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_5` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_6` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_7` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_8` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_9` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_10` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_11` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mois_12` decimal(10,2) NOT NULL DEFAULT '0.00',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_abonnement_user1_idx` (`user_id`),
  KEY `fk_abonnement_exercice1_idx` (`exercice_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Vider la table avant d'insérer `abonnement`
--

TRUNCATE TABLE `abonnement`;
--
-- Contenu de la table `abonnement`
--

INSERT INTO `abonnement` (`id`, `user_id`, `exercice_id`, `client_id`, `type`, `periodicitee`, `montant`, `mois`, `commentaire`, `mois_1`, `mois_2`, `mois_3`, `mois_4`, `mois_5`, `mois_6`, `mois_7`, `mois_8`, `mois_9`, `mois_10`, `mois_11`, `mois_12`, `date_creation`) VALUES
(0000000028, 6, 15, 0, '1', 12, '100.00', 1, 'test annuel', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-12 10:04:23'),
(0000000029, 6, 15, 0, '1', 1, '123.00', 1, 'test mensuel', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-12 10:05:35'),
(0000000032, 6, 15, 0, '1', 2, '111.00', 1, 'bimensuel', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-12 10:06:20'),
(0000000034, 6, 15, 0, '1', 6, '222.00', 1, 'Test semestre', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-12 10:24:08'),
(0000000035, 6, 15, 0, '1', 3, '30.00', 1, 'test trimestre', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-12 10:37:06'),
(0000000036, 6, 15, 0, '4', 1, '1.00', 1, 'test', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-12 10:39:52');

-- --------------------------------------------------------

--
-- Structure de la table `ca`
--

DROP TABLE IF EXISTS `ca`;
CREATE TABLE IF NOT EXISTS `ca` (
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `mois` smallint(6) DEFAULT NULL COMMENT '1..12',
  `total_abonnements` decimal(10,2) DEFAULT NULL,
  `total_charges` decimal(10,2) DEFAULT NULL,
  `salaire` decimal(10,2) DEFAULT NULL,
  `treso` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`exercice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Chiffre d''affaire mensuel';

--
-- Vider la table avant d'insérer `ca`
--

TRUNCATE TABLE `ca`;
-- --------------------------------------------------------

--
-- Structure de la table `ca_mensuel`
--

DROP TABLE IF EXISTS `ca_mensuel`;
CREATE TABLE IF NOT EXISTS `ca_mensuel` (
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `mois` smallint(6) DEFAULT NULL COMMENT '1..12',
  `total_abonnements` decimal(10,2) DEFAULT NULL,
  `total_charges` decimal(10,2) DEFAULT NULL,
  `salaire` decimal(10,2) DEFAULT NULL,
  `treso` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`exercice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Chiffre d''affaire mensuel';

--
-- Vider la table avant d'insérer `ca_mensuel`
--

TRUNCATE TABLE `ca_mensuel`;
-- --------------------------------------------------------

--
-- Structure de la table `depense`
--

DROP TABLE IF EXISTS `depense`;
CREATE TABLE IF NOT EXISTS `depense` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `periodicitee` smallint(6) DEFAULT NULL,
  `mois` smallint(6) DEFAULT NULL COMMENT '1..12',
  `commentaire` varchar(1024) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Vider la table avant d'insérer `depense`
--

TRUNCATE TABLE `depense`;
--
-- Contenu de la table `depense`
--

INSERT INTO `depense` (`id`, `user_id`, `exercice_id`, `type`, `montant`, `periodicitee`, `mois`, `commentaire`, `date_creation`) VALUES
(00000000013, 6, 15, '1', '101.00', NULL, 1, 'test frais', '2013-12-12 11:38:51'),
(00000000014, 6, 15, '3', '102.00', NULL, 1, 'Social', '2013-12-12 11:39:05');

-- --------------------------------------------------------

--
-- Structure de la table `encaissement`
--

DROP TABLE IF EXISTS `encaissement`;
CREATE TABLE IF NOT EXISTS `encaissement` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `abonnement_id` int(10) DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `mois` smallint(6) DEFAULT NULL COMMENT '(1..12)',
  `type` varchar(45) DEFAULT NULL,
  `compte` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Vider la table avant d'insérer `encaissement`
--

TRUNCATE TABLE `encaissement`;
-- --------------------------------------------------------

--
-- Structure de la table `exercice`
--

DROP TABLE IF EXISTS `exercice`;
CREATE TABLE IF NOT EXISTS `exercice` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `mois_debut` smallint(6) DEFAULT NULL,
  `montant_treso_initial` decimal(10,2) DEFAULT NULL,
  `annee_debut` year(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Vider la table avant d'insérer `exercice`
--

TRUNCATE TABLE `exercice`;
--
-- Contenu de la table `exercice`
--

INSERT INTO `exercice` (`id`, `user_id`, `mois_debut`, `montant_treso_initial`, `annee_debut`) VALUES
(00000000014, 6, 1, '100.00', 2010),
(00000000015, 6, 12, '10000.00', 2013);

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

DROP TABLE IF EXISTS `paiement`;
CREATE TABLE IF NOT EXISTS `paiement` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `abonnement_id` int(11) unsigned zerofill DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `mois` smallint(6) DEFAULT NULL COMMENT '(1..12)',
  `type` varchar(45) DEFAULT NULL,
  `encaissement` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Vider la table avant d'insérer `paiement`
--

TRUNCATE TABLE `paiement`;
-- --------------------------------------------------------

--
-- Structure de la table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `user_id` int(11) NOT NULL,
  `annee` year(4) NOT NULL,
  `mois` smallint(6) NOT NULL,
  `treso` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vider la table avant d'insérer `session`
--

TRUNCATE TABLE `session`;
-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(45) DEFAULT NULL,
  `nom` varchar(45) DEFAULT NULL,
  `email` varchar(96) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `password` varchar(12) NOT NULL,
  `inscription` date DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  `administrateur` tinyint(1) NOT NULL,
  `token` varchar(45) DEFAULT NULL,
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `email_2` (`email`),
  KEY `email` (`email`),
  KEY `email_3` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Vider la table avant d'insérer `user`
--

TRUNCATE TABLE `user`;
--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `prenom`, `nom`, `email`, `telephone`, `password`, `inscription`, `montant`, `expiration`, `administrateur`, `token`) VALUES
(4, 'fred', 'meyrou', 'frederic@meyrou.com', '0612345678', 'derf44', '2013-11-28', '1234.00', '0000-00-00', 1, NULL),
(6, 'elise', 'meyrou', 'elise@meyrou.com', '0612456789', 'grenouille', '2013-01-12', '100.00', '0000-00-00', 0, NULL),
(8, 'Fr&eacute;d&eacute;ric', 'MEYROU', 'frederic_meyrou@yahoo.fr', '0672268111', 'derf44', '2013-12-14', '999.00', '2013-12-12', 0, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
