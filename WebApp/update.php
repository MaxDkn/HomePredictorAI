<?php
require('actions/securityAction.php');
require('actions/securityUpdate.php');

include('includes/functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $request =  $bdd->prepare('SELECT * FROM HomeInformations WHERE HouseID=?');
    $request->execute(array(intval($_GET['id'])));
    $homeInformations = $request->fetch();
    
    // Attribuer les variables
    $homeID = $homeInformations['HouseID'];
    $intAddress = extractIntAddress($homeInformations['HouseAddress']);
    $strAddress = extractStrAddress($homeInformations['HouseAddress']);
    $respond = $homeInformations['Respond'];
    $size = $homeInformations['HouseSize'];
    $security = $homeInformations['SecurityGateOrAlarm'];
    $dog = $homeInformations['Dog'];
    $age = $homeInformations['Age'];
    $gender = $homeInformations['Gender'];
    $price = $homeInformations['Price'];
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['intAddress'].' '.$_POST['address'];
    $respond = $_POST['respond'];
    $size = $_POST['size'];
    $security = $_POST['gate'];
    $dog = $_POST['dog'];
    $age = ($_POST['age'] != null) ? $_POST['age'] : null;

    $gender = $_POST['gender'];
    $price = $_POST['price'];
    try {
        if ($respond=="Yes"){
            $request = $bdd->prepare('UPDATE HomeInformations SET HouseAddress=?, Respond=?, HouseSize=?, SecurityGateOrAlarm=?, Dog=?, Age=?, Gender=?, Price=? WHERE HouseID=?');
            $request->execute(array($address, $respond, $size, $security, $dog, $age, $gender, $price, $_GET['id']));
        } elseif ($respond=="No"){
            $request = $bdd->prepare('UPDATE HomeInformations SET HouseAddress=?, Respond=?, HouseSize=?, SecurityGateOrAlarm=?, Dog=?, Age=?, Gender=?, Price=? WHERE HouseID=?');
            $request->execute(array($address, $respond, $size, $security, $dog, "", "", $price, $_GET['id']));
        } 
        header('Location: index.php');
    } catch(PDOException $e) {
        $errorMsg = "Erreur lors de la mise à jour : " . $e->getMessage();
    }
    
}

?>
<!DOCTYPE html>
<html lang='fr'>
    <?php include 'includes/head.php';?>
    <body>
        <?php include 'includes/navbar.php';?>
        <br>
        <form class="container" method="POST">
            <?php if (isset($errorMsg)){echo '<h3>'.$errorMsg.'</h3>';}?>
            <label class="form-label">Adresse de la maison</label>
            <div class="row mb-3">
                <div class="col-3">
                    <input type="number" class="form-control" placeholder="<?php echo $intAddress;?>" value="<?php echo $intAddress;?>" name="intAddress" required>
                </div>
                <div class="col-4">
                    <span class="input-group-text">Rue de le/la</span>
                </div>
                <div class="col-5">
                    <input type="text" class="form-control" placeholder="<?php echo $strAddress;?>" value="<?php echo $strAddress;?>" name="address" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Taille de la maison</label>
                <select class="form-select" name='size' required>
                    <option value='Small' <?php if ($size == 'Small') {echo "selected";}?>>Petite</option>
                    <option value='Medium' <?php if ($size == 'Medium') {echo "selected";}?>>Moyenne</option>
                    <option value='Big' <?php if ($size == 'Big') {echo "selected";}?>>Grande</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Gros portail de sécurité ou présence d'alarme ?</label>
                <select class="form-select" name='gate' required>
                    <option value='Yes' <?php if ($security == 'Yes') {echo "selected";}?>>Oui</option>
                    <option value='No' <?php if ($security == 'No') {echo "selected";}?>
                    >Non</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Une personne a-t'elle répondu ?</label>
                <br>
                <div class="form-check form-check-inline col-4">
                    <input class="form-check-input" type="radio" name="respond" id="inlineRadio1" value="Yes" required <?php if ($respond=='Yes') {echo "checked";}?>>
                    <label class="form-check-label">Oui</label>
                </div>
                <div class="form-check form-check-inline col-4">
                    <input class="form-check-input" type="radio" name="respond" id="inlineRadio2" value="No" required <?php if ($respond=='No') {echo "checked";}?>>
                    <label class="form-check-label">Non</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Présence de chien</label>
                <select class="form-select" name='dog' required>
                    <option value="Unknown" <?php if ($dog=="Unknown") {echo "selected";}?>>Ne sais pas</option>
                    <option value='Small' <?php if ($dog=="Small") {echo "selected";}?>>Petit</option>
                    <option value='Large' <?php if ($dog=="Large") {echo "selected";}?>>Gros</option>
                    <option value='No' <?php if ($dog=="No") {echo "selected";}?>>Non</option>
                </select>
            </div>
            <div class='row mb-3'>
                <div class="col-8">
                        <label class="form-label">Âge de la personne</label>
                        <select class="form-select" id="ageSelect" name='age'>
                            <option <?php if ($age=="") {echo "selected";}?> disabled value="">Choose...</option>
                            
                            <option <?php if ($age=="Young") {echo "selected";}?> value='Young'>Jeune parent ou moins</option>
                            <option <?php if ($age=="Middle-aged") {echo "selected";}?> value='Middle-aged'>Entre 40 et 55 ans</option>
                            <option <?php if ($age=="Elderly") {echo "selected";}?> value='Elderly'>Âgée (plus de 55 ans)</option>
                            
                        </select>
                  </div>
                  <div class="col-4">
                        <label class="form-label">Genre</label>
                        <select class="form-select" id="genderSelect" name='gender'>
                            <option <?php if ($gender=="") {echo "selected";}?> disabled value="">Choose...</option>
                            <option value='Male' <?php if ($gender=="Male") {echo "selected";}?>>Homme</option>
                            <option value='Female' <?php if ($gender=="Female") {echo "selected";}?>>Femme</option>
                        </select>
                  </div>
            </div>
            <label class="form-label">Prix</label>
            <div class="row mb-3">
                <div class="col-4">
                    <input type="number" class="form-control" placeholder="<?php echo $price;?>" value="<?php echo $price;?>" name='price' required>
                </div>
                <div class="col-8">
                    <span class="input-group-text">€ (arrondi à l'unité près)</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="submit">Modifier</button>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-grid">
                        <a class="btn btn-danger" href="delete.php?id=<?php echo $homeID;?>">Supprimer</a>
                    </div>
                </div>
            </div>
        </form>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
            var radioYes = document.getElementById('inlineRadio1');
            var radioNo = document.getElementById('inlineRadio2');
            var ageInput = document.getElementById('ageSelect');
            var genderInput = document.getElementById('genderSelect');
        
            function toggleRespondValidation() {
                if (radioYes.checked) {
                    ageInput.setAttribute('required', '');
                    genderInput.setAttribute('required', '');
                    ageInput.removeAttribute('disabled');
                    genderInput.removeAttribute('disabled');
                } else if (radioNo.checked) {
                    ageInput.setAttribute('disabled', '');
                    genderInput.setAttribute('disabled', '');
                    ageInput.removeAttribute('required');
                    genderInput.removeAttribute('required');
                }
            }
            toggleRespondValidation();
            radioYes.addEventListener('click', toggleRespondValidation);
            radioNo.addEventListener('click', toggleRespondValidation);
            });
    
        </script>
    </body>
</html>