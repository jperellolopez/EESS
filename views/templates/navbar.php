<!-- navbar -->
<?php
/**
 * @var string $home_url
 * @var $page_title
 */
?>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <span class="navbar-brand"><i class="fas fa-solid fa-gas-pump"></i> infoGasolineras</span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item <?php echo $page_title == "Mapa de gasolineras" ? "active" : ""; ?>">
                    <a class="nav-link"
                       aria-current="page" href="<?php echo $home_url; ?>controllers/general_map.php">Mapa general</a>
                </li>
                <?php
                // opciones visibles para usuarios registrados
                if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && $_SESSION['access_level'] == 'Usuario') {
                    ?>
                    <li class="nav-item <?php echo $page_title == "Creación de facturas" ? "active" : ""; ?>">
                        <a class="nav-link "
                           aria-current="page" href="<?php echo $home_url; ?>controllers/invoice_map.php">Crear
                            facturas</a>
                    </li>

                    <li class="nav-item <?php echo $page_title == "Consulta de facturas" ? "active" : ""; ?>">
                        <a class="nav-link"
                           aria-current="page" href="<?php echo $home_url; ?>controllers/invoice_list.php">Consultar
                            facturas</a>
                    </li>

                <?php } ?>
            </ul>
            <?php
            // opciones visibles para usuarios registrados
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && $_SESSION['access_level'] == 'Usuario') {
                ?>

                <ul class="nav navbar-nav ms-auto mb-2 mb-lg-0 profile-menu">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdownMenuLink" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            <i class="fas fa-user"></i>
                            <?php echo $_SESSION['firstname']; ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item <?php echo $page_title == "Perfil de usuario" ? "active" : ""; ?>"
                                   href="<?php echo $home_url; ?>controllers/user_profile.php"><i
                                            class="fa-solid fa-gear"></i> Perfil de usuario</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?php echo $home_url; ?>controllers/logout.php"><i
                                            class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión</a></li>
                        </ul>
                    </li>
                </ul>

                <?php
            } // muestra las opciones de login y registro para usuarios no logeados
            else {
                ?>

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 profile-menu">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                            <li <?php echo $page_title == "Login" ? "class='active'" : ""; ?>>
                                <a class="dropdown-item" href="<?php echo $home_url; ?>controllers/login.php"><i
                                            class="fa-solid fa-arrow-right"></i> Iniciar sesión
                                </a>
                            </li>

                            <li <?php echo $page_title == "Register" ? "class='active'" : ""; ?>>
                                <a class="dropdown-item" href="<?php echo $home_url; ?>controllers/register.php"><i
                                            class="fa-solid fa-list-check"></i> Registrar usuario
                                </a>
                            </li>

                        </ul>
                    </li>
                </ul>
                <?php
            }
            ?>
        </div>
    </div>
</nav>
<!-- /navbar -->

