<?php

require('includes/functions.php');
require('actions/database.php');

$result = $bdd->prepare('SELECT * FROM HomeInformations WHERE SellerID=?');
$result->execute(array($_SESSION['ID']));
// compte le prix total des ventes de gavottes
$total_price = 0;
while ($row = $result->fetch()) {
    $total_price += $row['Price']; 
}
echo '<div class="container">';
if ($result->rowCount() == 0){
    echo "<h1 class='h1'>Bonjour ".$_SESSION['FirstName']." !!</h1>";
    echo "<p class='h4 text-justify'>
<br>Bienvenue sur la page principale de gestion des adresses de GavottesAI !<br>Pour ajouter une adresse, cliquez sur <strong>\"Ajouter une maison\"</strong> en bas de la page ou dans la barre de navigation. Pour <strong>modifier</strong> une adresse, cliquez sur son <strong>numéro, nom de rue, date ou prix</strong> dans le tableau, puis modifiez le formulaire pré-rempli. Pour supprimer une adresse, cliquez sur <strong>\"Supprimer\"</strong> à côté de celle-ci. <br>Vous pouvez revenir sur cette page en cliquant sur le logo <strong>GavottesAI</strong>.<br>Pour toute aide avec votre <strong>mot de passe</strong>, contactez Max ou Nikitas.</p></div>";
    
} else {
    if ($result->rowCount() == 1) {
        echo "<div class='container'>
        <h1 class='h1'>Voici votre seul adresse pour le moment, ".$_SESSION['FirstName'].":</h1>
        ";
    } else {
        echo '<div class="container">
            <h1 class="h1">Voici vos adresses, '.$_SESSION['FirstName'].':</h1>
        ';
    }
    
    echo '<label class="form-label">Montant total des ventes: '.$total_price.'€</label></div><br>';
    echo '<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nom de la rue</th>
            <th scope="col">Date et Heure</th>
            <th scope="col">Prix</th>
        </tr>
    </thead>
    <tbody>';
    $result = $bdd->prepare('SELECT * FROM HomeInformations WHERE SellerID=?');
    $result->execute(array($_SESSION['ID']));
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $houseID = $row['HouseID'];
            $houseIntAddress = extractIntAddress($row['HouseAddress']);
            $houseStrAddress = extractStrAddress($row["HouseAddress"]);
            $dayTime = extractDate($row["DayTime"])." ".extractHour($row["DayTime"]);
            $price = $row['Price'];
            echo '<tr class="align-middle"><th scope="row"><a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hoverlink-underline-dark link-dark" href="update.php?id='.$houseID.'">'.$houseIntAddress."</a></th>";
            echo '<td class="align-middle"><a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hoverlink-underline-dark link-dark" href="update.php?id='.$houseID.'">'.$houseStrAddress."</a></td>";
            echo '<td class="align-middle"><a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hoverlink-underline-dark link-dark" href="update.php?id='.$houseID.'">'.$dayTime."</a></td>";
           
            echo '<td class="align-middle"><a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hoverlink-underline-dark link-dark" href="update.php?id='.$houseID.'">'.$price."€</a></td>";
            echo '</tr>';
        }
    echo '</tbody>
</table></div>';
}