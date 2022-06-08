<?php
/**
 * @var string $userinfo
 */

include_once '../views/templates/layout_head.php';

$action = isset($_GET['action']) ? $_GET['action'] : ""; ?>

    <hr class="bg-secondary border-2 border-top border-secondary">

    <div class="container-fluid">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 col-md-8 col-lg-6">
                <form action='../controllers/user_profile.php' method='post' id='register'>
                    <div class="bg-secondary-soft px-4 py-5 rounded">
                        <div class="row g-3">
                            <h4 class="mb-4 mt-0 text-center"><?php echo 'Miembro desde: ' . date('d/m/Y', strtotime($userinfo['created'])) ?></h4>
                            <?php
                            if ($action == 'changed_profile') {
                                echo "<div class='alert alert-success'>El perfil ha sido actualizado</div>";
                            } else if ($action == 'email_unavailable') {
                                echo "<div class='alert alert-danger'>El email introducido ya está en uso</div>";
                            } else if ($action == 'wrong_address') {
                                echo "<div class='alert alert-danger' role='alert'>Introduzca una dirección válida</div>";
                            }
                            ?>
                            <div class="col-md-6">
                                <label class="form-label" for="firstname">Nombre</label>
                                <input id="firstname" type='text' pattern="^[a-zA-Z\u00C0-\u017F\s]+$" minlength="2" maxlength="32"
                                       name='firstname' class='form-control' required
                                       value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname'], ENT_QUOTES) : "{$userinfo['firstname']}"; ?>"
                                       disabled/>
                            </div>

                            <div class="col-md-6">
                            <label class="form-label" for="lastname">Apellidos</label>
                            <input id="lastname" type='text' pattern="^[a-zA-Z\u00C0-\u017F\s]+$" minlength="3" maxlength="64"
                                   name='lastname' class='form-control' required
                                   value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname'], ENT_QUOTES) : "{$userinfo['lastname']}"; ?>"
                                   disabled/>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="contact_number">Número de teléfono</label>
                                <input id="contact_number" type='text' pattern="(\+34|0034|34)?[ -]*([0-9][ -]*){9}"
                                       title="un número de 9 cifras, con o sin prefijo español" name='contact_number' class='form-control'
                                       required
                                       value="<?php echo isset($_POST['contact_number']) ? htmlspecialchars($_POST['contact_number'], ENT_QUOTES) : "{$userinfo['contact_number']}"; ?>"
                                       disabled/>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="postal_code">Código postal</label>
                                <input type='text' id='postal_code' pattern="^(?:0[1-9]|[1-4]\d|5[0-2])\d{3}$"
                                       title="un número entre 01000 y 05299" name='postal_code' class='form-control' required
                                       value="<?php echo isset($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code'], ENT_QUOTES) : "{$userinfo['postal_code']}"; ?>"
                                       disabled/>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label" for="address">Dirección</label>
                                <textarea disabled id="address" name='address' class='form-control' minlength="6" maxlength="256"
                                          required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address'], ENT_QUOTES) : "{$userinfo['address']}"; ?></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label" for="email">Email</label>
                                <input type='email' name='email' id="email" class='form-control' maxlength="64" required
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : "{$userinfo['email']}"; ?>"
                                       disabled/>
                            </div>

                            <div class="gap-2 d-md-flex text-center">
                            <input type="button" class="btn btn-secondary btn-lg col-md-6 col-12 mb-3" onclick="enable();" value="Activar edición">
                            <button type="submit" id="edituserdata" name="edituserdata" class="btn btn-success btn-lg col-md-6 col-12 mb-3" disabled>Actualizar perfil</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function enable() {
            document.getElementById("edituserdata").removeAttribute("disabled");
            document.getElementById("address").removeAttribute("disabled");
            let inputs = document.getElementsByTagName("input");
            for (let i = 0; i < inputs.length; i++) {
                inputs[i].removeAttribute("disabled");
            }
        }
    </script>

<?php
include '../views/templates/layout_foot.php';
?>