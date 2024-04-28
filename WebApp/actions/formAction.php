<?php
session_start();
require('database.php');

if (isset($_POST['submit'])){
    $intAddress = htmlspecialchars($_POST['intAddress']);
    $strAddress = htmlspecialchars($_POST['address']);
    $address = $intAddress.' '.$strAddress;
    $respond = htmlspecialchars($_POST['respond']);
    $size = htmlspecialchars($_POST['size']);
    $gate = htmlspecialchars($_POST['gate']);
    $dog = htmlspecialchars($_POST['dog']);
    $age = htmlspecialchars($_POST['age']);
    $gender = htmlspecialchars($_POST['gender']);
    $price = $_POST['price'];
    
    $datetime = date('Y/m/d H:i');
    $insertUserOnWebsite = $bdd->prepare('INSERT INTO HomeInformations(SellerID, SellerName, HouseAddress, DayTime, Respond, HouseSize, SecurityGateOrAlarm, Dog, Age, Gender, Price) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $sellerName = $_SESSION['LastName'].' '.$_SESSION['FirstName'];
    
    $insertUserOnWebsite->execute(array($_SESSION['ID'], $sellerName, $address, $datetime, $respond, $size, $gate, $dog, $age, $gender, $price));
    $errorMsg = 'La maison a bien été ajouté !';
    header('location: index.php');    

    
}