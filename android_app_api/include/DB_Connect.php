<?php
class DB_Connect {
 
    function __construct() {
         
    }
 
    /**
     * Connexion à MySQL
     * 
     * @return PDO Instance de l'objet PDO permettant le dialogue avec la base de donnée
     */
    public function connect() {

        require_once 'include/Config.php';
    
        try {
            $pdo = new PDO("mysql:host=" . HOTE . ";dbname=" . BDD, USR, MDP, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        } catch (PDOException $e) {
            echo $e;
        }

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
 
    public function close() {
        //
    }
 
}
 
?>