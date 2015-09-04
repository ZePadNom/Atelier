<?php session_start();
	include('./include/header.php');
	include("include/verif.php");
	include('./php/t_connex_bd.php');
	
	if($_SESSION['classe']==1)
	{
?>
	
	<div>
	<h1>Création d'un nouveau lieu</h1>
		<form method="post" action='./php/t_new_lieu.php'>
			<fieldset>
				<legend>Lieux éxistants</legend>
				<ul>
				<?php
					$Rlieu = $bdd->query('Select nom from lieu
										order by nom');
					while($lieu = $Rlieu->fetch())
					{
				?>
					<li><?php echo($lieu['nom']);?></li>
				<?php
					}
				?>
				</ul>
			</fieldset>
			<fieldset>
				<legend>Nouveau Lieu</legend>
				<label for='insert_lieu'>Nom</label><input id ='insert_lieu' name='insert_lieu'/>	
			</fieldset>
			<p class='boutons'>
				<input class='bouton' type="submit" />
				<input class='bouton' type="reset" />
			</p>
			<fieldset>
				<legend>Supprimer un lieu</legend>
				<label for ='suppr_lieu'>Nom</label>
				<select id='suppr_lieu' name='suppr_lieu'>
					<option value="">Aucun</option>
				<?php
					$Rlieu2 = $bdd->query('Select nom from lieu
										order by nom');
					while($lieu2 = $Rlieu2->fetch())
					{
				?>
					<option value="<?php echo($lieu2['nom']);?>"><?php echo($lieu2['nom']);?></option>
				<?php
					}
				?>
				</select>
			</fieldset>
			<p class='boutons'>
				<input class='bouton' type="submit" value="Supprimer" />
				<input class='bouton' type="reset" />
			</p>
			
		</form>
	</div>
<?php
	}else{
		header('Location: ./page_principale.php');
	}
	include ("include/footer.html");
?>