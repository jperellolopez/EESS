<?php
// core configuration
include_once "../config/core.php";

// set page title
$page_title = "Forgot Password";

// include login checker
include_once "../controllers/login_check.php";

// include classes
include_once "../config/database.php";
include_once '../entities/user.php';
include_once "../libs/php/utils.php";

// get database connection
$database = new Database();
$db = $database->getConnection();

// initialize objects
$user = new User($db);
$utils = new Utils();

// include page header HTML
include_once "../views/layout_head.php";

// if the login form was submitted
if($_POST){

    echo "<div class='col-sm-12'>";

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
                echo "<div class='alert alert-info'>
                            Password reset link was sent to your email.
                            Click that link to reset your password.
                        </div>";
            }

            // message if unable to send email for password reset link
            else{ echo "<div class='alert alert-danger'>ERROR: Unable to send reset link.</div>"; }
        }

        // message if unable to update access code
        else{ echo "<div class='alert alert-danger'>ERROR: Unable to update access code.</div>"; }

    }

    // message if email does not exist
    else{ echo "<div class='alert alert-danger'>Your email cannot be found.</div>"; }

    echo "</div>";
}

include_once "../views/forgot_password.php";
