<?php

function getFormElt($classe_css, $champ, $label_champ) {

	$elt = '-->'
		 . '<div class="'.$classe_css.'">'
		 . '<label>'.$label_champ.'</label>'
		 . '<span>'.$champ.'</span>'
		 . '</div>'
		 . '<!--';

	return $elt;

}

function getFormSelect($classe_css, $champ, $label_champ) {

	$elt = '-->'
		 . '<div class="'.$classe_css.'">'
		 . '<label>'.$label_champ.'</label>'
		 . $champ
		 . '</div>'
		 . '<!--';

	return $elt;

}


function getFormEltInput($classe_css, $champ, $val, $label_champ) {

	$elt = '-->'
		 . '<div class="'.$classe_css.'">'
		 . '<label for="'.$champ.'">'.$label_champ.'</label>'
		 . '<input type="text" id="'.$champ.'" name="'.$champ.'" value="'.$val.'">'
		 . '</div>'
		 . '<!--';

	return $elt;

}

function getFormEltTA($classe_css, $champ, $val, $label_champ) {

	$elt = '-->'
		 . '<div class="'.$classe_css.'">'
		 . '<label for="'.$champ.'">'.$label_champ.'</label>'
		 . '<textarea type="text" id="'.$champ.'" name="'.$champ.'">'
		 . $val
		 . '</textarea>'
		 . '</div>'
		 . '<!--';

	return $elt;

}

function getDetails($action, $id_ticket, $pdo) {

		// récupération du ticket

		$table = getTicket($id_ticket, $pdo);

		foreach ($table as $row) {

			$id_ticket = $row['ID_TICKET'];

			$titre = $row['TITRE'];
			$description = $row['DESCRIPTION'];

			$num_categorie = $row['NUM_CATEGORIE'];
			$nom_categorie = $row['NOM_CATEGORIE'];

			$num_importance = $row['NUM_IMPORTANCE'];
			$nom_importance = $row['NOM_IMPORTANCE'];

			$num_lieu = $row['NUM_LIEU'];
			$nom_lieu = $row['NOM_LIEU'];

			$num_statut = $row['NUM_STATUT'];
			$nom_statut = $row['NOM_STATUT'];

			$d_ouverture = $row['D_OUVERTURE'];

			$id_responsable = $row['ID_RESPONSABLE'];
			$nom_responsable = $row['NOM_RESPONSABLE'];

		}

		// Bouton édition
		
		if (isset($_POST['edition']))

			$bouton_edition = '<input type="submit" name="annul_edition" value="Annuler les modifications" class="button">';

		elseif ($action == 'historique' || $_SESSION['usr_connected']['classe'] == 3)

			$bouton_edition = '';

		else

			$bouton_edition = '<input type="submit" name="edition" value="Modifier le ticket" class="button">';

		// création du formulaire

		
		$details = '<form class="info_ticket" method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"].'?ticketid='.$_GET['ticketid']).'">'
				 . '<h2>'."Information sur le ticket".'</h2>'
				 . $bouton_edition
				 . '<!--';

		switch ($action) {

			case 'attribuer':
				
				$details .= getFormElt('mi_largeur', $titre, 'Titre')
						  . getFormSelect('mi_largeur highlight', listerImportance($num_importance, $pdo).getIconImportance($num_importance), 'Importance')
						  . getFormElt('pleine_largeur', $description, 'Description')
						  . getFormSelect('mi_largeur highlight', listerLieu($num_lieu, $pdo), 'Lieu')
						  . getFormSelect('mi_largeur highlight', listerCategorie($num_categorie, $pdo), 'Catégorie')
						  . getFormSelect('mi_largeur highlight', listerAgent($id_responsable, $pdo), 'Agent')
						  . getFormElt('mi_largeur', $nom_statut, 'Statut')
						  . '-->'
						  . '<!--'
						  . '-->'
						  . '<div class="boutons">'
			  			  . '<input type="submit" name="attribuer" class="button details" value="Attribuer le ticket">'
						  . '</div>'
						  . '<!--';

				break;

			case 'encours':
				
				$details .= getFormElt('mi_largeur', $titre, 'Titre')
						  . getFormElt('mi_largeur', $nom_importance.getIconImportance($num_importance), 'Importance')
						  . getFormElt('pleine_largeur', $description, 'Description')
						  . getFormElt('mi_largeur', $nom_lieu, 'Lieu')
						  . getFormElt('mi_largeur', $nom_categorie, 'Catégorie')
						  . getFormElt('mi_largeur', $nom_responsable, 'Agent')
						  . getFormElt('mi_largeur', $nom_statut, 'Statut')
						  . '-->'
						  . '<div class="boutons">'
						  . '<input type="submit" name="resoudre" class="button details" value="Résoudre le ticket">'
						  . '</div>'
						  . '<div class="boutons">'
						  . '<input type="submit" name="attente" class="button details" value="Mettre le ticket en attente">'
						  . '</div>'
						  . '<!--';

				break;

			case 'resoudre':


				$boutons_res = $_SESSION['usr_connected']['classe'] == 3
							 ? ""
							 : '<div class="boutons">'
							 . '<input type="submit" name="cloturer" class="button details" value="Valider et cloturer le ticket">'
							 . '</div>'
							 . '<div class="boutons">'
							 . '<input type="submit" name="refuser" class="button details" value="Refuser le ticket et le remettre en cours">'
							 . '</div>';
				
				$details .= getFormElt('mi_largeur', $titre, 'Titre')
						  . getFormElt('mi_largeur', $nom_importance.getIconImportance($num_importance), 'Importance')
						  . getFormElt('pleine_largeur', $description, 'Description')
						  . getFormElt('mi_largeur', $nom_lieu, 'Lieu')
						  . getFormElt('mi_largeur', $nom_categorie, 'Catégorie')
						  . getFormElt('mi_largeur', $nom_responsable, 'Agent')
						  . getFormElt('mi_largeur', $nom_statut, 'Statut')
						  . '-->'
						  . '<!--'
						  . '-->'
						  . $boutons_res
						  . '<!--';

				break;

			case 'edition':
				
				$details .= getFormEltInput('mi_largeur', 'titre', $titre, 'Titre')
						  . getFormSelect('mi_largeur', listerImportance($num_importance, $pdo).getIconImportance($num_importance), 'Importance')
						  . getFormEltTA('pleine_largeur', 'description', $description, 'Description')
						  . getFormSelect('mi_largeur', listerLieu($num_lieu, $pdo), 'Lieu')
						  . getFormSelect('mi_largeur', listerCategorie($num_categorie, $pdo), 'Catégorie')
						  . getFormSelect('mi_largeur', listerAgent($id_responsable, $pdo), 'Agent')
						  . getFormElt('mi_largeur', $nom_statut, 'Statut')
						  . '-->'
						  . '<!--'
						  . '-->'
						  . '<div class="boutons">'
						  . '<input type="submit" name="editer" class="button details" value="Enregistrer les modifications">'
						  . '</div>'
						  . '<!--';
				
				break;

			case 'historique':

				$details .= getFormElt('mi_largeur', $titre, 'Titre')
						  . getFormElt('mi_largeur', $nom_importance.getIconImportance($num_importance), 'Importance')
						  . getFormElt('pleine_largeur', $description, 'Description')
						  . getFormElt('mi_largeur', $nom_lieu, 'Lieu')
						  . getFormElt('mi_largeur', $nom_categorie, 'Catégorie')
						  . getFormElt('mi_largeur', $nom_responsable, 'Agent')
						  . getFormElt('mi_largeur', $nom_statut, 'Statut');
				
				break;
			
			default:

				$details .= getFormElt('mi_largeur', $titre, 'Titre')
						  . getFormElt('mi_largeur', $nom_importance.getIconImportance($num_importance), 'Importance')
						  . getFormElt('pleine_largeur', $description, 'Description')
						  . getFormElt('mi_largeur', $nom_lieu, 'Lieu')
						  . getFormElt('mi_largeur', $nom_categorie, 'Catégorie')
						  . getFormElt('mi_largeur', $nom_responsable, 'Agent')
						  . getFormElt('mi_largeur', $nom_statut, 'Statut');

				
				break;
		}

	$details .= '-->'
			  . '</form>';

	return $details;
}

// Si l'utilisateur a envoyer le formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	include_once "../php/test_input.php";

	$session_id = $_SESSION['usr_connected']['id'];

	$table = getTicket($_GET['ticketid'], $bdd);

		foreach ($table as $row) {

			$id_ticket = $row['ID_TICKET'];

			$titre = $row['TITRE'];
			$description = $row['DESCRIPTION'];

			$num_categorie = $row['NUM_CATEGORIE'];
			$nom_categorie = $row['NOM_CATEGORIE'];

			$num_importance = $row['NUM_IMPORTANCE'];
			$nom_importance = $row['NOM_IMPORTANCE'];

			$num_lieu = $row['NUM_LIEU'];
			$nom_lieu = $row['NOM_LIEU'];

			$num_statut = $row['NUM_STATUT'];
			$nom_statut = $row['NOM_STATUT'];

			$d_ouverture = $row['D_OUVERTURE'];

			$id_responsable = $row['ID_RESPONSABLE'];
			$nom_responsable = $row['NOM_RESPONSABLE'];

		}


	/*
	 * Annuler le mode edition
	 * -----------------------
	 */
	if (isset($_POST['annul_edition'])) {
		header('Location : '.htmlspecialchars($_SERVER["REQUEST_URI"]/*.'?ticketid='.$_GET['ticketid']*/));
		// exit();
	}


	/*
	 * Si formulaire d'édition envoyer
	 * -------------------------------
	 */
	if (isset($_POST['editer'])) {

		// Initialisation des variables d'erreur	
		$pdo_erreur = $titre_err = $description_err = $categorie_err = $importance_err = $lieu_err = $agent_err = FALSE;

		$champ_mod = array();


		/*
		 * Titre
		 * -----------
		 * ~ Requis
		 * ~ Pas de condition de validation particulière 
		 */
		if (empty($_POST["titre"])) {
			$titre_err = TRUE;
		} else {
			if ($_POST["titre"] != $titre) {
				$champ_mod[] = 'Titre';
				$champ_lib[] = $titre;
			}

			$titre = test_input($_POST["titre"]);
		}

		/*
		 * Description
		 * -----------
		 * ~ Requis
		 * ~ Pas de condition de validation particulière 
		 */
		if (empty($_POST["description"])) {
			$description_err = TRUE;
		} else {
			if ($_POST["description"] != $description) {
				$champ_mod[] = 'Description';
				$champ_lib[] = $description;
			}
			$description = test_input($_POST["description"]);
		}

		/*
		 * Catégorie (liste)
		 * ------------
		 * ~ Facultatif
		 */
		if (($_POST["categorie"] != $num_categorie)) {
			$champ_mod[] = 'Catégorie';
			$champ_lib[] = $nom_categorie;
		}

		$categorie = $_POST["categorie"];

		/*
		 * Attribué à (liste)
		 * ------------------
		 * ~ Requis 
		 * ~ Facultatif
		 */
		
		echo $_POST["agent"], " ", $id_responsable;

		if (($_POST["agent"] != $id_responsable && $_POST["agent"] != 0)) {
			$champ_mod[] = 'Responsable';
			$champ_lib[] = $nom_responsable;
		}

		if (($_POST["agent"] == 0))
			$agent_err = TRUE;

		$agent = $_POST["agent"];

		/*
		 * Importance (liste)
		 * ------------------
		 * ~ Requis 
		 * ~ Facultatif
		 */
		if (($_POST["importance"] != $num_importance)) {
			$champ_mod[] = 'Importance';
			$champ_lib[] = $nom_importance;
		}
		
		if (($_POST["importance"] == 1))
			$importance_err = TRUE;
		
		$importance = $_POST["importance"];
		
		/*
		 * Lieu (liste)
		 * ------------
		 * ~ Requis
		 */
		if (($_POST["lieu"] != $num_lieu)) {
			$champ_mod[] = 'Lieu';
			$champ_lib[] = $nom_lieu;
		}
		
		$lieu = $_POST["lieu"];

		/*
		 * Si titre & description correct et que l'agent et l'importance sont ok, on envoie le formulaire
		 */
		if (!($titre_err || $description_err)) {

			$agent = $agent_err ? "NULL" : $agent;

			$sql = "UPDATE `TICKET`"
				 . "SET `ID_RESPONSABLE`= $agent,\n\t"
				 . "`NUM_CATEGORIE`= $categorie,\n\t"
				 . "`NUM_LIEU`= $lieu,\n\t"
				 . "`NUM_IMPORTANCE` = $importance,\n\t"
				 . "`TITRE`= \"$titre\",\n\t"
				 . "`DESCRIPTION`= \"$description\"\n"
				 . "WHERE `TICKET`.`ID` = $id_ticket";

			try {
				$bdd->exec($sql);
			} catch(PDOException $pdoe) {
				$pdo_erreur = TRUE;
			}

			$snackbar = $pdo_erreur ? "Désolé mais une erreur est apparue".'<br><span>'.$pdoe->getMessage().'</span>'
									: "Le ticket à été correctement modifier, les traces de ces modifications ont été enregistrées.";

			$id_usr = $_SESSION['usr_connected']['id'];

			for ($i=0; $i < count($champ_mod); $i++) { 

				newEvoChamp($id_ticket, $id_usr, $champ_mod[$i], $champ_lib[$i], $bdd);

			}

			// $snackbar = "";

			unset($_POST);

		} else {
			$snackbar = "Si vous modifiez quelque chose, n'effacer pas le titre et/ou la description au moins, et l'agent responsable et l'importance sont toujours obligatoire.";
		}

	}

}

?>