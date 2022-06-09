<?php
/**
 * @var string $home_url
 * @var $access_code
 * @var $page_title
 * @var $updated1
 * @var $updated2
 */

include_once "../views/templates/layout_head.php";
?>

    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-secondary text-white" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">

                        <div class="mb-md-2 mt-md-4 pb-3">

                            <h2 class="fw-bold mb-4"><?php echo $page_title ?></h2>

                            <form class='form-signin'
                                  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?access_code=' . $access_code ?>"
                                  method='post'>
                                <div class="form-outline form-white mb-4 form-floating">
                                    <input type='password' name='password' id='restPass'
                                           pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}'
                                           title='Mínimo 6 caracteres, con 1 número, 1 letra mayúscula y 1 minúscula'
                                           class='form-control' required>
                                    <label class="text-dark" for="restPass">Nueva contraseña</label>
                                </div>

                                <button class="btn btn-outline-light btn-lg px-5" type="submit">Resetear contraseña
                                </button>
                            </form>
                        </div>

                        <?php
                        if ($updated1) {
                            echo "<div class='alert alert-success'>Se ha reseteado la contraseña. Por favor, <a href=' {$home_url}controllers/login.php'>inicia sesión.</a></div>";
                            $updated = false;
                        } else if ($updated2) {
                            echo "<div class='alert alert-danger'>No se ha podido resetear la contraseña.</div>";
                        } else {

                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
include_once "../views/templates/layout_foot.php";
?>