<?php

	try	{
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO('mysql:host=localhost; dbname=atelier', 'root', 'azerty', $pdo_options);
		$bdd->exec('SET NAMES utf8');
	} catch (Exception $e) {
		die('Erreur de connexion : ' . $e->getMessage());
	}

	// Selection des noms

	$sql = "SELECT `id`, `nom`, `prenom` FROM `utilisateur`;";
	$res = $bdd->query($sql);
	$table = $res->fetchAll(PDO::FETCH_ASSOC);

	foreach ($table as $row) {
		$nom = ucfirst(strtolower($row['nom']));
		$prenom = ucfirst(strtolower($row['prenom']));
		$id = $row['id'];


		$sql = "UPDATE `utilisateur` SET `nom` = '$nom', `prenom` = '$prenom' WHERE `id` = $id;";
	
		echo "<br>", $sql, "<br>";

		try	{
			$bdd->exec($sql);
		} catch (Exception $e) {
			echo $e->getMessage();
		}


	}
?>