<?php
// login check para usuarios registrados

if(isset($require_login) && $require_login==true){
    // if si se requiere login, redirige a la pág de login
    if(!isset($_SESSION['access_level'])){
        header("Location: {$home_url}controllers/login.php?action=please_login");
    }
}

// si está en la página de login, registro o sign up pero ya está logeado
else if(isset($page_title) && ($page_title=="Login" || $page_title=="Sign Up")){
    // si el usuario no está logeado, redirige al mapa general
    if(isset($_SESSION['access_level']) && $_SESSION['access_level']=="Usuario"){
        header("Location: {$home_url}controllers/index.php?action=already_logged_in");
    }
}

else{

}
?>