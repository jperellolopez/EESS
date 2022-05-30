<?php
// core configuration
include_once "../config/core.php";

// set page title
$page_title = "Reset Password";

// include login checker
include_once "login_check.php";

// include classes
include_once "../config/database.php";
include_once "../models/user.php";

// get database connection
$database = new Database();
$db = $database->getConnection();

// initialize objects
$user = new User($db);

$updated1 = false;
$updated2 = false;

// include page header HTML
include_once "../views/templates/layout_head.php";

// get given access code
$access_code=isset($_GET['access_code']) ? $_GET['access_code'] : die("Access code not found.");

// check if access code exists
$user->access_code=$access_code;

if(!$user->accessCodeExists()){
    die('Access code not found.');
}

else{
    // if form was posted

    if($_POST) {

        // set values to object properties
        $user->password = $_POST['password'];

        // reset password
        if ($user->updatePassword()) {
        $updated1 = true;
        $updated2 = false;

        }  else{
           $updated2 = true;
           $updated1 = false;
        }
    }

 include_once "../views/reset_password.php";
}

// include page footer HTML
include_once "../views/templates/layout_foot.php";
?>