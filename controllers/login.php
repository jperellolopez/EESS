<?php
/**
 * @var string $home_url
 */

include_once "../config/core.php";

$page_title = "Login";

$require_login=false;
include_once "../controllers/login_check.php";

$access_denied=false;

if($_POST){
    include_once "../config/database.php";
    include_once "../models/user.php";

    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);

    $user->email=$_POST['email'];

    $email_exists = $user->emailExists();

    if ($email_exists && password_verify($_POST['password'], $user->password) && $user->status==1){

        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user->id;
        $_SESSION['access_level'] = $user->access_level;
        $_SESSION['firstname'] = htmlspecialchars($user->firstname, ENT_QUOTES, 'UTF-8') ;
        $_SESSION['lastname'] = $user->lastname;
        $_SESSION['email'] = $user->email;

        if($user->access_level=='Usuario') {
            header("Location: {$home_url}controllers/invoice_map.php?action=login_success");
        }

   } else {
        $access_denied=true;
    }

}

require_once "../views/login.php"

?>
