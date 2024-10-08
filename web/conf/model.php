<?php
require('bdd.php');
$bdd = conec_db();

function get_data(){
    global $bdd;
    $sql_data = 'SELECT * FROM `data`';
    return $bdd->query($sql_data)->fetchAll(PDO::FETCH_ASSOC);
}

function compt_email($email){
    global $bdd;
    $sql_email = 'UPDATE `compt` SET `email`=":email"';
    $statement = $bdd->prepare($sql_email);
    $statement->bindValue(':email', $email);
    $statement->execute();

}
