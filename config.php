<?php

/**
 * Fichier de configuration de l'application 
 * -----------------------------------------
 * ~ Modifier les chemins d'installation en fonction du serveur
 * ~ Modifier les paramètres de la base de données en fonctions du serveur
 */


/**
 * Chemins
 * -------
 * @constant HTML_PATH Chemin 'racine' pour créer les liens en HTML (pour les adresses URL)
 * @constant PHP_PATH Chemin 'racine' pour créer les liens en PHP (propre au serveur)
 */

define('HTML_PATH', '/'.basename(__DIR__).'/');
define('PHP_PATH', __DIR__.'/');

/**
 * Base de données MySQL
 * ---------------------
 * @constant BDD Nom de la base de données (ex. 'atelier_v2')
 * @constant USR utilisateur de la base de données (ex. 'root')
 * @constant MDP Mot de passe de l'utilisateur (ex. 'azerty')
 * @constant HOTE Nom d'hôte où le serveur MySQL est installé (ex. 'localhost')
 */

// define("BDD", 'atelier_v2');
// define("USR", 'LOZANOA');
// define("MDP", 'oaYPQp');
// define("HOTE", 'localhost');

?>