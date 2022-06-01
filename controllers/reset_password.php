<?php
include_once "../config/core.php";

$page_title = "Reset Password";

include_once "login_check.php";

include_once "../config/database.php";
include_once "../models/user.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$updated1 = false;
$updated2 = false;

$access_code=isset($_GET['access_code']) ? $_GET['access_code'] : die("Access code not found.");

// comprueba que exista el codigo de acceso
$user->access_code=$access_code;

if(!$user->accessCodeExists()){
    die('Access code not found.');
}

else{

    if($_POST) {

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
?>