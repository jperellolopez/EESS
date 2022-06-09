<?php
/**
 * @var string $home_url
 */

include_once "../config/core.php";

include_once '../config/database.php';
include_once '../models/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$user->access_code=isset($_GET['access_code']) ? $_GET['access_code'] : "";

if(!$user->accessCodeExists()){
    die("ERROR: Código de acceso no encontrado.");
}

else{

    // pasa el status del usuario de 0 (inactivo) a 1 en la bd
    $user->status=1;
    $user->updateStatusByAccessCode();

    header("Location: {$home_url}controllers/login.php?action=email_verified");
}
?>