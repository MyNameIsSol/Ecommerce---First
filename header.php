<nav class="navi">
    <ul class="logo">
        <li>
            <img src="img/icons/Crochet_andHooks.png" alt="Crochet_andHooks" class="image-responsive">
            <!-- <h1>CH</h1>
                        <P>Crochet<span>&</span><span>Hooks</span></P> -->
        </li>
    </ul>
    <ul class="cart-barg">
        <li ><a href="cart.php" class="sup"><span class="fa fa-shopping-cart"></span>
        <?php
                if (isset($_SESSION['cart'])) {
                    $count = count($_SESSION['cart']);
                    echo '<div class="c-barg">' . $count . '</div>';
                } else {
                    echo '<div class="c-barg">0</div>';
                }
                ?>
    </a></li>
    </ul>
    <ul class="nav-menu">
        <li><a href=""><span class="fa fa-bars"></span></a></li>
    </ul>
    <ul class="nav-cancel">
        <li><a href=""><span class="fa fa-times"></span></a></li>
    </ul>
    
    <ul class="nav-center">
        <li><a href="index.php">Products</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
    <ul class="nav-end">
        <?php if (isset($username)) {
            //   echo '<li><span class="user">'.$username.'</span></li>';
            echo '<li><a href="logout.php"><span class="fa fa-sign-out mr-1"></span>'.$username .'</a></li>';
        } elseif (isset($adminname)) {
            echo '<li><a href="dashboard.php">Dashboard</a></li>';
            echo '<li><a href="logout.php"><span class="fa fa-sign-out mr-1"></span>'.$adminname.'</a></li>';
        } else {
            echo '<li><a href="login.php">Login</a></li>';
        }
        ?>
        <li><a href="cart.php" class="sup">Cart<span class="fa fa-shopping-cart"></span>
                <?php
                if (isset($_SESSION['cart'])) {
                    $count = count($_SESSION['cart']);
                    echo '<div class="barg">' . $count . '</div>';
                } else {
                    echo '<div class="barg">0</div>';
                }
                ?>
            </a>
        </li>
    </ul>
</nav>