<?php
// core configuration
include_once "../config/core.php";

// set page title
$page_title = "Register";

include_once "login_check.php";

include_once '../config/database.php';
include_once '../models/user.php';
include_once "../libs/php/utils.php";

if(isset($_POST) && $_POST){

        $database = new Database();
        $db = $database->getConnection();

        $user = new User($db);
        $utils = new Utils();

        $address = trim($_POST['address']);

        // valida el textarea ya que no admite regex
    if ($address !== "" && !empty($address) && strlen($address) > 5) {

        $user->email = $_POST['email'];

        if ($user->emailExists()) {
            header("Location: {$home_url}controllers/register.php?action=email_exists");
        } // crea un usuario nuevo cuando el email no existe
        else {
            $user->firstname = $_POST['firstname'];
            $user->lastname = $_POST['lastname'];
            $user->contact_number = $_POST['contact_number'];
            $user->address = $_POST['address'];
            $user->postal_code = $_POST['postal_code'];
            $user->password = $_POST['password'];
            $user->access_level = 'Usuario';
            $user->status = 0;
            $access_code = $utils->getToken();
            $user->access_code = $access_code;

            // crea usuario, envia correo
            if ($user->create()) {

                $send_to_email = $_POST['email'];
                $body = "Hola, {$send_to_email}.<br /><br />";
                $body .= "Por favor, haz click en el siguiente enlace para verificar tu email e iniciar sesión: {$home_url}controllers/verify.php/?access_code={$access_code}";
                $subject = "Email de verificación";

                if ($utils->sendEmailViaPhpMail($send_to_email, $subject, $body)) {
                    header("Location: {$home_url}controllers/register.php?action=register_successful");
                } else {
                    header("Location: {$home_url}controllers/register.php?action=register_partial");
                }

                $_POST = array();

            } else {
                header("Location: {$home_url}controllers/register.php?action=register_err");
            }

        }
    } else {
        header("Location: {$home_url}controllers/register.php?action=invalid_address");
    }

}

include_once "../views/register.php";

?>
