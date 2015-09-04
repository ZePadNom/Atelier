-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 04 Septembre 2015 à 16:03
-- Version du serveur :  5.6.12-log
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  'atelier'
--

-- --------------------------------------------------------

--
-- Structure de la table 'action'
--

CREATE TABLE IF NOT EXISTS `action` (
  NUM int(4) NOT NULL AUTO_INCREMENT,
  NOM varchar(128) DEFAULT NULL,
  PRIMARY KEY (NUM)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table 'action'
--

INSERT INTO action VALUES(1, 'Attribuer');
INSERT INTO action VALUES(2, 'Résoudre');
INSERT INTO action VALUES(3, 'Mise en attente');
INSERT INTO action VALUES(4, 'Modifier');
INSERT INTO action VALUES(5, 'Cloturer');
INSERT INTO action VALUES(6, 'Refuser');

-- --------------------------------------------------------

--
-- Structure de la table 'categorie'
--

CREATE TABLE IF NOT EXISTS categorie (
  NUM int(4) NOT NULL AUTO_INCREMENT,
  NOM varchar(128) DEFAULT NULL,
  PRIMARY KEY (NUM)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Contenu de la table 'categorie'
--

INSERT INTO categorie VALUES(1, 'Aucune');
INSERT INTO categorie VALUES(2, 'Electricité');
INSERT INTO categorie VALUES(3, 'Plomberie');
INSERT INTO categorie VALUES(4, 'Espaces verts');
INSERT INTO categorie VALUES(5, 'Peinture');
INSERT INTO categorie VALUES(6, 'Menuiserie');
INSERT INTO categorie VALUES(7, 'Déménagement');
INSERT INTO categorie VALUES(8, 'Informatique');
INSERT INTO categorie VALUES(9, 'Minibus 1020');
INSERT INTO categorie VALUES(10, 'Minibus 1021');
INSERT INTO categorie VALUES(11, 'Minibus 1022');

-- --------------------------------------------------------

--
-- Structure de la table 'classe'
--

CREATE TABLE IF NOT EXISTS classe (
  NUM int(4) NOT NULL AUTO_INCREMENT,
  NOM varchar(128) DEFAULT NULL,
  PRIMARY KEY (NUM)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table 'classe'
--

INSERT INTO classe VALUES(1, 'Administrateur');
INSERT INTO classe VALUES(2, 'Agent chef');
INSERT INTO classe VALUES(3, 'Agent');
INSERT INTO classe VALUES(4, 'Inactif');

-- --------------------------------------------------------

--
-- Structure de la table 'commentaire'
--

CREATE TABLE IF NOT EXISTS commentaire (
  ID_TICKET int(4) NOT NULL,
  ID int(4) NOT NULL,
  ID_UTILISATEUR int(4) NOT NULL,
  CONTENU varchar(255) DEFAULT NULL,
  H_COM time DEFAULT NULL,
  D_COM date DEFAULT NULL,
  PRIMARY KEY (ID_TICKET,ID),
  KEY I_FK_COMMENTAIRE_UTILISATEUR (ID_UTILISATEUR),
  KEY I_FK_COMMENTAIRE_TICKET (ID_TICKET)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table 'evolution'
--

CREATE TABLE IF NOT EXISTS evolution (
  ID_TICKET int(4) NOT NULL,
  ID char(32) NOT NULL,
  ID_UTILISATEUR int(4) NOT NULL,
  NUM_ACTION int(4) NOT NULL,
  CHAMP varchar(128) DEFAULT NULL,
  LIB_CHAMP varchar(255) DEFAULT NULL,
  H_EVO time DEFAULT NULL,
  D_EVO date DEFAULT NULL,
  PRIMARY KEY (ID_TICKET,ID),
  KEY I_FK_EVOLUTION_UTILISATEUR (ID_UTILISATEUR),
  KEY I_FK_EVOLUTION_TICKET (ID_TICKET),
  KEY I_FK_EVOLUTION_ACTION (NUM_ACTION)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Structure de la table 'importance'
--

CREATE TABLE IF NOT EXISTS importance (
  NUM int(4) NOT NULL AUTO_INCREMENT,
  NOM varchar(128) DEFAULT NULL,
  PRIMARY KEY (NUM)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table 'importance'
--

INSERT INTO importance VALUES(1, 'Aucune');
INSERT INTO importance VALUES(2, 'Faible');
INSERT INTO importance VALUES(3, 'Forte');
INSERT INTO importance VALUES(4, 'Sous délai');

-- --------------------------------------------------------

--
-- Structure de la table 'lieu'
--

CREATE TABLE IF NOT EXISTS lieu (
  NUM int(4) NOT NULL AUTO_INCREMENT,
  NOM varchar(128) DEFAULT NULL,
  PRIMARY KEY (NUM)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Contenu de la table 'lieu'
--

INSERT INTO lieu VALUES(1, 'Aucun');
INSERT INTO lieu VALUES(2, 'Bât B0');
INSERT INTO lieu VALUES(3, 'CDI');
INSERT INTO lieu VALUES(4, 'Maison des Lycéens');
INSERT INTO lieu VALUES(5, 'Selfs');
INSERT INTO lieu VALUES(6, 'Préfabriqués');
INSERT INTO lieu VALUES(7, 'Gymnase');
INSERT INTO lieu VALUES(8, 'Cuisine');
INSERT INTO lieu VALUES(9, 'Administration (Bât A)');
INSERT INTO lieu VALUES(10, 'Bât  B1');
INSERT INTO lieu VALUES(11, 'Bât B2');
INSERT INTO lieu VALUES(12, 'Bât B3');
INSERT INTO lieu VALUES(13, 'Atelier');
INSERT INTO lieu VALUES(14, 'Chaufferie (Bât D)');
INSERT INTO lieu VALUES(15, 'Bât E2 -RDC');
INSERT INTO lieu VALUES(16, 'Bât E2 - Dortoir G');
INSERT INTO lieu VALUES(17, 'Bât E2 - Dortoir F2');
INSERT INTO lieu VALUES(18, 'Bât E2 - Dortoir F3');
INSERT INTO lieu VALUES(19, 'Bât E3');
INSERT INTO lieu VALUES(20, 'Logement PETIT');
INSERT INTO lieu VALUES(21, 'Logement GRAND');
INSERT INTO lieu VALUES(22, 'Logement INTERNAT');
INSERT INTO lieu VALUES(23, 'Infirmerie');
INSERT INTO lieu VALUES(24, 'Extérieurs');
INSERT INTO lieu VALUES(25, 'Bât E1');
INSERT INTO lieu VALUES(26, 'Salle polyvalente');

-- --------------------------------------------------------

--
-- Structure de la table 'statut'
--

CREATE TABLE IF NOT EXISTS statut (
  NUM int(4) NOT NULL AUTO_INCREMENT,
  NOM varchar(128) DEFAULT NULL,
  PRIMARY KEY (NUM)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table 'statut'
--

INSERT INTO statut VALUES(1, 'En cours d''attribution');
INSERT INTO statut VALUES(2, 'En cours');
INSERT INTO statut VALUES(3, 'En attente');
INSERT INTO statut VALUES(4, 'Résolu');
INSERT INTO statut VALUES(5, 'Cloturé');

-- --------------------------------------------------------

--
-- Structure de la table 'ticket'
--

CREATE TABLE IF NOT EXISTS ticket (
  ID int(4) NOT NULL AUTO_INCREMENT,
  ID_RESPONSABLE int(4) DEFAULT NULL,
  NUM_CATEGORIE int(4) NOT NULL,
  NUM_LIEU int(4) NOT NULL,
  NUM_STATUT int(4) NOT NULL,
  ID_CREATEUR int(4) NOT NULL,
  NUM_IMPORTANCE int(4) NOT NULL,
  TITRE varchar(32) DEFAULT NULL,
  DESCRIPTION varchar(255) DEFAULT NULL,
  D_OUVERTURE date DEFAULT NULL,
  H_OUVERTURE time DEFAULT NULL,
  PRIMARY KEY (ID),
  KEY I_FK_TICKET_UTILISATEUR (ID_RESPONSABLE),
  KEY I_FK_TICKET_CATEGORIE (NUM_CATEGORIE),
  KEY I_FK_TICKET_LIEU (NUM_LIEU),
  KEY I_FK_TICKET_STATUT (NUM_STATUT),
  KEY I_FK_TICKET_UTILISATEUR1 (ID_CREATEUR),
  KEY I_FK_TICKET_IMPORTANCE (NUM_IMPORTANCE)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;



--
-- Structure de la table 'utilisateur'
--

CREATE TABLE IF NOT EXISTS utilisateur (
  ID int(4) NOT NULL AUTO_INCREMENT,
  NUM_CLASSE int(4) NOT NULL,
  NOM varchar(64) DEFAULT NULL,
  PRENOM varchar(64) DEFAULT NULL,
  FONCTION varchar(64) DEFAULT NULL,
  TEL int(4) DEFAULT NULL,
  MAIL varchar(128) DEFAULT NULL,
  ID_CONNEX varchar(128) DEFAULT NULL,
  PASS_CONNEX varchar(255) DEFAULT NULL,
  PRIMARY KEY (ID),
  KEY I_FK_UTILISATEUR_CLASSE (NUM_CLASSE)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table 'utilisateur'
--

INSERT INTO utilisateur VALUES(1, 1, 'Dupond', 'Jean', NULL, NULL, NULL, 'adminatelier', '$2y$10$4M6pIpa4D.3JTtMt14V4YuCNyErfJWIo7dU2x1yLbMbCLXWajBrfq');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table commentaire
--
ALTER TABLE commentaire
  ADD CONSTRAINT commentaire_ibfk_1 FOREIGN KEY (ID_UTILISATEUR) REFERENCES utilisateur (ID),
  ADD CONSTRAINT commentaire_ibfk_2 FOREIGN KEY (ID_TICKET) REFERENCES ticket (ID);

--
-- Contraintes pour la table evolution
--
ALTER TABLE evolution
  ADD CONSTRAINT evolution_ibfk_1 FOREIGN KEY (ID_UTILISATEUR) REFERENCES utilisateur (ID),
  ADD CONSTRAINT evolution_ibfk_2 FOREIGN KEY (ID_TICKET) REFERENCES ticket (ID),
  ADD CONSTRAINT evolution_ibfk_3 FOREIGN KEY (NUM_ACTION) REFERENCES action (NUM);

--
-- Contraintes pour la table ticket
--
ALTER TABLE ticket
  ADD CONSTRAINT ticket_ibfk_1 FOREIGN KEY (ID_RESPONSABLE) REFERENCES utilisateur (ID),
  ADD CONSTRAINT ticket_ibfk_2 FOREIGN KEY (NUM_CATEGORIE) REFERENCES categorie (NUM),
  ADD CONSTRAINT ticket_ibfk_3 FOREIGN KEY (NUM_LIEU) REFERENCES lieu (NUM),
  ADD CONSTRAINT ticket_ibfk_4 FOREIGN KEY (NUM_STATUT) REFERENCES statut (NUM),
  ADD CONSTRAINT ticket_ibfk_5 FOREIGN KEY (ID_CREATEUR) REFERENCES utilisateur (ID),
  ADD CONSTRAINT ticket_ibfk_6 FOREIGN KEY (NUM_IMPORTANCE) REFERENCES importance (NUM);

--
-- Contraintes pour la table utilisateur
--
ALTER TABLE utilisateur
  ADD CONSTRAINT utilisateur_ibfk_1 FOREIGN KEY (NUM_CLASSE) REFERENCES classe (NUM);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
