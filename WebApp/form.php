<?php
require('actions/securityAction.php');
require('actions/formAction.php');
?>
<!DOCTYPE html>
<html lang='fr'>
    <?php include 'includes/head.php';?>
    <title>Publish</title>
    <body>
        <?php include 'includes/navbar.php';?>
        <br>
        <form class="container" method="POST">
        <?php if (isset($errorMsg)){echo '<h3>'.$errorMsg.'</h3>';}?>
        <label for="exampleInputEmail1" class="form-label">Adresse de la maison</label>
        <div class="row mb-3">
            <div class="col-3">
                <input type="number" class="form-control" placeholder="14" name="intAddress" required>
            </div>
            <div class="col-4">
                <span class="input-group-text">Rue de le/la</span>
            </div>
            <div class="col-5">
                <input type="text" class="form-control" placeholder="Grande Fontenelle" name="address" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Taille de la maison</label>
            <select class="form-select" id="validationCustom04" name='size' required>
                <option selected disabled value="">Choose...</option>
                <option value='Small'>Petite</option>
                <option value='Medium'>Moyenne</option>
                <option value='Big'>Grande</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Gros portail de sécurité ou présence d'alarme ?</label>
            <select class="form-select" id="validationCustom04" name='gate' required>
                <option selected disabled value="">Choose...</option>
                <option value='Yes'>Oui</option>
                <option value='No'>Non</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Une personne a-t'elle répondu ?</label>
            <br>
            <div class="form-check form-check-inline col-4">
                <input class="form-check-input" type="radio" name="respond" id="inlineRadio1" value="Yes" required>
                <label class="form-check-label">Oui</label>
            </div>
            <div class="form-check form-check-inline col-4">
                <input class="form-check-input" type="radio" name="respond" id="inlineRadio2" value="No" required>
                <label class="form-check-label">Non</label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Présence de chien</label>
            <select class="form-select" name='dog' required>
                <option selected value="Unknown">Ne sais pas</option>
                <option value='Small'>Petit</option>
                <option value='Large'>Gros</option>
                <option value='No'>Non</option>
            </select>
        </div>
        <div class='row mb-3'>
            <div class="col-8">
                    <label class="form-label">Âge de la personne</label>
                    <select class="form-select" id="ageSelect" name='age'>
                        <option selected disabled value="">Choose...</option>
                        <option value='Young'>Jeune parent ou moins</option>
                        <option value='Middle-aged'>Entre 40 et 55 ans</option>
                        <option value='Elderly'>Âgée (plus de 55 ans)</option>
                        
                    </select>
              </div>
              <div class="col-4">
                    <label class="form-label">Genre</label>
                    <select class="form-select" id="genderSelect" name='gender'>
                        <option selected disabled value="">Choose...</option>
                        <option value='Male'>Homme</option>
                        <option value='Female'>Femme</option>
                    </select>
              </div>
        </div>
        <label class="form-label">Prix</label>
        <div class="row mb-3">
            <div class="col-4">
                <input type="number" class="form-control" name='price' required>
            </div>
            <div class="col-8">
                <span class="input-group-text">€ (arrondi à l'unité près)</span>
            </div>
        </div>
        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary" name="submit">Ajouter cette maison</button>
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
        radioYes.addEventListener('click', toggleRespondValidation);
        radioNo.addEventListener('click', toggleRespondValidation);
        });

    </script>
    </body>
</html>