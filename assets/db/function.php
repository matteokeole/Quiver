<?php

class Quiver 
{
    function __construct() {
        $this->bdd = new PDO("mysql:host=mysql-quiver.alwaysdata.net;dbname=quiver_db", "quiver", "pouetpouet123");
		$this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
}



?>