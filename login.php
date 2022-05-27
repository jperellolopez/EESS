<?php
// user ejemplo: ejemplo@example.com
// Pass ejemplo: darwin12qw!@QW
// core configuration
include_once "config/core.php";

// set page title
$page_title = "Login";

// include login checker
$require_login=false;
include_once "login_check.php";

// default to false
$access_denied=false;

// if the login form was submitted
if($_POST){
    // include classes
    include_once "config/database.php";
    include_once "entities/user.php";

// get database connection
    $database = new Database();
    $db = $database->getConnection();

// initialize objects
    $user = new User($db);

// check if email and password are in the database
    $user->email=$_POST['email'];

// check if email exists, also get user details using this emailExists() method
    $email_exists = $user->emailExists();

// validate login
    if ($email_exists && password_verify($_POST['password'], $user->password) && $user->status==1){

        // if it is, set the session value to true
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user->id;
        $_SESSION['access_level'] = $user->access_level;
        $_SESSION['firstname'] = htmlspecialchars($user->firstname, ENT_QUOTES, 'UTF-8') ;
        $_SESSION['lastname'] = $user->lastname;

            header("Location: {$home_url}invoice_map.php?action=login_success");

    }

// if username does not exist or password is wrong
    else{
        $access_denied=true;
    }
}

// include page header HTML
include_once "layout_head.php";

echo "<div class='col-sm-6 col-md-4 col-md-offset-4'>";

// get 'action' value in url parameter to display corresponding prompt messages
$action=isset($_GET['action']) ? $_GET['action'] : "";

// tell the user he is not yet logged in
if($action =='not_yet_logged_in'){
    echo "<div class='alert alert-danger margin-top-40' role='alert'>Please login.</div>";
}

// tell the user to login
else if($action=='please_login'){
    echo "<div class='alert alert-info'>
        <strong>Please login to access that page.</strong>
    </div>";
}

// tell the user email is verified
else if($action=='email_verified'){
    echo "<div class='alert alert-success'>
        <strong>Your email address have been validated.</strong>
    </div>";
}

// tell the user if access denied
if($access_denied){
    echo "<div class='alert alert-danger margin-top-40' role='alert'>
        Access Denied.<br /><br />
        Your username or password maybe incorrect
    </div>";
}

// actual HTML login form
echo "<div class='account-wall'>";
echo "<div id='my-tab-content' class='tab-content'>";
echo "<div class='tab-pane active' id='login'>";
echo "<img class='profile-img' src='images/login-icon.png'>";
echo "<form class='form-signin' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
echo "<input type='text' name='email' class='form-control' placeholder='Email' required autofocus />";
echo "<input type='password' name='password' class='form-control' placeholder='Password' required />";
echo "<input type='submit' class='btn btn-lg btn-primary btn-block' value='Log In' />";
echo "<div class='margin-1em-zero text-align-center'>
    <a href='{$home_url}forgot_password.php'>Olvidé mi contraseña</a>
</div>";
echo "</form>";
echo "</div>";
echo "</div>";
echo "</div>";

echo "</div>";

// footer HTML and JavaScript codes
include_once "layout_foot.php";
?>