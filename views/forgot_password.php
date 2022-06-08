<?php

include_once "../views/templates/layout_head.php";

$action = isset($_GET['action']) ? $_GET['action'] : ""; ?>

    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-secondary text-white" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">

                        <div class="mb-md-2 mt-md-4 pb-3">

                            <img class='key-img mb-2' alt="key-logo" src='../images/key-icon.png'>

                            <h2 class="fw-bold mb-4"><?php echo $page_title ?></h2>

                            <form class='form-signin' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method='post'>
                                <div class="form-outline form-white mb-4 form-floating">
                                    <input type="email" maxlength="64" name="email" id="typeEmail" class="form-control form-control-lg" />
                                    <label  class="text-dark" for="typeEmail">Email</label>
                                </div>

                                <button class="btn btn-outline-light btn-lg px-5" type="submit">Enviar correo de restablecimiento</button>
                            </form>
                        </div>
                        <?php
                        if ($action == "reset_link_sent") {
                        echo "<div class='alert alert-success'> Se ha enviado a tu dirección de email un mensaje con un enlace para restablecer tu contraseña</div>";
                        } else if ($action == "reset_link_error") {
                        echo "<div class='alert alert-danger'>No se ha podido enviar el enlace de reseteo</div>";
                        } else if ($action == "access_code_not_updated") {
                        echo "<div class='alert alert-danger'>No se ha podido actualizar el código de acceso</div>";
                        } else if ($action == "email_not_found") {
                        echo "<div class='alert alert-danger'>No se puede encontrar la dirección de email indicada</div>";
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