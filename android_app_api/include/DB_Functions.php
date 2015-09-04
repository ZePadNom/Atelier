<?php
 
class DB_Functions {
 
    private $db;

    function __construct() {
        require_once 'DB_Connect.php';
        $this->db = new DB_Connect();
        $this->db->connect();
    }
 
    function __destruct() {
         
    }

    /**
     * Vérification de l'utilisateur
     * @param  [type] $id  [description]
     * @param  [type] $mdp [description]
     * @return [type]      [description]
     */
    function getUsrCon($id, $mdp) {

        $sql = "SELECT `ID`, `UTILISATEUR`.`NOM` AS `NOM`, `PRENOM`, `TEL`, `MAIL`, `UTILISATEUR`.`NUM_CLASSE` AS `CLASSE`, `CLASSE`.`NOM` AS `TYPE_DE_COMPTE`, `ID_CONNEX`, `PASS_CONNEX`
                FROM `UTILISATEUR`
                INNER JOIN `CLASSE`
                        ON `UTILISATEUR`.`NUM_CLASSE` = `CLASSE`.`NUM`;";

        try {
            $res = $pdo->query($sql);
            $table = $res->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $pdoe) {
            echo $e;
        }

        foreach ($table as $row) {

            // Données de connexion présent dans la base
            $id_bdd = $row['ID_CONNEX'];
            $pass_bdd = $row['PASS_CONNEX'];

            if ($id == $id_bdd && password_verify($mdp, $pass_bdd)) {

                return true;

            } else {

                return false;

            }

        }
    }

    function getTickets() {

        $sql = "SELECT  `TICKET`.`ID` AS `ID_TICKET`,\n\t"
             . "`TITRE`,\n\t"
             . "`DESCRIPTION`,\n\t"
             . "`NUM_CATEGORIE`, `NUM_IMPORTANCE`, `NUM_LIEU`, `NUM_STATUT`,`ID_RESPONSABLE`,\n\t"
             . "`CATEGORIE`.`NOM` AS `NOM_CATEGORIE`,\n\t"
             . "`IMPORTANCE`.`NOM` AS `NOM_IMPORTANCE`,\n\t"
             . "`LIEU`.`NOM` AS `NOM_LIEU`,\n\t"
             . "`STATUT`.`NOM` AS `NOM_STATUT`,\n\t"
             . "DATE_FORMAT(`D_OUVERTURE`, '%d/%m/%Y') AS `D_OUVERTURE`,\n\t"
             . "CONCAT(`UTILISATEUR`.`NOM`, \" \", `UTILISATEUR`.`PRENOM`) AS `NOM_RESPONSABLE`\n"
             . "FROM `TICKET`\n"
             . "INNER JOIN `CATEGORIE`\n\t\t"
             . "ON `TICKET`.`NUM_CATEGORIE` = `CATEGORIE`.`NUM`\n"
             . "INNER JOIN `IMPORTANCE`\n\t\t"
             . "ON `TICKET`.`NUM_IMPORTANCE` = `IMPORTANCE`.`NUM`\n"
             . " INNER JOIN `LIEU`\n\t\t"
             . "ON `TICKET`.`NUM_LIEU` = `LIEU`.`NUM`\n"
             . "LEFT OUTER JOIN `UTILISATEUR`\n\t\t"
             . "ON `TICKET`.`ID_RESPONSABLE` = `UTILISATEUR`.`ID`\n"
             . "INNER JOIN `STATUT`\n"
             . "ON `TICKET`.`NUM_STATUT` = `STATUT`.`NUM`\n"
             // . "WHERE `TICKET`.`ID` = ".$id_ticket.";";
     
        try {
            $res = $pdo->query($sql);
            $table = $res->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $pdoe) {
            echo $e;
        }

        $tickets = $table;

        return $tickets;
    }

































    /**
     * Storing new user
     * returns user details
     */
    // public function storeUser($name, $email, $password) {
    //     $uuid = uniqid('', true);
    //     $hash = $this->hashSSHA($password);
    //     $encrypted_password = $hash["encrypted"]; // encrypted password
    //     $salt = $hash["salt"]; // salt
    //     $result = mysql_query("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at) VALUES('$uuid', '$name', '$email', '$encrypted_password', '$salt', NOW())");
    //     // check for successful store
    //     if ($result) {
    //         // get user details 
    //         $uid = mysql_insert_id(); // last inserted id
    //         $result = mysql_query("SELECT * FROM users WHERE uid = $uid");
    //         // return user details
    //         return mysql_fetch_array($result);
    //     } else {
    //         return false;
    //     }
    // }
 
    // /**
    //  * Get user by email and password
    //  */
    // public function getUserByEmailAndPassword($email, $password) {
    //     $result = mysql_query("SELECT * FROM users WHERE email = '$email'") or die(mysql_error());
    //     // check for result 
    //     $no_of_rows = mysql_num_rows($result);
    //     if ($no_of_rows > 0) {
    //         $result = mysql_fetch_array($result);
    //         $salt = $result['salt'];
    //         $encrypted_password = $result['encrypted_password'];
    //         $hash = $this->checkhashSSHA($salt, $password);
    //         // check for password equality
    //         if ($encrypted_password == $hash) {
    //             // user authentication details are correct
    //             return $result;
    //         }
    //     } else {
    //         // user not found
    //         return false;
    //     }
    // }
 
    // /**
    //  * Check user is existed or not
    //  */
    // public function isUserExisted($email) {
    //     $result = mysql_query("SELECT email from users WHERE email = '$email'");
    //     $no_of_rows = mysql_num_rows($result);
    //     if ($no_of_rows > 0) {
    //         // user existed 
    //         return true;
    //     } else {
    //         // user not existed
    //         return false;
    //     }
    // }
 
    // /**
    //  * Encrypting password
    //  * @param password
    //  * returns salt and encrypted password
    //  */
    // public function hashSSHA($password) {
 
    //     $salt = sha1(rand());
    //     $salt = substr($salt, 0, 10);
    //     $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
    //     $hash = array("salt" => $salt, "encrypted" => $encrypted);
    //     return $hash;
    // }
 
    // /**
    //  * Decrypting password
    //  * @param salt, password
    //  * returns hash string
    //  */
    // public function checkhashSSHA($salt, $password) {
 
    //     $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
    //     return $hash;
    // }
 
}
 
?>