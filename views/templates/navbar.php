<!-- navbar -->
<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid">

        <div class="navbar-header">
            <!-- to enable navigation dropdown when viewed in mobile device -->
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Change "Your Site" to your site name -->
            <span class="navbar-brand">InfoGasolineras</span>
        </div>

        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <!-- mapa general -->
                <li <?php echo $page_title=="Mapa de gasolineras" ? "class='active'" : ""; ?>>
                    <a href="<?php echo $home_url; ?>controllers/general_map.php">Mapa general</a>
                </li>
                <?php
                // opciones visibles para usuarios registrados
                if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true && $_SESSION['access_level']=='Usuario'){
                ?>
                <li <?php echo $page_title=="Creación de facturas" ? "class='active'" : ""; ?>>
                    <a href="<?php echo $home_url; ?>controllers/invoice_map.php">Crear facturas</a>
                </li>

                <li <?php echo $page_title=="Consulta de facturas" ? "class='active'" : ""; ?>>
                        <a href="<?php echo $home_url; ?>controllers/invoice_list.php">Consultar facturas</a>
                </li>

                 <li <?php echo $page_title=="Perfil de usuario" ? "class='active'" : ""; ?>>
                 <a href="<?php echo $home_url; ?>controllers/user_profile.php">Perfil de usuario</a>
                 </li>

               <?php } ?>
            </ul>
            <?php
            // opciones visibles para usuarios registrados
            if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true && $_SESSION['access_level']=='Usuario'){
                ?>
                <ul class="nav navbar-nav navbar-right">
                    <li <?php echo $page_title=="Edit Profile" ? "class='active'" : ""; ?>>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            <?php echo $_SESSION['firstname']; ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo $home_url; ?>controllers/logout.php">Cerrar sesión</a></li>
                        </ul>
                    </li>
                </ul>
                <?php
            }

            // muestra las opciones de login y registro para usuarios no logeados
            else{
                ?>
                <ul class="nav navbar-nav navbar-right">
                    <li <?php echo $page_title=="Login" ? "class='active'" : ""; ?>>
                        <a href="<?php echo $home_url; ?>controllers/login.php">
                            <span class="glyphicon glyphicon-log-in"></span> Iniciar sesión
                        </a>
                    </li>

                    <li <?php echo $page_title=="Register" ? "class='active'" : ""; ?>>
                        <a href="<?php echo $home_url; ?>controllers/register.php">
                            <span class="glyphicon glyphicon-check"></span> Registrar usuario
                        </a>
                    </li>
                </ul>
                <?php
            }
            ?>

        </div><!--/.nav-collapse -->

    </div>
</div>
<!-- /navbar -->

