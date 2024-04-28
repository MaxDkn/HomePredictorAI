<?php
session_start();
include('actions/database.php');
if (!isset($_GET['id']) or $_GET['id']==""){
    header('location: index.php');
} else {
    $currentHouseID = $_GET['id'];
    $getSellerID = $bdd->prepare('SELECT SellerID FROM HomeInformations WHERE HouseID=?');
    $getSellerID->execute(array($currentHouseID));
    if ($getSellerID->fetch()['SellerID'] != $_SESSION['ID']) {
        header('Location: index.php');
    }
}