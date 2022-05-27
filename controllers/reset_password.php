<?php
// core configuration
include_once "../config/core.php";

// set page title
$page_title = "Reset Password";

// include login checker
include_once "login_check.php";

// include classes
include_once "../config/database.php";
include_once "../entities/user.php";

// get database connection
$database = new Database();
$db = $database->getConnection();

// initialize objects
$user = new User($db);

// include page header HTML
include_once "../views/layout_head.php";

echo "<div class='col-sm-12'>";

// get given access code
$access_code=isset($_GET['access_code']) ? $_GET['access_code'] : die("Access code not found.");

// check if access code exists
$user->access_code=$access_code;

if(!$user->accessCodeExists()){
    die('Access code not found.');
}

else{
    // if form was posted
    if($_POST){

        // set values to object properties
        $user->password=$_POST['password'];

        // reset password
        if($user->updatePassword()){
            echo "<div class='alert alert-info'>Password was reset. Please <a href='{$home_url}controllers/login.php'>login.</a></div>";
        }

        else{
            echo "<div class='alert alert-danger'>Unable to reset password.</div>";
        }
    }

 include_once "../views/reset_password.php";
}

echo "</div>";

// include page footer HTML
include_once "../views/layout_foot.php";
?>