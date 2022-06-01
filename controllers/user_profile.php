<?php
/**
 * @var string $home_url
 */

include_once '../config/database.php';
include_once '../models/user.php';
include_once "../libs/php/utils.php";

include_once "../config/core.php";

$page_title = "Perfil de usuario";

$require_login = true;
include_once "login_check.php";

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$user->id=$_SESSION['user_id'];
$userinfo = $user->showUserData();

if (isset($_POST['edituserdata'])) {

    $address = trim($_POST['address']);

    // valida el textarea ya que no admite regex
    if ($address !== "" && !empty($address) && strlen($address) > 5) {

    $user->firstname=$_POST['firstname'];
    $user->lastname=$_POST['lastname'];
    $user->contact_number=$_POST['contact_number'];
    $user->address=$_POST['address'];
    $user->postal_code=$_POST['postal_code'];
    $user->email=$_POST['email'];

    if ($user->repeatedEmail()) {
        header("Location: {$home_url}controllers/user_profile.php?action=email_unavailable");
        return;
    }

    if ($user->editProfile()) {
        header("Location: {$home_url}controllers/user_profile.php?action=changed_profile");
    }

        } else {
        header("Location: {$home_url}controllers/user_profile.php?action=wrong_address");
    }

}

if (isset($_POST['resetpassword'])) {
    session_unset();
    session_destroy();
    header("Location: {$home_url}controllers/forgot_password.php");
}

include_once "../views/user_profile.php";

?>