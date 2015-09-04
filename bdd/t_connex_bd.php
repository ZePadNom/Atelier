<?php
/**
 * Connexion à la base de données
 */

// require_once 'config.php';
require_once $_SESSION['PHP_PATH'] . "config/bdd_config.php";

try {
    $bdd = new PDO("mysql:host=" . HOTE . ";dbname=" . BDD, USR, MDP, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} catch (PDOException $e) {
    echo "<br>Problème  ";
    $erreur = $e->getCode();
    $message = $e->getMessage();
    echo "erreur $erreur $message\n";
}

$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>