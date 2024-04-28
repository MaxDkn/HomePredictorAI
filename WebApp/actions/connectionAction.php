<?php
require('database.php');
require('includes/functions.php');

if ($_SESSION['auth'] == true) {
    header('Location: index.php');
}

//Vérifie si le formulaire est envoyé
if (isset($_POST['submit'])){
    
    //Vérifie les entrées ne sont pas vides            
    if (!empty($_POST['firstname'] && !empty($_POST['lastname']) && !empty($_POST['password']))){
        
        //Récupère les entrées pour mettre dans des variables
        $lastname = upperCase(htmlspecialchars($_POST['lastname']));
        $firstname = titleCase(htmlspecialchars($_POST['firstname']));
        $datetime = date('Y/m/d H:i');
        $password = $_POST['password'];
        //Récupère les utilisateurs avec le même nom et prénom
        $checkUserAlreadyExists = $bdd->prepare('SELECT ID FROM Users WHERE Firstname=? AND LastName=?');
        $checkUserAlreadyExists->execute(array($firstname, $lastname));
        //Vérifie que aucun n'existe
        if ($checkUserAlreadyExists->rowCount() == 0) {
            //Insert dans la bdd l'utilisateur
        
            $insertUserOnWebsite = $bdd->prepare('INSERT INTO Users(FirstConnection, LastConnection, FirstName, LastName, Password) VALUES(?, ?, ?, ?, ?)');
            $insertUserOnWebsite->execute(array($datetime, $datetime, $firstname, $lastname, $password));
            //Récupère les informations de l'utilisateur
            $getInfosOfThisUserReq = $bdd->prepare('SELECT * FROM Users WHERE Firstname=? AND LastName=?');
            $getInfosOfThisUserReq->execute(array($firstname, $lastname));
            $UserInformation = $getInfosOfThisUserReq->fetch();
            $_SESSION['ID'] = $UserInformation['ID'];
            $_SESSION['FirstName'] = $UserInformation['FirstName'];
            $_SESSION['LastName'] = $lastname;
            $_SESSION['auth'] = true;
            //Renvoie vers la page principale
            header('location: index.php');
            
        } elseif ($checkUserAlreadyExists->rowCount() == 1) {
            $getInfosOfThisUserReq = $bdd->prepare('SELECT * FROM Users WHERE Firstname=? AND LastName=?');
            $getInfosOfThisUserReq->execute(array($firstname, $lastname));
            $userInfos = $getInfosOfThisUserReq->fetch();
            if ($userInfos['Password'] == $password){
                $_SESSION['ID'] = $userInfos['ID'];
                $_SESSION['FirstName'] = $userInfos['FirstName'];
                $_SESSION['LastName'] = $userInfos['LastName'];
                $_SESSION['auth'] = true;
                //Renvoie vers la page principale
                header('location: index.php');
                    
            } else {
                $errorMsg = 'Votre mot de passe est incorrect';
            }
        
        } else {
            $errorMsg = "Trop d'utilisateur avec le meme nom ".$checkUserAlreadyExists->rowCount();
        }
    } else {
        $errorMsg = "Veuillez compléter tous les champs..";
    }
}