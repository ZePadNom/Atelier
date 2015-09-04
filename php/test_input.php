<?php

/**
 * Cette fonction prend une données fourni en paramètre et
 * la formate en vue d'une requête SQL pour prévenir de certaines attaques
 *
 * @param string $data Données de champs de formulaire
 * @return string Données formaté pour la requête SQL
 */
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>