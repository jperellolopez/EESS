<?php
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
include_once "../views/layout_foot.php";
?>