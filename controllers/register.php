<?php
// core configuration
include_once "../config/core.php";

// set page title
$page_title = "Register";

// include login checker
include_once "login_check.php";

// include classes
include_once '../config/database.php';
include_once '../models/user.php';
include_once "../libs/php/utils.php";

// include page header HTML
include_once "../views/templates/layout_head.php";


$action = isset($_GET['action']) ? $_GET['action'] : "";

// if form was posted
if(isset($_POST) && $_POST){

        // get database connection
        $database = new Database();
        $db = $database->getConnection();

        // initialize objects
        $user = new User($db);
        $utils = new Utils();

        $address = trim($_POST['address']);

        // valida el textarea ya que no admite regex
    if ($address !== "" && !empty($address) && strlen($address) > 5) {

        // set user email to detect if it already exists
        $user->email = $_POST['email'];

        // check if email already exists
        if ($user->emailExists()) {
            header("Location: {$home_url}controllers/register.php?action=email_exists");
        } // crea un usuario nuevo cuando el email no existe
        else {
            // set values to object properties
            $user->firstname = $_POST['firstname'];
            $user->lastname = $_POST['lastname'];
            $user->contact_number = $_POST['contact_number'];
            $user->address = $_POST['address'];
            $user->postal_code = $_POST['postal_code'];
            $user->password = $_POST['password'];
            $user->access_level = 'Usuario';
            $user->status = 0;
            // access code for email verification
            $access_code = $utils->getToken();
            $user->access_code = $access_code;

// create the user
            if ($user->create()) {

                // send confimation email
                $send_to_email = $_POST['email'];
                $body = "Hi {$send_to_email}.<br /><br />";
                $body .= "Please click the following link to verify your email and login: {$home_url}controllers/verify.php/?access_code={$access_code}";
                $subject = "Verification Email";

                if ($utils->sendEmailViaPhpMail($send_to_email, $subject, $body)) {
                    header("Location: {$home_url}controllers/register.php?action=register_successful");
                } else {
                    header("Location: {$home_url}controllers/register.php?action=register_partial");
                }

                // empty posted values
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
