<?php

$ip = $_SERVER['SERVER_ADDR'];


define("USER","pi2");
define("PASSWD","");
define("SERVER",$ip);
define("BASE","bdd_detection_innondation");


    function conec_db(){
        try { $dbb = new PDO('mysql:host='.SERVER.';dbname='.BASE.';charset=utf8', USER, PASSWD); } 
        catch (Exception $e) { printf("Echec : %s\n", $e->getMessage()); }
        return $dbb;
    }

