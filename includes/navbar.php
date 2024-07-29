<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<?php
// Get the total number of items in the cart
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $details) {
        $cart_count += $details['quantity'];
    }
}
?>

<!-- Header -->
<header>
<nav>
    <div class="logo">
        <b>Feastly</b>
    </div>
    <div class="nav-links-container">
        <ul class="nav-links">
            <?php
            $current_page = basename($_SERVER['PHP_SELF']);
            if ($current_page !== 'register.php' && $current_page !== 'login.php') :
            ?>
                <li><a href="/restaurants/index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart <span class="cart-count"><?php echo $cart_count ?></span></a></li>
            <?php endif; ?>
            <?php if (isset($_SESSION['username'])) : ?>
                <li class="welcome-message no-hover">Welcome, <?= htmlspecialchars($_SESSION['username']); ?></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            <?php else : ?>
                <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="menu-toggle">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>
</nav>
</header>
<style>
    @media screen and (max-width: 768px) {

        .nav-links-container {
            display: none;
            position: absolute;
            top: 50px;
            left: 0;
            width: 100%;
            background-color: #333;
        }

        .nav-links-container.show {
            display: block;
        }

        .nav-links {
            flex-direction: column;
            padding: 0;
            margin: 0;
        }

        .nav-links li {
            margin: 10px 0;
            text-align: center;
        }

        .menu-toggle {
            display: block;
            cursor: pointer;
        }

        .bar {
            width: 25px;
            height: 3px;
            background-color: white;
            margin: 5px 0;
        }
        nav .welcome-message{
            display: none;
        }
        
    }

    @media screen and (min-width: 769px) {
        .nav-links-container {
            display: flex;
            justify-content: space-between;
        }

        .nav-links {
            display: flex;
        }

        .menu-toggle {
            display: none;
        }
        
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.querySelector('.menu-toggle');
        const navLinksContainer = document.querySelector('.nav-links-container');

        menuToggle.addEventListener('click', function() {
            navLinksContainer.classList.toggle('show');
        });
    });
</script>