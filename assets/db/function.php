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
    public function Create_backup($nickname,$backup,$email)
    {
        $nickname=htmlspecialchars($nickname);
        $insert_backup=$this->bdd->prepare("INSERT INTO `backup` ( `json`, `id_users`) VALUES ( ?, (SELECT id_users FROM users WHERE nickname=? AND email=?))");
        $insert_backup->execute(array($backup,$nickname,$email));
        return  "saved";
    }
    public function get_backup($nickname,$email)
    {
        $email=htmlspecialchars($email);
        $nickname=htmlspecialchars($nickname);
        $backup_query=$this->bdd->query("SELECT json FROM `backup` JOIN users ON backup.id_users=users.id_users WHERE users.nickname = '$nickname' AND users.email='$email'");
        $backup=$backup_query->fetchAll();
        return $backup;
    }

}

$game = new Quiver();
var_dump($game->get_backup('paul','asas'));

//var_dump($game);

?>