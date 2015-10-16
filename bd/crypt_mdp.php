<?php
	try	{
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO('mysql:host=localhost; dbname=atelier_v2', 'root', 'utbcdscssc', $pdo_options);
		$bdd->exec('SET NAMES utf8');
	} catch (Exception $e) {
		die('Erreur de connexion : ' . $e->getMessage());
	}

	// Selection des mots de passe

	$sql = "SELECT `pass_connex` FROM `utilisateur`";
	$res = $bdd->query($sql);
	$data = $res->fetchAll(PDO::FETCH_ASSOC);

	// Cryptage

	$update = "";
	$i = 0;

	foreach($data as $mdp) { 

		$mdp_crypt[$i] = password_hash($mdp['pass_connex'], PASSWORD_DEFAULT);

		$update .= "UPDATE `utilisateur` SET `pass_connex`='$mdp_crypt[$i]' WHERE `pass_connex`='".$mdp['pass_connex']."';";

		$i++;

	}

	echo $update;

	$alter = "ALTER TABLE `utilisateur` CHANGE `pass_connex` `pass_connex` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;";
		   //. "ALTER TABLE `classe` CHANGE `nom` `intitule` CHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;";

	try {
		$bdd->exec($alter);
		$bdd->exec($update);
	} catch (PDOException $e) {
		echo $e->getMessage();
	}	
	
?>