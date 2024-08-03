<?php

include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/config.php';

// Database connection
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
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

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $_POST['product_name'],
            'price' => $_POST['price'],
            'quantity' => $quantity
        ];
    }
}

// Get product ID from GET request
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id == 0) {
    echo "<p>Invalid product ID.</p>";
} else {
    $stmt = $mysqli->prepare("
        SELECT m.name, m.description, m.price, c.name AS category
        FROM MenuItems m
        JOIN Categories c ON m.category_id = c.category_id
        WHERE m.item_id = ?
    ");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
      <div class="item-details">
    <h1 class="product-title"><?php echo htmlspecialchars($row['name']); ?></h1>
    <p class="product-category">Category: <?php echo htmlspecialchars($row['category']); ?></p>
    
    <div class="product-content">
        <div class="product-gallery">
            <?php
            $stmt_images = $mysqli->prepare("SELECT image_url FROM Images WHERE item_id = ?");
            $stmt_images->bind_param("i", $product_id);
            $stmt_images->execute();
            $result_images = $stmt_images->get_result();

            if ($result_images->num_rows > 0) {
                $images = $result_images->fetch_all(MYSQLI_ASSOC);
                ?>
                <div class="main-image">
                    <img src="<?php echo htmlspecialchars($images[0]['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" id="main-product-image">
                </div>
                <!-- <div class="sub-images">
                    <?php foreach ($images as $image) { ?>
                        <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" onclick="changeMainImage(this.src)">
                    <?php } ?>
                </div> -->
                <div class="sub-images">
    <?php foreach ($images as $image) { ?>
        <div class="sub-image-wrapper">
            <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" onclick="changeMainImage(this.src)">
        </div>
    <?php } ?>
</div>
                <?php
            } else {
                echo "<p>No images available for this product.</p>";
            }
            $stmt_images->close();
            ?>
        </div>
        <div class="product-info">
            <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
            <p class="price">Price: $<?php echo number_format($row['price'], 2); ?></p>
            
            <form class="add-to-cart-form" action="" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($row['name']); ?>">
                <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1">
                <button type="submit" >Add to Cart</button>
            </form>
        </div>
    </div>
</div>
        <script>
        function changeMainImage(src) {
            document.getElementById('main-product-image').src = src;
        }
        document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.add-to-cart-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Get the form data
        var formData = new FormData(this);

        // Send the form data via AJAX
        fetch('', {
            method: 'POST',
            body: formData
        }).then(response => response.text()).then(data => {
            // Update the cart count here
            var quantity = parseInt(document.getElementById('quantity').value);
            var currentCount = parseInt(document.querySelector('.cart-count').textContent);
            var newCount = currentCount + quantity;
            document.querySelector('.cart-count').textContent = newCount;
        }).catch(error => {
            console.error('Error submitting form:', error);
        });
    });
});

        </script>

<style>
.item-details {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.product-title {
    font-size: 28px;
    margin-bottom: 10px;
}

.product-category {
    font-size: 18px;
    color: #666;
    margin-bottom: 20px;
}

.product-content {
    display: flex;
    gap: 40px;
    flex-wrap: wrap;
    justify-content: center;
}

.product-gallery {
    flex: 1;
    max-width: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.main-image {
    width: 100%;
    margin-bottom: 20px;
    height: 400px; /* Set a fixed height for the main image container */
    width: 400px; /* Set a fixed width for the main image container */
    overflow: hidden; /* Hide overflow if image aspect ratio doesn't match */
}

.main-image img {
    width: 100%;
    height: 100%; /* Make the image fill the container */
    object-fit: cover; /* Maintain aspect ratio and cover the container */
}

.sub-images {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
}

.sub-image-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 80px;
    height: 80px;
    overflow: hidden;
}

.sub-image-wrapper img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    cursor: pointer;
    transition: opacity 0.3s ease;
}

.sub-image-wrapper img:hover {
    opacity: 0.7;
}

.product-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.product-info p {
    margin-bottom: 15px;
}

.price {
    font-size: 24px;
    font-weight: bold;
    color: #B12704;
    margin-bottom: 20px;
}

.add-to-cart-form {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
}

.add-to-cart-form input[type="number"] {
    width: 60px;
    padding: 5px;
}

.add-to-cart-form button {
    background-color: #000000;
    border: 1px solid #000000;
    padding: 10px 20px;
    cursor: pointer;
}

/* Responsive adjustments */
@media (max-width: 1000px) {
    .product-content {
        flex-direction: column;
    }

    .product-gallery, .product-info {
        max-width: 100%;
    }

    .sub-images {
        gap: 8px;
    }

    .sub-image-wrapper {
        width: 60px;
        height: 60px;
    }
    
}

@media (max-width: 480px) {
    .sub-images {
        gap: 6px;
    }

    .sub-image-wrapper {
        width: 50px;
        height: 50px;
    }
}

@media (max-width: 320px) {
    .product-title {
        font-size: 24px;
    }

    .product-category {
        font-size: 16px;
    }

    .price {
        font-size: 20px;
    }
}


</style>
        <?php
    } else {
        echo "<p>Product not found.</p>";
    }

    $stmt->close();
}

$mysqli->close();
include 'includes/footer.php';
?>