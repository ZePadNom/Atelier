<?php

class Ticket {

	private $id;
	private $d_ouverture;
	private $d_resolution;
	private $d_validation;
	private $titre;
	private $description;
	private $note;
	private $lieu;
	private $h_ouverture;
	private $h_resolution;
	private $h_validation;
	private $d_modif;
	private $h_modif;
	private $statut;
	private $categorie;
	private $importance;
	private $id_responsable;



        
	function insererTicket(PDO $pdo, array $nom_colonnes, array $colonnes) {

		$nc = "";

		foreach ($nom_colonnes as $champ) {
			$nc .= "`".$champ."`";
			if ($nom_colonnes[count($nom_colonnes)] != $champ)
				$nc .= ", ";
		}

		foreach ($colonnes as $val) {
			$c .= "'".$val."'";
			if ($colonnes[count($colonnes)] != $val)
				$c .= ", ";
		}

		$sql ="INSERT INTO `ticket`($nc)\n"
             ."VALUES ($val);";
	}
        
       

        
        function getId() {
            return $this->id;
        }

        function getD_ouverture() {
            return $this->d_ouverture;
        }

        function getD_resolution() {
            return $this->d_resolution;
        }

        function getD_validation() {
            return $this->d_validation;
        }

        function getTitre() {
            return $this->titre;
        }

        function getDescription() {
            return $this->description;
        }

        function getNote() {
            return $this->note;
        }

        function getLieu() {
            return $this->lieu;
        }

        function getH_ouverture() {
            return $this->h_ouverture;
        }

        function getH_resolution() {
            return $this->h_resolution;
        }

        function getH_validation() {
            return $this->h_validation;
        }

        function getD_modif() {
            return $this->d_modif;
        }

        function getH_modif() {
            return $this->h_modif;
        }

        function getStatut() {
            return $this->statut;
        }

        function getCategorie() {
            return $this->categorie;
        }

        function getImportance() {
            return $this->importance;
        }

        function getId_responsable() {
            return $this->id_responsable;
        }

        function setId($id) {
            $this->id = $id;
        }

        function setD_ouverture($d_ouverture) {
            $this->d_ouverture = $d_ouverture;
        }

        function setD_resolution($d_resolution) {
            $this->d_resolution = $d_resolution;
        }

        function setD_validation($d_validation) {
            $this->d_validation = $d_validation;
        }

        function setTitre($titre) {
            $this->titre = $titre;
        }

        function setDescription($description) {
            $this->description = $description;
        }

        function setNote($note) {
            $this->note = $note;
        }

        function setLieu($lieu) {
            $this->lieu = $lieu;
        }

        function setH_ouverture($h_ouverture) {
            $this->h_ouverture = $h_ouverture;
        }

        function setH_resolution($h_resolution) {
            $this->h_resolution = $h_resolution;
        }

        function setH_validation($h_validation) {
            $this->h_validation = $h_validation;
        }

        function setD_modif($d_modif) {
            $this->d_modif = $d_modif;
        }

        function setH_modif($h_modif) {
            $this->h_modif = $h_modif;
        }

        function setStatut($statut) {
            $this->statut = $statut;
        }

        function setCategorie($categorie) {
            $this->categorie = $categorie;
        }

        function setImportance($importance) {
            $this->importance = $importance;
        }

        function setId_responsable($id_responsable) {
            $this->id_responsable = $id_responsable;
        }

}

?>