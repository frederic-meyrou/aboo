SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `abonnement`;
CREATE TABLE IF NOT EXISTS `abonnement` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `ventilation_id` int(11) DEFAULT NULL,
  `montant` decimal(10,0) DEFAULT NULL,
  `mois` int(11) DEFAULT NULL COMMENT '1..12',
  `commentaire` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `ca`;
CREATE TABLE IF NOT EXISTS `ca` (
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `mois` int(11) DEFAULT NULL COMMENT '1..12',
  `total_abonnements` int(11) DEFAULT NULL,
  `total_charges` int(11) DEFAULT NULL,
  `salaire` int(11) DEFAULT NULL,
  `treso` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`exercice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Chiffre d''affaire mensuel';

DROP TABLE IF EXISTS `ca_mensuel`;
CREATE TABLE IF NOT EXISTS `ca_mensuel` (
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `mois` int(11) DEFAULT NULL COMMENT '1..12',
  `total_abonnements` int(11) DEFAULT NULL,
  `total_charges` int(11) DEFAULT NULL,
  `salaire` int(11) DEFAULT NULL,
  `treso` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`exercice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Chiffre d''affaire mensuel';

DROP TABLE IF EXISTS `depense`;
CREATE TABLE IF NOT EXISTS `depense` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `exercice_id` int(11) NOT NULL,
  `ventilation_id` int(11) DEFAULT NULL,
  `montant` decimal(10,0) DEFAULT NULL,
  `mois` int(11) DEFAULT NULL COMMENT '1..12',
  `commentaire` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `encaissement`;
CREATE TABLE IF NOT EXISTS `encaissement` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `abonnement_id` int(10) DEFAULT NULL,
  `montant` int(11) DEFAULT NULL,
  `mois` int(11) DEFAULT NULL COMMENT '(1..12)',
  `type` varchar(45) DEFAULT NULL,
  `compte` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `exercice`;
CREATE TABLE IF NOT EXISTS `exercice` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `premier_mois` int(11) DEFAULT NULL,
  `montant_treso_initial` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `paiement`;
CREATE TABLE IF NOT EXISTS `paiement` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `abonnement_id` int(10) unsigned zerofill DEFAULT NULL,
  `montant` int(11) DEFAULT NULL,
  `mois` int(11) DEFAULT NULL COMMENT '(1..12)',
  `type` varchar(45) DEFAULT NULL,
  `encaissement` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(45) DEFAULT NULL,
  `nom` varchar(45) DEFAULT NULL,
  `email` varchar(96) NOT NULL,
  `telephone` varchar(10) DEFAULT NULL,
  `password` varchar(12) NOT NULL,
  `inscription` date DEFAULT NULL,
  `montant` decimal(10,0) DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  `administrateur` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
