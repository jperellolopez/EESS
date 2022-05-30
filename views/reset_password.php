<?php
echo "<div class='col-sm-12'>";

if ($updated1) {
    echo "<div class='alert alert-info'>Password was reset. Please <a href='{$home_url}controllers/login.php'>login.</a></div>";
    $updated = false;
} else if ($updated2) {
    echo "<div class='alert alert-danger'>Unable to reset password.</div>";
} else {

}

echo "</div>";

echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?access_code={$access_code}' method='post'>
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Password</td>
            <td><input type='password' name='password'  pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}' title='Mínimo 6 caracteres, con 1 número, 1 letra mayúscula y 1 minúscula' class='form-control' required></td>
        </tr>
        <tr>
            <td></td>
            <td><button type='submit' class='btn btn-primary'>Reset Password</button></td>
        </tr>
    </table>
</form>";