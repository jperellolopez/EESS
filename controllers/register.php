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

echo "<div class='col-md-12'>";

// if form was posted
if($_POST){

    // get database connection
    $database = new Database();
    $db = $database->getConnection();

    // initialize objects
    $user = new User($db);
    $utils = new Utils();

    // set user email to detect if it already exists
    $user->email=$_POST['email'];

    // check if email already exists
    if($user->emailExists()){
        echo "<div class='alert alert-danger'>";
        echo "The email you specified is already registered. Please try again or <a href='{$home_url}controllers/login.php'>login.</a>";
        echo "</div>";
    }

    // crea un usuario nuevo cuando el email no existe
    else{
        // set values to object properties
        $user->firstname=$_POST['firstname'];
        $user->lastname=$_POST['lastname'];
        $user->contact_number=$_POST['contact_number'];
        $user->address=$_POST['address'];
        $user->postal_code=$_POST['postal_code'];
        $user->password=$_POST['password'];
        $user->access_level='Usuario';
        $user->status=0;
        // access code for email verification
        $access_code=$utils->getToken();
        $user->access_code=$access_code;

// create the user
        if($user->create()){

            // send confimation email
            $send_to_email=$_POST['email'];
            $body="Hi {$send_to_email}.<br /><br />";
            $body.="Please click the following link to verify your email and login: {$home_url}controllers/verify.php/?access_code={$access_code}";
            $subject="Verification Email";

            if($utils->sendEmailViaPhpMail($send_to_email, $subject, $body)){
                echo "<div class='alert alert-success'>
            Verification link was sent to your email. Click that link to login.
        </div>";
            }

            else{
                echo "<div class='alert alert-danger'>
            User was created but unable to send verification email. Please contact admin.
        </div>";
            }

            // empty posted values
            $_POST=array();

        }else{
            echo "<div class='alert alert-danger' role='alert'>Unable to register. Please try again.</div>";
        }

    }
}

include_once "../views/register.php";

?>
