<?php
/**
 * Connexion à la base de données
 * @var PDO $bdd Instance de l'objet PDO permettant le dialogue avec la base de données MySQL
 */

$PARAM_nom_bd = 'atelier'; // le nom de votre base de données
$PARAM_utilisateur = 'root'; // nom d'utilisateur pour se connecter
$PARAM_mot_passe = ''; // mot de passe de l'utilisateur pour se connecter
$PARAM_hote = 'localhost'; // le chemin vers le serveur
$PARAM_pdo = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

try {
    $bdd = new PDO("mysql:host=$PARAM_hote;dbname=$PARAM_nom_bd", $PARAM_utilisateur, $PARAM_mot_passe, $PARAM_pdo);
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<br>Problème  ";
    $erreur = $e->getCode();
    $message = $e->getMessage();
    echo "erreur $erreur $message\n";
}


?>