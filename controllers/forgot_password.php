<?php
// core configuration
include_once "../config/core.php";

// set page title
$page_title = "Restablecer contraseña";

// include login checker
include_once "../controllers/login_check.php";

// include classes
include_once "../config/database.php";
include_once '../models/user.php';
include_once "../libs/php/utils.php";

// get database connection
$database = new Database();
$db = $database->getConnection();

// initialize objects
$user = new User($db);
$utils = new Utils();

// if the login form was submitted
if($_POST){

    // check if username and password are in the database
    $user->email=$_POST['email'];

    if($user->emailExists()){

        // update access code for user
        $access_code=$utils->getToken();

        $user->access_code=$access_code;
        if($user->updateAccessCode()){

            // send reset link
            $body="Hi there.<br /><br />";
            $body.="Please click the following link to reset your password: {$home_url}controllers/reset_password.php/?access_code={$access_code}";
            $subject="Reset Password";
            $send_to_email=$_POST['email'];

            if($utils->sendEmailViaPhpMail($send_to_email, $subject, $body)){
                header("Location: {$home_url}controllers/forgot_password.php?action=reset_link_sent");
            }

            // message if unable to send email for password reset link
            else{
                header("Location: {$home_url}controllers/forgot_password.php?action=reset_link_error");
                 }
        }

        // message if unable to update access code
        else{
            header("Location: {$home_url}controllers/forgot_password.php?action=access_code_not_updated");

        }

    }

    // message if email does not exist
    else{
        header("Location: {$home_url}controllers/forgot_password.php?action=email_not_found");
    }

}

include_once "../views/forgot_password.php";
