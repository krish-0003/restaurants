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
        <ul class="nav-links left">
            <li><a href="/restaurants/index.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart <span class="cart-count"><?php echo $cart_count ?></span></a></li>
        </ul>
        <ul class="nav-links right">
            <?php if (isset($_SESSION['username'])) : ?>
                <li class="welcome-message no-hover">Welcome, <?= htmlspecialchars($_SESSION['username']); ?></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            <?php else : ?>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
            <?php endif; ?>
        </ul>
        <div class="menu-toggle">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </nav>
</header>


<!-- JavaScript -->
<script>
    document.querySelector('.menu-toggle').addEventListener('click', () => {
        document.querySelector('.nav-links').classList.toggle('active');
    });
</script>