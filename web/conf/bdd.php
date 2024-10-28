<?php

$ip = $_SERVER['SERVER_ADDR'];


define("USER","root");
define("PASSWD",(empty($_ENV['MYSQL_PASSWORD']) ? "<custom password>" : $_ENV['MYSQL_PASSWORD']));
define("SERVER",$ip); // ! Change l'ip donner en fesant docker network inspect my_my_network
define("BASE","bdd_detection_innondation");


    function conec_db(){
        try { $dbb = new PDO('mysql:host='.SERVER.';dbname='.BASE.';charset=utf8', USER, PASSWD); } 
        catch (Exception $e) { printf("Echec : %s\n", $e->getMessage()); }
        return $dbb;
    }

