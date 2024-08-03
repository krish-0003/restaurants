<?php
include 'includes/header.php';
include 'includes/navbar.php';

if (!isset($_SESSION['order'])) {
    header('Location: cart.php');
    exit;
}

$order = $_SESSION['order'];
unset($_SESSION['cart']);
unset($_SESSION['order']);
?>

<div class="container">
    <h2 class="thank-you">Thank you for your order!</h2>
    <p>Total: $<?php echo number_format($order['total'], 2); ?></p>

    <h3>Shipping Details</h3>
    <div class="shipping-details">
        <p>Name: <?php echo htmlspecialchars($order['shipping_details']['name']); ?></p>
        <p>Address: <?php echo htmlspecialchars($order['shipping_details']['address']); ?></p>
        <p>City: <?php echo htmlspecialchars($order['shipping_details']['city']); ?></p>
        <p>ZIP: <?php echo htmlspecialchars($order['shipping_details']['zip']); ?></p>
    </div>
</div>


<style>
    .container {
    width: 800px;
    margin: 50px auto;
    padding: 40px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    background-color: white;
    text-align: center;
}

.container h2 {
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: 600;
}

.container p {
    margin-bottom: 10px;
    font-size: 14px;
}
.container h3 {
    margin-bottom: 10px;
    font-size: 18px;
    font-weight: 600;
}

.container .shipping-details {
    margin-bottom: 20px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #f9f9f9;
}

.container .shipping-details p {
    margin-bottom: 10px;
    font-size: 14px;
}
@media (max-width: 1000px) {
    .container {
        width: 90%;
        padding: 20px;
    }

    .container h2 {
        font-size: 20px;
    }

    .container p {
        font-size: 12px;
    }

    .container h3 {
        font-size: 16px;
    }

    .container .shipping-details {
        padding: 10px;
    }
}
/* Add some space between elements */
.container > * {
    margin-bottom: 20px;
}

/* Style the thank you message */
.container .thank-you {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

/* Style the total amount */
.container .total {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

    </style>
<?php
include 'includes/footer.php';
?>