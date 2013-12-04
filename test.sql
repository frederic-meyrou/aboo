-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Client: 127.0.0.1
-- Généré le: Mer 04 Décembre 2013 à 12:51
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
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `ventilation_id` int(11) DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `mois` smallint(6) DEFAULT NULL COMMENT '1..12',
  `commentaire` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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

-- --------------------------------------------------------

--
-- Structure de la table `depense`
--

DROP TABLE IF EXISTS `depense`;
CREATE TABLE IF NOT EXISTS `depense` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `ventilation_id` int(11) DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `mois` smallint(6) DEFAULT NULL COMMENT '1..12',
  `commentaire` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `prenom`, `nom`, `email`, `telephone`, `password`, `inscription`, `montant`, `expiration`, `administrateur`, `token`) VALUES
(3, 'frederic', 'meyrou', 'frederic_meyrou@yahoo.fr', '0612345678', 'derf44', '2011-02-03', '1.00', '0000-00-00', 0, NULL),
(4, 'fred', 'meyrou', 'frederic@meyrou.com', '0612345678', 'derf44', '2013-11-28', '1234.00', '0000-00-00', 1, NULL),
(6, 'elise', 'meyrou', 'elise@meyrou.com', '0612456789', 'grenouille', '2013-01-12', '100.00', '0000-00-00', 0, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
