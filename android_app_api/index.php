<?php
 
/**
 * Fichier pour manipuler toute les requêtes de l'API
 * Accepte uniquement par la superglobale POST
 * 
 * Chaque requête est indentifiée par un mot clef (@var $mclef)
 * Les réponse seront transmise en JSON
 */

/** 
 * Si POST Soumis
 */
if ($_SERVER["REQUEST_METHOD"] == "POST"  && $_POST['mclef'] != '') {

    $mclef = $_POST['mclef'];
 
    /**
     * Inclusion(s)
     * ------------
     * ~ Fonction de la base de données
     */
    require_once 'include/DB_Functions.php';

    $db = new DB_Functions();
 
    // Tableau de réponse
    $response = array("mclef" => $mclef, "error" => FALSE);
 
    /**
     * Structure conditionnel permettant d'effectuer les fonctions
     * et de renvoyer les résultats
     * 
     * @var string $mclef Identifie les requêtes
     */
    
    /**
     * Demande de connexion
     */
    if ($mclef == 'login') {


        $identifiant = $_POST['id'];
        $mdp = $_POST['mdp'];
 
        // On vérifie l'utilisateur
        $user = $db->getUserByEmailAndPassword($email, $password);

        // Si utilisateur correct, on autorise la connexion, sinon erreur
        if ($user != false) {

            $response["error"] = FALSE;
            echo json_encode($response);

        } else {

            $response["error"] = TRUE;
            $response["error_msg"] = "Identifiant incorrect !";
            echo json_encode($response);

        }

    /**
     * Demande de la liste compléte des tickets
     */
    }  else if ($mclef == 'gettickets') {

		$tickets = $bd->getTickets;

		$response["error"] = FALSE;
		$response["tickets"] = $tickets;

		echo json_encode($response);

	}  else if (false) {

	    # rien pour l'instant ...


	} else {

	    $response["error"] = TRUE;
	    $response["error_msg"] = "Erreur ! Aucun paramètre détecté !";

	    echo json_encode($response);

	}

}

?>