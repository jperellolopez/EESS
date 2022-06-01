<?php

// include page header HTML
include_once "../views/templates/layout_head.php";

$action = isset($_GET['action']) ? $_GET['action'] : "";

echo "<div class='col-sm-12'>";

if ($action == "reset_link_sent") {
    echo "<div class='alert alert-info'>
                            Password reset link was sent to your email.
                            Click that link to reset your password.
                        </div>";
} else if ($action == "reset_link_error") {
    echo "<div class='alert alert-danger'>ERROR: Unable to send reset link.</div>";
} else if ($action == "access_code_not_updated") {
    echo "<div class='alert alert-danger'>ERROR: Unable to update access code.</div>";
} else if ($action == "email_not_found") {
    echo "<div class='alert alert-danger'>Your email cannot be found.</div>";
}

echo "</div>";
// show reset password HTML form
echo "<div class='col-md-4'></div>";
echo "<div class='col-md-4'>";

echo "<div class='account-wall'>
        <div id='my-tab-content' class='tab-content'>
            <div class='tab-pane active' id='login'>
                <img class='profile-img' src='../images/login-icon.png'>
                <form class='form-signin' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                    <input type='email' name='email' class='form-control' placeholder='Your email' required autofocus>
                    <input type='submit' class='btn btn-lg btn-primary btn-block' value='Send Reset Link' style='margin-top:1em;' />
                </form>
            </div>
        </div>
    </div>";

echo "</div>";
echo "<div class='col-md-4'></div>";

// footer HTML and JavaScript codes
include_once "../views/templates/layout_foot.php";
?>