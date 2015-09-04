<?php session_start();
	include('./include/header.php');
	include("include/verif.php");
	include('./php/t_connex_bd.php');
	
	if($_SESSION['classe']==1)
	{
?>
	
	<div>
	<h1>Création d'une nouvelle catégorie</h1>
		<form method="post" action='./php/t_new_categorie.php'>
			<fieldset>
				<legend>Catégories éxistantes</legend>
				<ul>
				<?php
					$Rcat = $bdd->query('Select nom from categorie
										order by nom');
					while($cat = $Rcat->fetch())
					{
				?>
					<li><?php echo($cat['nom']);?></li>
				<?php
					}
				?>
				</ul>
			</fieldset>
			<fieldset>
				<legend>Nouvelle catégorie</legend>
				<label for='insert_cat'>Nom</label><input id ='insert_cat' name='insert_cat'/>	
			</fieldset>
			<p class='boutons'>
				<input class='bouton' type="submit" />
				<input class='bouton' type="reset" />
			</p>
			<fieldset>
				<legend>Supprimer une catégorie</legend>
				<label for ='suppr_cat'>Nom</label>
				<select id='suppr_cat' name='suppr_cat'>
					<option value="">Aucune</option>
				<?php
					$Rcat2 = $bdd->query('Select nom from Categorie
										order by nom');
					while($cat2 = $Rcat2->fetch())
					{
				?>
					<option value="<?php echo($cat2['nom']);?>"><?php echo($cat2['nom']);?></option>
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