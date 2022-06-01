<?php

include_once "../views/templates/layout_head.php";

$action = isset($_GET['action']) ? $_GET['action'] : "";

echo "<div class='col-sm-12'>";

if ($action == "reset_link_sent") {
    echo "<div class='alert alert-info'> Se ha enviado a tu dirección de email un mensaje con un enlace para restablecer tu contraseña</div>";
} else if ($action == "reset_link_error") {
    echo "<div class='alert alert-danger'>No se ha podido enviar el enlace de reseteo</div>";
} else if ($action == "access_code_not_updated") {
    echo "<div class='alert alert-danger'>No se ha podido actualizar el código de acceso</div>";
} else if ($action == "email_not_found") {
    echo "<div class='alert alert-danger'>No se puede encontrar la dirección de email indicada</div>";
}

echo "</div>";
echo "<div class='col-md-4'></div>";
echo "<div class='col-md-4'>";

echo "<div class='account-wall'>
        <div id='my-tab-content' class='tab-content'>
            <div class='tab-pane active' id='login'>
                <img class='profile-img' src='../images/login-icon.png'>
                <form class='form-signin' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                    <input type='email' name='email' class='form-control' placeholder='Tu email' required autofocus>
                    <input type='submit' class='btn btn-lg btn-primary btn-block' value='Enviar correo de restablecimiento' style='margin-top:1em;' />
                </form>
            </div>
        </div>
    </div>";

echo "</div>";
echo "<div class='col-md-4'></div>";

include_once "../views/templates/layout_foot.php";
?>