<?php require('actions/connectionAction.php');?>
<!DOCTYPE html>
<html lang='fr'>
<?php include 'includes/head.php';?>
<body>
    <br><br>
    <form class="container" method="POST">
        <?php include 'actions/errorMessage.php'?>
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" class="form-control" name='lastname'>
        </div>
        <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" class="form-control" name='firstname'>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name='password'>
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
    </form>
</body>
</html>