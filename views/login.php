<?php

include_once "../views/templates/layout_head.php";
$action=isset($_GET['action']) ? $_GET['action'] : "";
?>

  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-secondary text-white" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <div class="mb-md-2 mt-md-4 pb-3">

              <img class='login-img mb-2' alt="login-logo" src='../images/login-icon.png'>

              <h2 class="fw-bold mb-4">Inicio de sesión</h2>

                <form class='form-signin' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method='post'>
              <div class="form-outline form-white mb-4 form-floating">
                <input type="email" maxlength="64" name="email" id="typeEmail" class="form-control form-control-lg" />
                <label  class="text-dark" for="typeEmail">Email</label>
              </div>

              <div class="form-outline form-white mb-4 form-floating">
                <input type="password"  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" name="password" id="typePassword" class="form-control form-control-lg" />
                <label class="text-dark" for="typePassword">Contraseña</label>
              </div>

              <p class="small mb-2 pb-lg-2"><a class="text-white" href="<?php echo $home_url?>controllers/forgot_password.php">Olvidé mi contraseña</a></p>

              <button class="btn btn-outline-light btn-lg px-5" type="submit">Iniciar sesión</button>
                </form>
            </div>

                  <?php if($action =='not_yet_logged_in'){ ?>
                  <div class='alert alert-danger mb-3' role='alert'>Por favor, inicie sesión</div>
                  <?php } else if($action=='please_login'){  ?>
                  <div class='alert alert-info mb-3' role='alert'>Inicie sesión para acceder a la página</div>    <?php } else if($action=='email_verified'){ ?>
                  <div class='alert alert-success mb-3' role='alert'>Su email ha sido validado</div>
                <?php }
                  if($access_denied){?>
                   <div class='alert alert-danger mb-3' role='alert'>Acceso denegado<br /><br />
                       Su correo o contraseña podrían ser incorrectos</div>
                  <?php } ?>

            <div>
              <p class="mb-0 mt-3">¿No tienes cuenta? <a href="<?php echo $home_url?>controllers/register.php" class="text-white fw-bold">Regístrate</a>
              </p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
<?php
// footer HTML and JavaScript codes
include_once "../views/templates/layout_foot.php";
?>
