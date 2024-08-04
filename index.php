<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/config.php';


// Redirect to login page if username is not set
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Add product to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Update quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add new item to cart
        $_SESSION['cart'][$product_id] = [
            'name' => $_POST['product_name'],
            'price' => $_POST['price'],
            'quantity' => $quantity
        ];
    }
}
?>
<div class="offer-banner">
    <h3>New Customer Special: Get 20% off your first order! Use code: NEWBIE20</h3>
</div>

<h2>Special Offers</h2>
<div class="items-container special-offers">
    <?php
    // Fetch the first 4 products
    $query = "SELECT p.item_id, p.name, p.description, p.price, c.name AS category_name, i.image_url 
              FROM MenuItems p
              JOIN Categories c ON p.category_id = c.category_id
              LEFT JOIN Images i ON p.item_id = i.item_id
              GROUP BY p.item_id
              LIMIT 4";

    $result = $mysqli->query($query);

    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
            ?>
            <div class="product-card">
                <div class="product-card__image">
                <div class="special-offer-badge">Special Offer</div>

                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>"
                        alt="<?php echo htmlspecialchars($row['name']); ?>">
                </div>
                <div class="product-card__info">
                    <h2 class="product-card__title"><?php echo htmlspecialchars($row['name']); ?></h2>
                    <p class="product-card__description"><?php echo htmlspecialchars($row['description']); ?></p>
                    <div class="product-card__price-row">

                        <span class="product-card__price">$<?php echo number_format($row['price'], 2); ?></span>
                        <form action="view_product.php" method="get" style="margin: 0;">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['item_id']); ?>">
                            <button class="product-card__btn" type="submit">View</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile;
    endif;
    ?>
</div>
<h2>All Products</h2>
<div class="items-container all-products">
    <?php
    // Fetch the rest of the products
    $query = "SELECT p.item_id, p.name, p.description, p.price, c.name AS category_name, i.image_url 
              FROM MenuItems p
              JOIN Categories c ON p.category_id = c.category_id
              LEFT JOIN Images i ON p.item_id = i.item_id
              GROUP BY p.item_id
              LIMIT 4, 100"; // Start after the first 4, a

    $result = $mysqli->query($query);

    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
            ?>
            <div class="product-card">
                <div class="product-card__image">
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>"
                        alt="<?php echo htmlspecialchars($row['name']); ?>">
                </div>
                <div class="product-card__info">
                    <h2 class="product-card__title"><?php echo htmlspecialchars($row['name']); ?></h2>
                    <p class="product-card__description"><?php echo htmlspecialchars($row['description']); ?></p>
                    <div class="product-card__price-row">
                        <span class="product-card__price">$<?php echo number_format($row['price'], 2); ?></span>
                        <form action="view_product.php" method="get" style="margin: 0;">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['item_id']); ?>">
                            <button class="product-card__btn" type="submit">View</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile;
    else:
        echo "<p>No products found.</p>";
    endif;

    $mysqli->close();
    ?>
</div>
<style>
.offer-banner {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px;
    margin: 0 auto 20px;  /* Changed: Added auto margin for left and right */
    border-radius: 5px;
    width: 50%;
    max-width: 800px;  /* Added: Set a maximum width for larger screens */
}

.offer-banner h3 {
    margin: 0;
    font-size: 18px;
}
@media (max-width: 768px) {
    .offer-banner {
        width: 80%;  /* Added: Increase width on smaller screens for better readability */
    }
    
    .offer-banner h3 {
        font-size: 16px;
    }
}



.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.special-offer-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #ff6b6b;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: bold;
    z-index: 1;
    transition: transform 0.3s ease; /* Add transition to match the card */
}


@media (max-width: 768px) {
    .special-offer-badge {
        font-size: 12px;
        padding: 3px 8px;
    }
}



</style>
</div>


<?php include 'includes/footer.php'; ?>