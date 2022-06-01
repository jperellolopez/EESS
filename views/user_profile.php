<?php
/**
* @var string $userinfo
*/

include_once '../views/templates/layout_head.php';

$action = isset($_GET['action']) ? $_GET['action'] : "";


echo "<div class='col-md-12'>";

if ($action == 'changed_profile') {
    echo "<div class='alert alert-success'>El perfil ha sido actualizado</div>";
} else if ($action == 'email_unavailable') {
    echo "<div class='alert alert-danger'>El email introducido ya está en uso</div>";
} else if ($action == 'wrong_address') {
    echo "<div class='alert alert-danger' role='alert'>Introduzca una dirección válida</div>";
}

?>

<h2>Datos actuales</h2>
<br>

<?php  echo 'Miembro desde: ' . date('d/m/Y', strtotime($userinfo['created']))?>
<br>
<br>
<table class='table table-responsive'>

    <tr>
        <td class='width-30-percent'>Nombre</td>
        <td><?php  echo $userinfo['firstname'] ?></td>

    </tr>

    <tr>
        <td>Apellidos</td>
        <td><?php  echo $userinfo['lastname'] ?></td>
    </tr>

    <tr>
        <td>Teléfono de contacto</td>
        <td><?php  echo $userinfo['contact_number'] ?></td>
    </tr>

    <tr>
        <td>Dirección</td>
        <td><?php  echo $userinfo['address'] ?></td>
    </tr>

    <tr>
        <td>Código postal</td>
        <td><?php  echo $userinfo['postal_code'] ?></td>
    </tr>

    <tr>
        <td>Email</td>
        <td><?php  echo $userinfo['email'] ?></td>
    </tr>

    <tr>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td style="display: inline">
            <form style="display: inline" method="post" action="../controllers/user_profile.php">
                <br>
                <input type="submit" name="editoption" value="Editar datos" class="btn btn-primary">
            </form>
            <form style="display: inline" method="post" action="../controllers/user_profile.php">
                <input type="submit" name="resetpassword" value="Cambiar contraseña" class="btn btn-primary ">
            </form>
        </td>
    </tr>
</table>

<?php

if (isset($_POST['editoption'])) { ?>

    <form action='../controllers/user_profile.php' method='post' id='register'>

        <h2>Nuevos datos</h2>
        <br>

        <table class='table table-responsive'>

            <tr>
                <td class='width-30-percent'><label for="firstname">Nuevo nombre</label></td>
                <td><input id="firstname" type='text' pattern="[a-zA-Z]+([\s][a-zA-Z]+)*" minlength="2" maxlength="32" name='firstname' class='form-control' required value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname'], ENT_QUOTES) : "{$userinfo['firstname']}";  ?>" /></td>
            </tr>

            <tr>
                <td><label for="lastname">Nuevos apellidos</label></td>
                <td><input id="lastname" type='text' pattern="[a-zA-Z]+([\s][a-zA-Z]+)*" minlength="3" maxlength="64"   name='lastname' class='form-control' required value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname'], ENT_QUOTES) : "{$userinfo['lastname']}";  ?>" /></td>
            </tr>

            <tr>
                <td><label for="contact_number">Nuevo número de teléfono</label</td>
                <td><input id="contact_number" type='text' pattern="(\+34|0034|34)?[ -]*([0-9][ -]*){9}" title="un número de 9 cifras, con o sin prefijo español" name='contact_number' class='form-control' required value="<?php echo isset($_POST['contact_number']) ? htmlspecialchars($_POST['contact_number'], ENT_QUOTES) : "{$userinfo['contact_number']}";  ?>" /></td>
            </tr>

            <tr>
                <td><label for="address">Nueva dirección</label></td>
                <td><textarea id="address" name='address' class='form-control' minlength="6" maxlength="256" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address'], ENT_QUOTES) : "{$userinfo['address']}";  ?></textarea></td>
            </tr>

            <tr>
                <td><label for="postal_code">Nuevo código postal</label></td>
                <td><input type='text' id='postal_code' pattern="^(?:0[1-9]|[1-4]\d|5[0-2])\d{3}$" title="un número entre 01000 y 05299" name='postal_code' class='form-control' required value="<?php echo isset($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code'], ENT_QUOTES) : "{$userinfo['postal_code']}";  ?>" /></td>
            </tr>

            <tr>
                <td> <label for="email">Nuevo email</label></td>
                <td><input type='email' name='email'  id="email" class='form-control' maxlength="64"  required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : "{$userinfo['email']}";  ?>" /></td>
            </tr>

            <tr>
                <td>
                    <button type="submit" name="edituserdata" class="btn btn-primary">
                        <span class="glyphicon glyphicon-plus"> </span> Cambiar datos
                    </button>
                </td>
            </tr>
        </table>
    </form>

<?php } ?>

<?php
include '../views/templates/layout_foot.php';
?>