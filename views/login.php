<?php

include_once "../views/templates/layout_head.php";

echo "<div class='col-sm-6 col-md-4 col-md-offset-4'>";

// get 'action' para disponer distintos mensajes
$action=isset($_GET['action']) ? $_GET['action'] : "";


if($action =='not_yet_logged_in'){
    echo "<div class='alert alert-danger margin-top-40' role='alert'>Por favor, inicie sesión</div>";
}

else if($action=='please_login'){
    echo "<div class='alert alert-info'>
        <strong>Inicie sesión para acceder a la página</strong>
    </div>";
}

else if($action=='email_verified'){
    echo "<div class='alert alert-success'>
        <strong>Su dirección de email ha sido validada</strong>
    </div>";
}

if($access_denied){
    echo "<div class='alert alert-danger margin-top-40' role='alert'>
        Acceso dengado<br /><br />
        Su correo o contraseña podrían ser incorrectos
    </div>";
}

echo "<div class='account-wall'>";
echo "<div id='my-tab-content' class='tab-content'>";
echo "<div class='tab-pane active' id='login'>";
echo "<img class='profile-img' src='../images/login-icon.png'>";
echo "<form class='form-signin' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
echo "<input type='text' name='email' class='form-control' placeholder='Email' required  autofocus/>";
echo "<input type='password' name='password' class='form-control' placeholder='Password' required />";
echo "<input type='submit' class='btn btn-lg btn-primary btn-block' value='Iniciar sesión' />";
echo "<div class='margin-1em-zero text-align-center'>
    <a href='{$home_url}controllers/forgot_password.php'>Olvidé mi contraseña</a>
</div>";
echo "</form>";
echo "</div>";
echo "</div>";
echo "</div>";

echo "</div>";

// footer HTML and JavaScript codes
include_once "../views/templates/layout_foot.php";
?>
