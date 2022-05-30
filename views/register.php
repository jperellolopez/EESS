<?php
//echo "<div class='col-md-12'>";
if ($action == 'email_exists') {
    echo "<div class='alert alert-danger'>";
    echo "The email you specified is already registered. Please try again or <a href='{$home_url}controllers/login.php'>login.</a>";
    echo "</div>";
} else if ($action == 'register_successful') {
    echo "<div class='alert alert-success'>
            Verification link was sent to your email. Click that link to login.
        </div>";
} else if ($action == 'register_partial') {
    echo "<div class='alert alert-danger'>
            User was created but unable to send verification email. Please contact admin.
        </div>";
} else if ($action == 'register_err') {
    echo "<div class='alert alert-danger' role='alert'>Unable to register. Please try again.</div>";
} else if ($action == 'invalid_address') {
    echo "<div class='alert alert-danger' role='alert'>Introduzca una dirección válida</div>";
}


?>

<form action='../controllers/register.php' method='post' id='register'>

    <table class='table table-responsive'>

        <tr>
            <td class='width-30-percent'><label for="firstname">Nombre</label></td>
            <td><input id="firstname" type='text' pattern="[a-zA-Z]+([\s][a-zA-Z]+)*" minlength="2" maxlength="32" name='firstname' class='form-control' required value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname'], ENT_QUOTES) : "";  ?>" /></td>
        </tr>

        <tr>
            <td><label for="lastname">Apellidos</label></td>
            <td><input id="lastname" type='text' pattern="[a-zA-Z]+([\s][a-zA-Z]+)*" minlength="3" maxlength="64"   name='lastname' class='form-control' required value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname'], ENT_QUOTES) : "";  ?>" /></td>
        </tr>

        <tr>
            <td><label for="contact_number">Número de teléfono</label</td>
            <td><input id="contact_number" type='text' pattern="(\+34|0034|34)?[ -]*([0-9][ -]*){9}" title="un número de 9 cifras, con o sin prefijo español" name='contact_number' class='form-control' required value="<?php echo isset($_POST['contact_number']) ? htmlspecialchars($_POST['contact_number'], ENT_QUOTES) : "";  ?>" /></td>
        </tr>

        <tr>
            <td><label for="address">Dirección</label></td>
            <td><textarea id="address" name='address' class='form-control' minlength="6" maxlength="256" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address'], ENT_QUOTES) : "";  ?></textarea></td>
        </tr>

        <tr>
            <td><label for="postal_code">Código postal</label></td>
            <td><input type='text' id='postal_code' pattern="^(?:0[1-9]|[1-4]\d|5[0-2])\d{3}$" title="un número entre 01000 y 05299" name='postal_code' class='form-control' required value="<?php echo isset($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code'], ENT_QUOTES) : "";  ?>" /></td>
        </tr>

        <tr>
            <td> <label for="email">Email</label></td>
            <td><input type='email' name='email'  id="email" class='form-control' maxlength="64"  required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : "";  ?>" /></td>
        </tr>

        <tr>
            <td><label for="passwordInput">Contraseña</label> </td>
            <td><input type='password' pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Mínimo 6 caracteres, 1 número, con 1 letra mayúscula y 1 minúscula" name='password' class='form-control' required id='passwordInput'></td>
        </tr>

<tr>
    <td></td>
    <td>
        <button type="submit" name="submit" id="submit" class="btn btn-primary">
            <span class="glyphicon glyphicon-plus"></span> Registrar usuario
        </button>
    </td>
</tr>

</table>
</form>
<?php

echo "</div>";

// include page footer HTML
include_once "../views/templates/layout_foot.php";
?>