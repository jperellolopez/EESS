<?php
/**
* @var string $userinfo
*/
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
                <td class='width-30-percent'>Nuevo nombre</td>
                <td><input type='text' name='firstname' class='form-control'  required value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname'], ENT_QUOTES) : "{$userinfo['firstname']}";  ?>" /></td>
            </tr>

            <tr>
                <td>Nuevos apellidos</td>
                <td><input type='text' name='lastname' class='form-control' required value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname'], ENT_QUOTES) : "{$userinfo['lastname']}";  ?>" /></td>
            </tr>

            <tr>
                <td>Nuevo número de contacto</td>
                <td><input type='text' name='contact_number' minlength="9" class='form-control' required value="<?php echo isset($_POST['contact_number']) ? htmlspecialchars($_POST['contact_number'], ENT_QUOTES) : "{$userinfo['contact_number']}";   ?>" /></td>
            </tr>

            <tr>
                <td>Nueva dirección</td>
                <td><textarea name='address' class='form-control' required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address'], ENT_QUOTES) : "{$userinfo['address']}";  ?></textarea></td>
            </tr>

            <tr>
                <td>Nuevo código postal</td>
                <td><input name='postal_code' pattern="^(?:0[1-9]|[1-4]\d|5[0-2])\d{3}$" class='form-control' required value="<?php echo isset($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code'], ENT_QUOTES) : "{$userinfo['postal_code']}";  ?>" /></td>
            </tr>

            <tr>
                <td>Nuevo email</td>
                <td><input type='email' name='email' class='form-control' required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : "{$userinfo['email']}";  ?>" /></td>
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
// footer HTML and JavaScript codes
include '../views/templates/layout_foot.php';
?>