<?php

include_once "../views/templates/layout_head.php";
?>

<div class='col-sm-12'>

<?php
if ($updated1) {
    echo "<div class='alert alert-info'>Se ha reseteado la contraseña. Por favor <a href=' {$home_url}controllers/login.php'>inicia sesión.</a></div>";
    $updated = false;
} else if ($updated2) {
    echo "<div class='alert alert-danger'>No se ha podido resetar la contraseña.</div>";
} else {

}

echo "</div>";

echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?access_code={$access_code}' method='post'>
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Contraseña</td>
            <td><input type='password' name='password'  pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}' title='Mínimo 6 caracteres, con 1 número, 1 letra mayúscula y 1 minúscula' class='form-control' required></td>
        </tr>
        <tr>
            <td></td>
            <td><button type='submit' class='btn btn-primary'>Resetear contraseña</button></td>
        </tr>
    </table>
</form>";


include_once "../views/templates/layout_foot.php";
?>