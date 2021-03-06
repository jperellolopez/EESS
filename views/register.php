<?php
/**
 * @var string $home_url
 * @var $page_title
 */

include_once "../views/templates/layout_head.php";

$action = isset($_GET['action']) ? $_GET['action'] : "";
?>

    <div class="container  py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="card bg-secondary text-white" style="border-radius: 1rem;">
                    <div class="card-body py-5 px-md-5 pb-3">

                        <img class='login-img mb-2' alt="register-logo" src='../images/register-icon.png'>
                        <h2 class="fw-bold mb-4 text-center"><?php echo $page_title ?></h2>
                        <form method="post" id="register" action="../controllers/register.php">

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline form-floating">
                                        <input id="firstname" type='text' pattern="^[a-zA-Z\u00C0-\u017F\s]+$"
                                               minlength="2" maxlength="32"
                                               name='firstname' class='form-control' required
                                               value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname'], ENT_QUOTES) : ""; ?>"/>
                                        <label class="text-dark" for="firstname"> Nombre</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline form-floating">
                                        <input id="lastname" type='text' pattern="^[a-zA-Z\u00C0-\u017F\s]+$"
                                               minlength="3" maxlength="64"
                                               name='lastname' class='form-control' required
                                               value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname'], ENT_QUOTES) : ""; ?>"/>
                                        <label class="text-dark" for="lastname"> Apellidos</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline form-floating">
                                        <input id="contact_number" type='text'
                                               pattern="(\+34|0034|34)?[ -]*([0-9][ -]*){9}"
                                               title="un n??mero de 9 cifras, con o sin prefijo espa??ol"
                                               name='contact_number'
                                               class='form-control' required
                                               value="<?php echo isset($_POST['contact_number']) ? htmlspecialchars($_POST['contact_number'], ENT_QUOTES) : ""; ?>"/>
                                        <label class="text-dark" for="contact_number">N??mero de tel??fono</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline form-floating">
                                        <input type='text' id='postal_code' pattern="^(?:0[1-9]|[1-4]\d|5[0-2])\d{3}$"
                                               title="un n??mero entre 01000 y 05299" name='postal_code'
                                               class='form-control' required
                                               value="<?php echo isset($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code'], ENT_QUOTES) : ""; ?>"/>
                                        <label class="text-dark" for="postal_code">C??digo postal</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-outline mb-4 form-floating">
                                <textarea id="address" name='address' class='form-control' minlength="6" maxlength="256"
                                          required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address'], ENT_QUOTES) : ""; ?></textarea>
                                <label class="text-dark" for="address">Direcci??n</label>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline form-floating">
                                        <input type='email' name='email' id="email" class='form-control' maxlength="64"
                                               required
                                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : ""; ?>"/>
                                        <label class="text-dark" for="email">Email</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline form-floating">
                                        <input type='password' pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                                               title="M??nimo 6 caracteres, 1 n??mero, con 1 letra may??scula y 1 min??scula"
                                               name='password'
                                               class='form-control' required id='passwordInput'>
                                        <label class="text-dark" for="passwordInput">Contrase??a</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Submit button -->
                            <div class="text-center">
                                <button type="submit" name="submit" id="submit"
                                        class="btn btn-outline-light btn-lg px-5">
                                    Registrar usuario
                                </button>
                            </div>
                        </form>

                        <div class="pt-3">
                            <?php
                            if ($action == 'email_exists') {
                                echo "<div class='alert alert-danger'>";
                                echo "El email indicado ya est?? en uso. Int??ntalo de nuevo o <a href='{$home_url}controllers/login.php'>inicia sesi??n.</a>";
                                echo "</div>";
                            } else if ($action == 'register_successful') {
                                echo "<div class='alert alert-success'>
            El email de verificaci??n se ha enviado a la direcci??n indicada. Haz click en ??l para iniciar sesi??n.
        </div>";
                            } else if ($action == 'register_partial') {
                                echo "<div class='alert alert-danger'>
            El usuario se ha creado, pero no se ha podido enviar el email de verificaci??n.
        </div>";
                            } else if ($action == 'register_err') {
                                echo "<div class='alert alert-danger' role='alert'>No se ha podido registrar el registro. Int??ntelo de nuevo.</div>";
                            } else if ($action == 'invalid_address') {
                                echo "<div class='alert alert-danger' role='alert'>Introduzca una direcci??n v??lida (m??n 6 caracteres)</div>";
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
// include page footer HTML
include_once "../views/templates/layout_foot.php";
?>