<?php
if (isset($errorMsg)){
    echo '<div class="alert alert-danger" role="alert">'.$errorMsg.'</div>';
}
if (isset($successMsg)){
    echo '<div class="alert alert-success" role="alert">'.$successMsg.'</div>';
}
if (isset($informationMsg)){
    echo '<div class="alert alert-primary" role="alert">'.$informationMsg.'</div>';
}

?>
        