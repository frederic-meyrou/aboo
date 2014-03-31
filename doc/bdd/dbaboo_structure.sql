-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Lun 31 Mars 2014 à 18:24
-- Version du serveur: 5.5.16
-- Version de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `dbaboo`
--

DELIMITER $$
--
-- Procédures
--
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
-- Structure de la table `charges`
--

CREATE TABLE IF NOT EXISTS `charges` (
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `mois` tinyint(4) DEFAULT NULL,
  `charges` decimal(10,2) DEFAULT NULL,
  `commentaire` varchar(1024) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `user_id` (`user_id`),
  KEY `exercice_id` (`exercice_id`),
  KEY `mois` (`mois`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

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

-- --------------------------------------------------------

--
-- Structure de la table `depense`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

-- --------------------------------------------------------

--
-- Structure de la table `exercice`
--

CREATE TABLE IF NOT EXISTS `exercice` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `mois_debut` smallint(6) DEFAULT NULL,
  `montant_treso_initial` decimal(10,2) DEFAULT NULL,
  `montant_provision_charges` decimal(10,2) DEFAULT NULL,
  `annee_debut` year(4) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure de la table `recette`
--

CREATE TABLE IF NOT EXISTS `recette` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `periodicitee` smallint(6) NOT NULL DEFAULT '1',
  `montant` decimal(10,2) DEFAULT NULL,
  `paye` smallint(6) NOT NULL DEFAULT '0',
  `declaration` tinyint(4) NOT NULL DEFAULT '1',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=207 ;

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
-- Structure de la table `salaire`
--

CREATE TABLE IF NOT EXISTS `salaire` (
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `mois` tinyint(4) DEFAULT NULL,
  `salaire` decimal(10,2) DEFAULT NULL,
  `commentaire` varchar(1024) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `mois` (`mois`),
  KEY `user_id` (`user_id`),
  KEY `exercice_id` (`exercice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(45) DEFAULT NULL,
  `nom` varchar(45) DEFAULT NULL,
  `email` varchar(96) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
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
  `raison_sociale` varchar(96) DEFAULT NULL,
  `siret` varchar(14) DEFAULT NULL,
  `site_internet` varchar(96) DEFAULT NULL,
  `regime_fiscal` tinyint(4) NOT NULL DEFAULT '0',
  `gestion_social` tinyint(1) NOT NULL DEFAULT '0',
  `adresse1` varchar(96) DEFAULT NULL,
  `adresse2` varchar(96) DEFAULT NULL,
  `cp` mediumint(9) DEFAULT NULL,
  `ville` varchar(96) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
