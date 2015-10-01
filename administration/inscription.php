<?php session_start();
	include("include/header.php");
	include("include/verif.php");
	include('./php/t_connex_bd.php');
?>
<div id="new_utimisateur">
<h1>Création d'un nouvel utilisateur</h1>
	<form id='inscription' method='post' action='t_new_utilisateur.php'>
		<fieldset>
			<legend>Présentation</legend>
			<label for ='nom'>Nom</label><input id='nom' name='nom' type='text'/><br/>
			<label for ='prenom'>Prénom</label><input id='prenom' name='prenom' type='text'/><br/>
			<label>Classe :</label>
				<select id='classe' name='classe'>
				<?php
					$reqClasse = $bdd->query('Select * from classe order by nom');
					while($class = $reqClasse->fetch())
					{
				?>
				<option value='<?php echo($class['num']);?>'><?php echo($class['nom']);?></option>
				<?php
					}
				?>
				</select>
		</fieldset>
		
		<fieldset>
			<legend>Données personnelles</legend>
			<label for='mail'>E-mail</label><input id='mail' name='mail' type='text'/><br/>
			<label for='tel'>Téléphone</label><input id='telf' name='tel' type='text'/><br/>
		</fieldset>
		
		<fieldset>
			<legend>Données de connexion</legend>
			<label for ='pseudo'>Identifiant</label><input id='pseudo' name='pseudo' type='text'/><br/>
			<label for ='mot_de_passe'>Mot de passe</label><input id='mot_de_passe' name='mot_de_passe' type='password'/><br/>
		</fieldset>
		
		<p id='boutons'>
			<input class='bouton' type="submit" />
			<input class='bouton' type="reset" />
		</p>
	</form>
</div>

<?php
	include ("include/footer.html");
?>