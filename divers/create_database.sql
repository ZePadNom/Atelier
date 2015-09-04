DROP DATABASE IF EXISTS atelier_v2;

CREATE DATABASE IF NOT EXISTS atelier_v2;
USE atelier_v2;

# -----------------------------------------------------------------------------
#       TABLE : STATUT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS STATUT
 (
   NUM INTEGER(4) NOT NULL AUTO_INCREMENT ,
   NOM VARCHAR(128) NULL  
   , PRIMARY KEY (NUM) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : COMMENTAIRE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS COMMENTAIRE
 (
   ID_TICKET INTEGER(4) NOT NULL  ,
   ID INTEGER(4) NOT NULL  ,
   ID_UTILISATEUR INTEGER(4) NOT NULL  ,
   CONTENU VARCHAR(255) NULL  ,
   H_COM TIME NULL  ,
   D_COM DATE NULL  
   , PRIMARY KEY (ID_TICKET,ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE COMMENTAIRE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_COMMENTAIRE_UTILISATEUR
     ON COMMENTAIRE (ID_UTILISATEUR ASC);

CREATE  INDEX I_FK_COMMENTAIRE_TICKET
     ON COMMENTAIRE (ID_TICKET ASC);

# -----------------------------------------------------------------------------
#       TABLE : IMPORTANCE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS IMPORTANCE
 (
   NUM INTEGER(4) NOT NULL AUTO_INCREMENT ,
   NOM VARCHAR(128) NULL  
   , PRIMARY KEY (NUM) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : UTILISATEUR
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS UTILISATEUR
 (
   ID INTEGER(4) NOT NULL AUTO_INCREMENT ,
   NUM_CLASSE INTEGER(4) NOT NULL  ,
   NOM VARCHAR(64) NULL  ,
   PRENOM VARCHAR(64) NULL  ,
   FONCTION VARCHAR(64) NULL  ,
   TEL INTEGER(4) NULL  ,
   MAIL VARCHAR(128) NULL  ,
   ID_CONNEX VARCHAR(128) NULL  ,
   PASS_CONNEX VARCHAR(255) NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE UTILISATEUR
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_UTILISATEUR_CLASSE
     ON UTILISATEUR (NUM_CLASSE ASC);

# -----------------------------------------------------------------------------
#       TABLE : LIEU
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS LIEU
 (
   NUM INTEGER(4) NOT NULL AUTO_INCREMENT ,
   NOM VARCHAR(128) NULL  
   , PRIMARY KEY (NUM) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : ACTION
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS ACTION
 (
   NUM INTEGER(4) NOT NULL AUTO_INCREMENT ,
   NOM VARCHAR(128) NULL  
   , PRIMARY KEY (NUM) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : EVOLUTION
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS EVOLUTION
 (
   ID_TICKET INTEGER(4) NOT NULL  ,
   ID CHAR(32) NOT NULL  ,
   ID_UTILISATEUR INTEGER(4) NOT NULL  ,
   NUM_ACTION INTEGER(4) NOT NULL  ,
   CHAMP VARCHAR(128) NULL  ,
   LIB_CHAMP VARCHAR(255) NULL  ,
   H_EVO TIME NULL  ,
   D_EVO DATE NULL  
   , PRIMARY KEY (ID_TICKET,ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE EVOLUTION
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_EVOLUTION_UTILISATEUR
     ON EVOLUTION (ID_UTILISATEUR ASC);

CREATE  INDEX I_FK_EVOLUTION_TICKET
     ON EVOLUTION (ID_TICKET ASC);

CREATE  INDEX I_FK_EVOLUTION_ACTION
     ON EVOLUTION (NUM_ACTION ASC);

# -----------------------------------------------------------------------------
#       TABLE : TICKET
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TICKET
 (
   ID INTEGER(4) NOT NULL AUTO_INCREMENT ,
   ID_RESPONSABLE INTEGER(4) NULL  ,
   NUM_CATEGORIE INTEGER(4) NOT NULL  ,
   NUM_LIEU INTEGER(4) NOT NULL  ,
   NUM_STATUT INTEGER(4) NOT NULL  ,
   ID_CREATEUR INTEGER(4) NOT NULL  ,
   NUM_IMPORTANCE INTEGER(4) NOT NULL  ,
   TITRE VARCHAR(32) NULL  ,
   DESCRIPTION VARCHAR(255) NULL  ,
   D_OUVERTURE DATE NULL  ,
   H_OUVERTURE TIME NULL  
   , PRIMARY KEY (ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE TICKET
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_TICKET_UTILISATEUR
     ON TICKET (ID_RESPONSABLE ASC);

CREATE  INDEX I_FK_TICKET_CATEGORIE
     ON TICKET (NUM_CATEGORIE ASC);

CREATE  INDEX I_FK_TICKET_LIEU
     ON TICKET (NUM_LIEU ASC);

CREATE  INDEX I_FK_TICKET_STATUT
     ON TICKET (NUM_STATUT ASC);

CREATE  INDEX I_FK_TICKET_UTILISATEUR1
     ON TICKET (ID_CREATEUR ASC);

CREATE  INDEX I_FK_TICKET_IMPORTANCE
     ON TICKET (NUM_IMPORTANCE ASC);

# -----------------------------------------------------------------------------
#       TABLE : CLASSE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CLASSE
 (
   NUM INTEGER(4) NOT NULL AUTO_INCREMENT ,
   NOM VARCHAR(128) NULL  
   , PRIMARY KEY (NUM) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : CATEGORIE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CATEGORIE
 (
   NUM INTEGER(4) NOT NULL AUTO_INCREMENT ,
   NOM VARCHAR(128) NULL  
   , PRIMARY KEY (NUM) 
 ) 
 comment = "";


# -----------------------------------------------------------------------------
#       CREATION DES REFERENCES DE TABLE
# -----------------------------------------------------------------------------


ALTER TABLE COMMENTAIRE 
  ADD FOREIGN KEY FK_COMMENTAIRE_UTILISATEUR (ID_UTILISATEUR)
      REFERENCES UTILISATEUR (ID) ;


ALTER TABLE COMMENTAIRE 
  ADD FOREIGN KEY FK_COMMENTAIRE_TICKET (ID_TICKET)
      REFERENCES TICKET (ID) ;


ALTER TABLE UTILISATEUR 
  ADD FOREIGN KEY FK_UTILISATEUR_CLASSE (NUM_CLASSE)
      REFERENCES CLASSE (NUM) ;


ALTER TABLE EVOLUTION 
  ADD FOREIGN KEY FK_EVOLUTION_UTILISATEUR (ID_UTILISATEUR)
      REFERENCES UTILISATEUR (ID) ;


ALTER TABLE EVOLUTION 
  ADD FOREIGN KEY FK_EVOLUTION_TICKET (ID_TICKET)
      REFERENCES TICKET (ID) ;


ALTER TABLE EVOLUTION 
  ADD FOREIGN KEY FK_EVOLUTION_ACTION (NUM_ACTION)
      REFERENCES ACTION (NUM) ;


ALTER TABLE TICKET 
  ADD FOREIGN KEY FK_TICKET_UTILISATEUR (ID_RESPONSABLE)
      REFERENCES UTILISATEUR (ID) ;


ALTER TABLE TICKET 
  ADD FOREIGN KEY FK_TICKET_CATEGORIE (NUM_CATEGORIE)
      REFERENCES CATEGORIE (NUM) ;


ALTER TABLE TICKET 
  ADD FOREIGN KEY FK_TICKET_LIEU (NUM_LIEU)
      REFERENCES LIEU (NUM) ;


ALTER TABLE TICKET 
  ADD FOREIGN KEY FK_TICKET_STATUT (NUM_STATUT)
      REFERENCES STATUT (NUM) ;


ALTER TABLE TICKET 
  ADD FOREIGN KEY FK_TICKET_UTILISATEUR1 (ID_CREATEUR)
      REFERENCES UTILISATEUR (ID) ;


ALTER TABLE TICKET 
  ADD FOREIGN KEY FK_TICKET_IMPORTANCE (NUM_IMPORTANCE)
      REFERENCES IMPORTANCE (NUM) ;

# -----------------------------------------------------------------------------
#       INSERTION DES COLONNES
# -----------------------------------------------------------------------------

INSERT INTO `CLASSE` (`NOM`) VALUES
('Administrateur'),
('Agent chef'),
('Agent'),
('Inactif');

INSERT INTO `CATEGORIE` (`NOM`) VALUES
('Aucune'),
('Electricité'),
('Plomberie'),
('Espaces verts'),
('Peinture'),
('Menuiserie'),
('Déménagement'),
('Informatique'),
('Minibus 1020'),
('Minibus 1021'),
('Minibus 1022');

INSERT INTO `IMPORTANCE` (`NOM`) VALUES
('Aucune'),
('Faible'),
('Forte'),
('Sous délai');

INSERT INTO `LIEU` (`NOM`) VALUES
('Aucun'),
('Bât B0'),
('CDI'),
('Maison des Lycéens'),
('Selfs'),
('Préfabriqués'),
('Gymnase'),
('Cuisine'),
('Administration (Bât A)'),
('Bât  B1'),
('Bât B2'),
('Bât B3'),
('Atelier'),
('Chaufferie (Bât D)'),
('Bât E2 -RDC'),
('Bât E2 - Dortoir G'),
('Bât E2 - Dortoir F2'),
('Bât E2 - Dortoir F3'),
('Bât E3'),
('Logement PETIT'),
('Logement GRAND'),
('Logement INTERNAT'),
('Infirmerie'),
('Extérieurs'),
('Bât E1'),
('Salle polyvalente');

INSERT INTO `ACTION` (`NOM`) VALUES
('Attribuer'),
('Résoudre'),
('Mise en attente'),
('Modifier'),
('Cloturer'),
('Refuser');

INSERT INTO `STATUT` (`nom`) VALUES
('En cours d''attribution'),
('En cours'),
('En attente'),
('Résolu'),
('Cloturé');

INSERT INTO `UTILISATEUR` (`NOM`, `PRENOM`, `TEL`, `MAIL`, `NUM_CLASSE`, `ID_CONNEX`, `PASS_CONNEX`) VALUES
('Lozano', 'Anthony', NULL, NULL, 1, 'adminatelier', '$2y$10$4M6pIpa4D.3JTtMt14V4YuCNyErfJWIo7dU2x1yLbMbCLXWajBrfq'),
('Grelet', 'Eric', 611822175, 'g.grelet@cr-poitou-CHArentes.fr', 2, 'grelet', '$2y$10$FGEgZca6SmSABZo4biEIXeQEegvrbYw6gWOlrWo7PeQMmoxfblVZa'),
('Vicedo', 'Thierry', 627476026, 't.vicedo@cr-poitou-charentes.fr', 3, 'vicedo', '$2y$10$p7qsA3M.rO709z3aq7p13OMwMVhWWJ9D/gpA4bxhMQQ9SlUd2Rf5S'),
('Cascarino', 'Fabienne', 627478404, 'fabienne.cascarino@ac-poitiers.fr', 1, 'cascarino', '$2y$10$RHmPJOkcptyv/ak6JS9bsejSDIZAcBbGcxPvituy383B5Ovmnaipq'),
-- ('Pell', 'Denis', NULL, NULL, 4, 'dpelle', '$2y$10$o1e5Y4.tGQASL4u.Aih2ieU42R7Sa55fj6i.mOkPQ/79iQu3odv1C'),
-- ('Portier', 'Christophe', 611822378, 'c.potier@cr-poitou-charentes.fr', 3, 'portier', '$2y$10$wYshaDa5fNBy0JnBBqoqu.92SFyBoafHHMsjOR/Wg8PsT.tr44LO6'),
('Raimond', 'Viviane', 610717636, 'v.raimond@cr-poitou-charentes.fr', 2, 'raimond', '$2y$10$qyMPOkKzSQR1TuCrGRkwMeDYN/8l58ihRDTXSyQayAhPZ2Yd1gGfu'),
('Moretti', 'Bernard', NULL, 'b.moretti@cr-poitou-charentes.fr', 3, 'moretti', '$2y$10$j7YvL3Xl6nFNyfk5Xz7upO51N0xKqjd/4aMxUbKiGnujap1MvS9r6'),
('Chassagnoux', 'Odile', NULL, NULL, 1, 'chassagnoux', '$2y$10$TvvyJtpnpQ2YYBGHAQMnNe1/LJuQXCnKPIh58ljq73.YUG55Flobe'),
('Dousset', 'Emilien', NULL, 'emilien.dousset@ac-poitiers.fr', 1, 'DOUSSEE', '$2y$10$f7OkqlJTJ/EKMD2wqlZmOOazzFEL7iFtug9WZaesfUyLEYFfIJVr6');

INSERT INTO `utilisateur` (`NUM_CLASSE`, `NOM`, `PRENOM`, `FONCTION`, `TEL`, `MAIL`, `ID_CONNEX`, `PASS_CONNEX`) VALUES
(1, 'Lozano', 'Anthony', 'Developpeur', NULL, NULL, 'adminatelier', '$2y$10$4M6pIpa4D.3JTtMt14V4YuCNyErfJWIo7dU2x1yLbMbCLXWajBrfq'),
(3, 'Moretti', 'Bernard', NULL, NULL, 'b.moretti@cr-poitou-charentes.fr', 'moretti', '$2y$10$j7YvL3Xl6nFNyfk5Xz7upO51N0xKqjd/4aMxUbKiGnujap1MvS9r6'),
(1, 'Chassagnoux', 'Odile', 'Adjoint gestionnaire', NULL, NULL, 'chassagnoux', '$2y$10$TvvyJtpnpQ2YYBGHAQMnNe1/LJuQXCnKPIh58ljq73.YUG55Flobe'),
(1, 'Dousset', 'Emilien', 'Adjoint gestionnaire', NULL, 'emilien.dousset@ac-poitiers.fr', 'DOUSSEE', '$2y$10$f7OkqlJTJ/EKMD2wqlZmOOazzFEL7iFtug9WZaesfUyLEYFfIJVr6'),
(2, 'Raimond', 'Viviane', NULL, 610717636, 'v.raimond@cr-poitou-charentes.fr', 'raimond', '$2y$10$qyMPOkKzSQR1TuCrGRkwMeDYN/8l58ihRDTXSyQayAhPZ2Yd1gGfu'),
(2, 'Grelet', 'Eric', 'Responsable de l''atelier', 611822175, 'g.grelet@cr-poitou-charentes.fr', 'grelet', '$2y$10$FGEgZca6SmSABZo4biEIXeQEegvrbYw6gWOlrWo7PeQMmoxfblVZa'),
(3, 'Vicedo', 'Thierry', NULL, 627476026, 't.vicedo@cr-poitou-charentes.fr', 'vicedo', '$2y$10$p7qsA3M.rO709z3aq7p13OMwMVhWWJ9D/gpA4bxhMQQ9SlUd2Rf5S'),
(1, 'Cascarino', 'Fabienne', NULL, 627478404, 'fabienne.cascarino@ac-poitiers.fr', 'cascarino', '$2y$10$RHmPJOkcptyv/ak6JS9bsejSDIZAcBbGcxPvituy383B5Ovmnaipq');

-- INSERT INTO `TICKET` (`ID`, `ID_RESPONSABLE`, `NUM_CATEGORIE`, `NUM_LIEU`, `NUM_STATUT`, `ID_CREATEUR`, `NUM_IMPORTANCE`, `TITRE`, `DESCRIPTION`, `D_OUVERTURE`, `H_OUVERTURE`) VALUES
-- (1, NULL, 1, 1, 1, 1, 1, 'Coupe de cheveux', 'Il faut couper les cheveux du stagiaire, ça ne vas plus là !', '2015-02-06', '09:36:47'),
-- (2, NULL, 1, 2, 1, 1, 1, 'Dylan est balèze', 'Donner lui une médaille d\'or vite !', '2015-02-06', '09:37:31'),
-- (3, NULL, 7, 10, 1, 1, 1, 'Ecrans 4K pour le stagiaire', 'Des écrans 4k en plus c\'est vraiment indispensable au travail', '2015-02-06', '09:39:21'),
-- (4, NULL, 4, 24, 1, 1, 1, 'Cirque', 'Pourquoi il y a un cirque dehors ?', '2015-02-06', '09:40:10'),
-- (5, 2, 4, 24, 2, 1, 3, 'Hippie', 'Vive les fleurs', '2015-02-06', '09:40:50');