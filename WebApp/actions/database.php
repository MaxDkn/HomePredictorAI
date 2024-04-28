<?php

$username_dbb = 'id22021870_test';
$password_dbb = '1Test!Test';

try{
    $bdd = new PDO('mysql:host=localhost;dbname=id22021870_gavottesform;charset=utf8;', $username_dbb, $password_dbb);
    
} catch (Exception $e){
    die('Un erreur a été trouvée : '. $e->getMessage());
}
date_default_timezone_set('Europe/Paris');
session_start();