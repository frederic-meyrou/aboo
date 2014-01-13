-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Client: 127.0.0.1
-- Généré le: Lun 13 Janvier 2014 à 22:21
-- Version du serveur: 5.6.11
-- Version de PHP: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `dbaboo`
--
CREATE DATABASE IF NOT EXISTS `dbaboo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `dbaboo`;

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
-- Structure de la table `bilan`
--

DROP TABLE IF EXISTS `bilan`;
CREATE TABLE IF NOT EXISTS `bilan` (
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `mois` tinyint(4) DEFAULT NULL COMMENT '1..12',
  `abonnements` decimal(10,2) NOT NULL DEFAULT '0.00',
  `depenses` decimal(10,2) NOT NULL DEFAULT '0.00',
  `salaire` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tresorerie` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ca` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ventilation` decimal(10,2) NOT NULL DEFAULT '0.00',
  `paiements` decimal(10,2) NOT NULL DEFAULT '0.00',
  `encaissements` decimal(10,2) NOT NULL DEFAULT '0.00',
  `a_tresoriser` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`user_id`,`exercice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Chiffre d''affaire mensuel';

--
-- Vider la table avant d'insérer `bilan`
--

TRUNCATE TABLE `bilan`;
-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `prenom` varchar(45) NOT NULL,
  `nom` varchar(45) NOT NULL,
  `email` varchar(96) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `age` tinyint(4) NOT NULL,
  `profession` varchar(96) NOT NULL,
  `adresse1` varchar(96) NOT NULL,
  `adresse2` varchar(96) NOT NULL,
  `cp` mediumint(9) NOT NULL,
  `ville` varchar(96) NOT NULL,
  `description` text NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Vider la table avant d'insérer `client`
--

TRUNCATE TABLE `client`;
--
-- Contenu de la table `client`
--

INSERT INTO `client` (`id`, `user_id`, `prenom`, `nom`, `email`, `telephone`, `mobile`, `age`, `profession`, `adresse1`, `adresse2`, `cp`, `ville`, `description`, `date_creation`) VALUES
(3, 6, 'test prenom', 'test nom', 'test@gmail.com', '0512345678', '0612345678', 44, 'Profession', 'Adr1', 'Adr2', 33123, 'Ville', 'Description', '2014-01-10 16:16:35'),
(4, 6, 'Fr&eacute;d&eacute;ric', 'Meyrou', 'frederic_meyrou@yahoo.fr', '', '0672268111', 44, 'Freelance', '9 rue de Coulon', '', 33130, 'BEGLES', 'Zob &agrave; droite', '2014-01-10 16:16:35');

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
  `mois` tinyint(4) DEFAULT NULL COMMENT '1..12',
  `commentaire` varchar(1024) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `mois` (`mois`),
  KEY `user_id` (`user_id`),
  KEY `exercice_id` (`exercice_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Vider la table avant d'insérer `depense`
--

TRUNCATE TABLE `depense`;
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
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Vider la table avant d'insérer `exercice`
--

TRUNCATE TABLE `exercice`;
-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

DROP TABLE IF EXISTS `paiement`;
CREATE TABLE IF NOT EXISTS `paiement` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `recette_id` int(11) unsigned zerofill DEFAULT NULL,
  `mois_1` decimal(10,2) DEFAULT '0.00',
  `mois_2` decimal(10,2) DEFAULT '0.00',
  `mois_3` decimal(10,2) DEFAULT '0.00',
  `mois_4` decimal(10,2) DEFAULT '0.00',
  `mois_5` decimal(10,2) DEFAULT '0.00',
  `mois_6` decimal(10,2) DEFAULT '0.00',
  `mois_7` decimal(10,2) DEFAULT '0.00',
  `mois_8` decimal(10,2) DEFAULT '0.00',
  `mois_9` decimal(10,2) DEFAULT '0.00',
  `mois_10` decimal(10,2) DEFAULT '0.00',
  `mois_11` decimal(10,2) DEFAULT '0.00',
  `mois_12` decimal(10,2) DEFAULT '0.00',
  `paye_1` tinyint(4) NOT NULL DEFAULT '0',
  `paye_2` tinyint(4) NOT NULL DEFAULT '0',
  `paye_3` tinyint(4) NOT NULL DEFAULT '0',
  `paye_4` tinyint(4) NOT NULL DEFAULT '0',
  `paye_5` tinyint(4) NOT NULL DEFAULT '0',
  `paye_6` tinyint(4) NOT NULL DEFAULT '0',
  `paye_7` tinyint(4) NOT NULL DEFAULT '0',
  `paye_8` tinyint(4) NOT NULL DEFAULT '0',
  `paye_9` tinyint(4) NOT NULL DEFAULT '0',
  `paye_10` tinyint(4) DEFAULT '0',
  `paye_11` tinyint(4) NOT NULL DEFAULT '0',
  `paye_12` tinyint(4) NOT NULL DEFAULT '0',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `recette_id` (`recette_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Vider la table avant d'insérer `paiement`
--

TRUNCATE TABLE `paiement`;
-- --------------------------------------------------------

--
-- Structure de la table `recette`
--

DROP TABLE IF EXISTS `recette`;
CREATE TABLE IF NOT EXISTS `recette` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `periodicitee` smallint(6) NOT NULL DEFAULT '1',
  `montant` decimal(10,2) DEFAULT NULL,
  `paye` smallint(6) NOT NULL DEFAULT '0',
  `mois` tinyint(4) DEFAULT NULL COMMENT '1..12',
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
  KEY `user_id` (`user_id`),
  KEY `exercice_id` (`exercice_id`),
  KEY `client_id` (`client_id`),
  KEY `type` (`type`),
  KEY `mois` (`mois`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=115 ;

--
-- Vider la table avant d'insérer `recette`
--

TRUNCATE TABLE `recette`;
--
-- Déclencheurs `recette`
--
DROP TRIGGER IF EXISTS `recette_after_delete`;
DELIMITER //
CREATE TRIGGER `recette_after_delete` AFTER DELETE ON `recette`
 FOR EACH ROW BEGIN
	
	DELETE FROM paiement WHERE recette_id = OLD.id;
		
    END
//
DELIMITER ;

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
  `password` varchar(20) NOT NULL,
  `inscription` date DEFAULT NULL,
  `actif` tinyint(4) NOT NULL DEFAULT '0',
  `essai` tinyint(4) NOT NULL DEFAULT '0',
  `montant` decimal(10,2) DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  `administrateur` tinyint(1) NOT NULL DEFAULT '0',
  `token` varchar(45) DEFAULT NULL,
  `mois_encours` tinyint(4) DEFAULT NULL COMMENT '1..12',
  `exerciceid_encours` int(11) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Vider la table avant d'insérer `user`
--

TRUNCATE TABLE `user`;
--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `prenom`, `nom`, `email`, `telephone`, `password`, `inscription`, `actif`, `essai`, `montant`, `expiration`, `administrateur`, `token`, `mois_encours`, `exerciceid_encours`, `date_creation`) VALUES
(4, 'fred', 'meyrou', 'frederic@meyrou.com', '0612345678', 'derf44', '2013-11-28', 1, 0, '1234.00', '0000-00-00', 1, NULL, 1, NULL, '2014-01-10 16:19:17'),
(6, 'elise', 'meyrou', 'elise@meyrou.com', '0612456789', 'grenouille', '2013-01-12', 1, 0, '100.00', NULL, 0, NULL, 1, NULL, '2014-01-10 16:19:17'),
(10, 'Fr&eacute;d&eacute;ric', 'MEYROU', 'frederic.meyrou@gmail.com', '0672268111', 'h6S2Tlv7', '2013-12-12', 0, 0, NULL, NULL, 0, '432b5f36651f5bab7e96984650487bb51417dea8', NULL, NULL, '2014-01-10 16:19:17'),
(11, 'Fr&eacute;d&eacute;ric', 'MEYROU', 'frederic_meyrou@yahoo.fr', '0672268111', 'derf44', '2014-01-01', 0, 0, '0.00', NULL, 0, NULL, NULL, NULL, '2014-01-13 18:23:49');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
