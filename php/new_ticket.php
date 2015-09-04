<?php session_start();
	include('./include/header.php');
	include('./include/verif.php');
	include('./php/t_connex_bd.php');
?>
	<h1>Nouveau ticket</h1>
	<form id='new_ticket' method='post' action="./php/t_new_ticket.php">
		<fieldset>
			<legend>Informations</legend>
			<label for ='titre'>Titre :</label><input id='titre' name='titre' type='text'required /><br/>
			<label for ='description'>Description :</label><textarea id='description' name='description' type='text'></textarea><br/>
			<!-- <label for ='lieu'>Lieu :</label><input id='lieu' name='lieu' type='text'/><br/> -->
			<label for='lieu'>Lieu :</label>
			<select>
				<option value=''>Non défini</option>
				<?php
					//WHILE LIEU
					$repLieu = $bdd->query('Select * from lieu');
					while($lLieu = $repLieu->fetch()){
					?>
				<option value="<?php echo($lLieu['num']);?>"><?php echo($lLieu['nom']);?></option>
					<?php
					}//Fin while
				?>
			</select>
			<label for ='categorie'>Categorie :</label>
				<select id='categorie' name='categorie'/>
					<?php
						//WHILE CATEGORIE
						$repCat = $bdd->query('Select * from categorie');
						while($lCat = $repCat->fetch()){
						?>
					<option value="<?php echo($lCat['num']);?>"><?php echo($lCat['nom']);?></option>
						<?php
						}//Fin while
					?>
				</select>
		</fieldset>
		
		<?php
			/*Le fieldset suivant n'est pas visible pour la classe Agent*/
			if($_SESSION['classe'] != 3){
			
		?>
		<fieldset id="supp">
			<legend>Informations supplémentaires</legend>
			<label for='agent'>Attribué à :</label>
				<select id='agent' name='agent'>
					<!-- La première option est l'option NULLE (aucun agent assigné -> statut en cours d'attribution -->
					<option value=''>Non définie</option>
					<?php
						/*Les options sont dans un while pour récupérer les informations par requetes -> pas de mise à jour html en cas de mise à jour de la base de données*/
						$repUti = $bdd->query('Select * from utilisateur');
						while($lUti = $repUti->fetch())
						{
							if($lUti['classe']!=4)//4=Inactif
							{
					?>
					<option value='<?php echo($lUti['id']);?>'><?php echo($lUti['nom'] . ' ' . $lUti['prenom']);?></option>
					<?php
							}
						}//fin while
					?>
				</select>
			<label for='importance'>Importance :</label>
				<select id='importance' name='importance'>
					<?php
						/*Les options sont dans un while pour récupérer les informations par requetes -> pas de mise à jour html en cas de mise à jour de la base de données*/
						$repImp = $bdd->query('Select * from importance');
						while($lImp = $repImp->fetch())
						{
					?>
					<option value='<?php echo($lImp['num']);?>'><?php echo($lImp['nom']);?></option>
					<?php
						}//fin while
					?>
				</select>
		</fieldset>
		<?php
			}//fin if
		?>
		
		<p id='boutons'>
			<input class='bouton' type="submit" />
			<input class='bouton' type="reset" />
		</p>
	</form>
	
<?php
	include('./include/footer.html');
?>