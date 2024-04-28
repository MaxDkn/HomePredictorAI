<?php
require('actions/database.php');

if (isset($_GET['id'])) {
    $houseID = intval($_GET['id']);
    
    $getSellerID = $bdd->prepare('SELECT SellerID FROM HomeInformations WHERE HouseID=?');
    $getSellerID->execute(array($houseID));
    if ($getSellerID->fetch()['SellerID'] == $_SESSION['ID']) {
        $deleteHouseByID = $bdd->prepare('DELETE FROM HomeInformations WHERE HouseID=?');
        $deleteHouseByID->execute(array($houseID));
    }
    header('Location: index.php');
}
?>