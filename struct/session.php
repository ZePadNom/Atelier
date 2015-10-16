<?php
/**
 * 
 * @author Anthony Lozano (2015)
 *
 * @version  1.0.0
 */

session_start();

if (!isset($_SESSION['usr_connected'])) {
	header('location: /atelier_v2/utilisateur_inconnu.php');
	exit;
}

/**
 * Met fin à la session et redirige l'utilisateur sur la page de connexion
 */
function deconnexion() {
	session_unset();
	session_destroy();
	header('Location: /atelier_v2/index.php');
}

?>