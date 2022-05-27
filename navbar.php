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
            <a class="navbar-brand" href="<?php echo $home_url; ?>">InfoGasolineras</a>
        </div>

        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <!-- link to the "Cart" page, highlight if current page is cart.php -->
                <li <?php echo $page_title=="Index" ? "class='active'" : ""; ?>>
                    <a href="<?php echo $home_url; ?>">Home</a>
                </li>
            </ul>

            <?php

            // check if users / customer was logged in
            // if user was logged in, show "Edit Profile", "Orders" and "Logout" options
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
                            <li><a href="<?php echo $home_url; ?>logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
                <?php
            }

            // if user was not logged in, show the "login" and "register" options
            else{
                ?>
                <ul class="nav navbar-nav navbar-right">
                    <li <?php echo $page_title=="Login" ? "class='active'" : ""; ?>>
                        <a href="<?php echo $home_url; ?>login.php">
                            <span class="glyphicon glyphicon-log-in"></span> Log In
                        </a>
                    </li>

                    <li <?php echo $page_title=="Register" ? "class='active'" : ""; ?>>
                        <a href="<?php echo $home_url; ?>register.php">
                            <span class="glyphicon glyphicon-check"></span> Register
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
