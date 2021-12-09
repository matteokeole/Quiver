<?php

class Quiver 
{
    private $bdd;
    function __construct() {
        $this->bdd = new PDO("mysql:host=mysql-quiver.alwaysdata.net;dbname=quiver_db", "quiver", "pouetpouet123");
		$this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function inscription($nickname,$email,$password)
    {
        
        $nickname=htmlspecialchars($nickname);
        $password=htmlspecialchars($password);
        $password=md5($password);
        $email=htmlspecialchars($email);
        $users_existe_query=$this->bdd->query("SELECT count(id_users) FROM users where email='$email';");
        $users_existe=$users_existe_query->fetch();
        if ($users_existe['count(id_users)'] == '0') {
            $insert_users=$this->bdd->prepare("INSERT INTO `users` (`nickname`, `password`, `email`) VALUES (?,?,?);");
            $insert_users->execute(array($nickname,$password,$email));
            return "Inscrit avec succes";
        }else{
            return "Vous etes deja inscrit";
        }
    }
    public function conection($email,$password)
    {
        $email=htmlspecialchars($email);
        $password=htmlspecialchars($password);
        $password=md5($password);
        $connection_query=$this->bdd->query("SELECT nickname FROM users where email='$email'and password='$password';");
        $connection=$connection_query->fetch();
        return $connection;

    }
    public function Create_backup($nickname,$backup)
    {
        $nickname=htmlspecialchars($nickname);
        $insert_backup=$this->bdd->prepare("INSERT INTO sauve(json,id_users) VALUES (?,?)");
    }
    
}

$game = new Quiver();
var_dump($game->conection('123@e','123'));

//var_dump($game);

?>