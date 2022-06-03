<?php
include_once "../config/core.php";
$page_title = "Restablecer contraseña";
include_once "../controllers/login_check.php";
include_once "../config/database.php";
include_once '../models/user.php';
include_once "../libs/php/utils.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$utils = new Utils();

if($_POST){

    // consulta si el usuario está en la bd
    $user->email=$_POST['email'];

    if($user->emailExists()){

        // genera y actualiza el código de acceso
        $access_code=$utils->getToken();

        $user->access_code=$access_code;
        if($user->updateAccessCode()){

            // envia un link de reseteo con el código
            $body="Hola.<br /><br />";
            $body.="Por favor, pulsa en el siguiente link para resetear tu contraseña: {$home_url}controllers/reset_password.php/?access_code={$access_code}";
            $subject="Restablecimiento de contraseña";
            $send_to_email=$_POST['email'];

            if($utils->sendEmailViaPhpMail($send_to_email, $subject, $body)){
                header("Location: {$home_url}controllers/forgot_password.php?action=reset_link_sent");
            }

            // si no se puede enviar el email
            else{
                header("Location: {$home_url}controllers/forgot_password.php?action=reset_link_error");
                 }
        }

        // si no se puede actualizar el código de acceso
        else{
            header("Location: {$home_url}controllers/forgot_password.php?action=access_code_not_updated");

        }

    }

    // si el email no existe
    else{
        header("Location: {$home_url}controllers/forgot_password.php?action=email_not_found");
    }

}

include_once "../views/forgot_password.php";
