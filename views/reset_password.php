<?php
echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?access_code={$access_code}' method='post'>
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Password</td>
            <td><input type='password' name='password' class='form-control' required></td>
        </tr>
        <tr>
            <td></td>
            <td><button type='submit' class='btn btn-primary'>Reset Password</button></td>
        </tr>
    </table>
</form>";