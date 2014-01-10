-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Client: 127.0.0.1
-- Généré le: Ven 10 Janvier 2014 à 17:47
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Vider la table avant d'insérer `depense`
--

TRUNCATE TABLE `depense`;
--
-- Contenu de la table `depense`
--

INSERT INTO `depense` (`id`, `user_id`, `exercice_id`, `type`, `montant`, `periodicitee`, `mois`, `commentaire`, `date_creation`) VALUES
(00000000001, 6, 15, '1', '1500.00', NULL, 10, 'test', '2014-01-06 11:30:31');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Vider la table avant d'insérer `exercice`
--

TRUNCATE TABLE `exercice`;
--
-- Contenu de la table `exercice`
--

INSERT INTO `exercice` (`id`, `user_id`, `mois_debut`, `montant_treso_initial`, `annee_debut`, `date_creation`) VALUES
(00000000014, 6, 1, '111.00', 2010, '2014-01-10 16:18:08'),
(00000000015, 6, 12, '10000.00', 2013, '2014-01-10 16:18:08'),
(00000000016, 6, 9, '0.00', 2014, '2014-01-10 16:18:08');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Vider la table avant d'insérer `paiement`
--

TRUNCATE TABLE `paiement`;
--
-- Contenu de la table `paiement`
--

INSERT INTO `paiement` (`id`, `recette_id`, `mois_1`, `mois_2`, `mois_3`, `mois_4`, `mois_5`, `mois_6`, `mois_7`, `mois_8`, `mois_9`, `mois_10`, `mois_11`, `mois_12`, `paye_1`, `paye_2`, `paye_3`, `paye_4`, `paye_5`, `paye_6`, `paye_7`, `paye_8`, `paye_9`, `paye_10`, `paye_11`, `paye_12`, `date_creation`) VALUES
(00000000008, 00000000091, '0.00', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2014-01-10 16:17:30'),
(00000000009, 00000000092, '500.00', '500.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2014-01-10 16:17:30'),
(00000000010, 00000000096, '50.00', '50.00', '50.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2014-01-10 16:17:30'),
(00000000011, 00000000098, '200.00', '0.00', '200.00', '0.00', '200.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, '2014-01-10 16:17:30'),
(00000000012, 00000000099, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '123.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, '2014-01-10 16:17:30'),
(00000000013, 00000000100, '1000.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2014-01-10 16:17:30'),
(00000000014, 00000000101, '50.00', '0.00', '25.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '25.00', 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, '2014-01-10 16:17:30'),
(00000000015, 00000000102, '0.00', '200.00', '100.00', '0.00', '200.00', '0.00', '200.00', '0.00', '200.00', '0.00', '100.00', '200.00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2014-01-10 16:17:30'),
(00000000016, 00000000103, '0.00', '100.00', '10.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2014-01-10 16:17:30'),
(00000000017, 00000000104, '0.00', '0.00', '100.00', '0.00', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2014-01-10 16:17:30'),
(00000000018, 00000000105, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '1000.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2014-01-10 16:17:30'),
(00000000019, 00000000106, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', '100.00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2014-01-10 16:17:30');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=107 ;

--
-- Vider la table avant d'insérer `recette`
--

TRUNCATE TABLE `recette`;
--
-- Contenu de la table `recette`
--

INSERT INTO `recette` (`id`, `user_id`, `exercice_id`, `client_id`, `type`, `periodicitee`, `montant`, `paye`, `mois`, `commentaire`, `mois_1`, `mois_2`, `mois_3`, `mois_4`, `mois_5`, `mois_6`, `mois_7`, `mois_8`, `mois_9`, `mois_10`, `mois_11`, `mois_12`, `date_creation`) VALUES
(0000000086, 6, 15, 0, '1', 1, '100.00', 1, 2, 'test ponctuel r&eacute;gl&eacute;', '0.00', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-28 18:35:12'),
(0000000091, 6, 15, 0, '1', 1, '100.00', 0, 2, 'test ponctuel non r&eacute;gl&eacute;', '0.00', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-28 18:56:09'),
(0000000092, 6, 15, 0, '1', 2, '1000.00', 0, 2, 'bi mensuel', '0.00', '500.00', '500.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-28 19:10:15'),
(0000000094, 6, 15, 4, '1', 1, '123.00', 1, 2, 'test avec client', '0.00', '123.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-30 11:02:15'),
(0000000095, 6, 15, 4, '1', 3, '150.00', 1, 2, 'Va falloir raquer amore mio', '0.00', '50.00', '50.00', '50.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-30 19:07:13'),
(0000000096, 6, 15, 4, '1', 12, '150.00', 0, 2, '', '0.00', '13.64', '13.64', '13.64', '13.64', '13.64', '13.64', '13.64', '13.64', '13.64', '13.64', '13.64', '2013-12-30 19:09:19'),
(0000000097, 6, 15, 4, '2', 1, '99.00', 1, 2, '', '0.00', '99.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-30 19:13:36'),
(0000000098, 6, 15, 4, '1', 3, '600.00', 0, 2, 'trimestriel &eacute;tal&eacute;', '0.00', '200.00', '200.00', '200.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-31 15:19:51'),
(0000000099, 6, 15, 0, '1', 3, '123.00', 0, 1, 'tezt', '41.00', '41.00', '41.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-31 15:26:02'),
(0000000100, 6, 15, 0, '1', 12, '1000.00', 0, 1, 'test etal&eacute; janvier', '83.33', '83.33', '83.33', '83.33', '83.33', '83.33', '83.33', '83.33', '83.33', '83.33', '83.33', '83.33', '2013-12-31 17:07:52'),
(0000000101, 6, 15, 0, '1', 2, '100.00', 0, 1, 'test bimensuel etal&eacute;', '50.00', '50.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2013-12-31 17:57:11'),
(0000000102, 6, 15, 0, '1', 12, '1200.00', 0, 2, 'Annuel &eacute;tal&eacute;', '0.00', '109.09', '109.09', '109.09', '109.09', '109.09', '109.09', '109.09', '109.09', '109.09', '109.09', '109.09', '2013-12-31 18:00:45'),
(0000000103, 6, 15, 0, '1', 12, '110.00', 0, 2, '', '0.00', '10.00', '10.00', '10.00', '10.00', '10.00', '10.00', '10.00', '10.00', '10.00', '10.00', '10.00', '2014-01-01 10:59:44'),
(0000000104, 6, 15, 0, '1', 3, '300.00', 0, 2, 'TEST TRim', '0.00', '100.00', '100.00', '100.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2014-01-01 20:46:58'),
(0000000105, 6, 15, 0, '1', 3, '1000.00', 0, 10, 'dfgdfgdfg', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '333.33', '333.33', '333.33', '2014-01-09 16:16:55'),
(0000000106, 6, 15, 0, '1', 2, '300.00', 0, 2, 'test', '0.00', '150.00', '150.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2014-01-09 16:19:09');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Vider la table avant d'insérer `user`
--

TRUNCATE TABLE `user`;
--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `prenom`, `nom`, `email`, `telephone`, `password`, `inscription`, `actif`, `essai`, `montant`, `expiration`, `administrateur`, `token`, `mois_encours`, `exerciceid_encours`, `date_creation`) VALUES
(4, 'fred', 'meyrou', 'frederic@meyrou.com', '0612345678', 'derf44', '2013-11-28', 1, 0, '1234.00', '0000-00-00', 1, NULL, NULL, NULL, '2014-01-10 16:19:17'),
(6, 'elise', 'meyrou', 'elise@meyrou.com', '0612456789', 'grenouille', '2013-01-12', 1, 0, '100.00', '0000-00-00', 0, NULL, 1, 15, '2014-01-10 16:19:17'),
(8, 'Frederic', 'MEYROU', 'frederic_meyrou@yahoo.fr', '0672268111', 'derf44', '2013-12-14', 1, 0, '999.00', '2013-12-12', 0, NULL, NULL, NULL, '2014-01-10 16:19:17'),
(10, 'Fr&eacute;d&eacute;ric', 'MEYROU', 'frederic.meyrou@gmail.com', '0672268111', 'h6S2Tlv7', '2013-12-12', 0, 0, NULL, NULL, 0, '432b5f36651f5bab7e96984650487bb51417dea8', NULL, NULL, '2014-01-10 16:19:17');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
