<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/config.php';

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

// Function to update quantity in the cart
if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    if (isset($_POST['update_action']) && isset($_SESSION['cart'][$productId])) {
        if ($_POST['update_action'] == 'increment') {
            $_SESSION['cart'][$productId]['quantity'] += 1;
        } elseif ($_POST['update_action'] == 'decrement') {
            $_SESSION['cart'][$productId]['quantity'] -= 1;
            if ($_SESSION['cart'][$productId]['quantity'] <= 0) {
                unset($_SESSION['cart'][$productId]); // Remove item if quantity is 0 or less
            }
        }
    }
    header("Location: cart.php");
    exit;
}

// Function to remove item from the cart
if (isset($_POST['remove_product_id'])) {
    $productId = $_POST['remove_product_id'];
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
    header("Location: cart.php");
    exit;
}

// Check if the cart is not empty
if (!empty($_SESSION['cart'])) {
    ?>
    <table style="width: 80%; margin: 20px auto;">
        
        <tr class="heading">
            <th>Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
            <th>Action</th>
        </tr>

        <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $product_id => $details) {
            $name = $details['name'];
            $quantity = intval($details['quantity']);
            $price = $details['price'];
            $subTotal = $price * $quantity;
            $total += $subTotal;
            ?>
            <tr>
            <td data-label="Name"><?php echo htmlspecialchars($name); ?></td>
            <td data-label="Quantity">
            <form action="" method="post" style="display: inline-block;">
                    <button type="submit" name="update_action" value="decrement">-</button>

                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    </form>
                    <?php echo "&nbsp;$quantity&nbsp;" ?>
                    <form action="" method="post" style="display: inline-block;">
                        <button type="submit" name="update_action" value="increment">+</button>

                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    </form>
                </td>
                <td data-label="Price">$<?php echo number_format($price, 2); ?></td>
    <td data-label="Total">$<?php echo number_format($subTotal, 2); ?></td>
    <td data-label="Action">
                    <form action="" method="post">
                        <button type="submit" name="remove_product_id" value="<?php echo $product_id; ?>">Delete</button>
                    </form>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="4" style="text-align: right;">Total:</td>
            <td>$<?php echo number_format($total, 2); ?></td>
        </tr>
    </table>
    <div style="text-align: right; margin: 20px 10%;">
        <a href="checkout.php" class="checkout-button">Proceed to Checkout</a>
    </div>
    
    <?php
} else {
    echo "<p>Your cart is empty.</p>";
}

include 'includes/footer.php';
?>