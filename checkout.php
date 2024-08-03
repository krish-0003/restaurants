<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/config.php';

// Create database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sample shipping methods
$shipping_methods = [
    'standard' => 5.00,
    'express' => 15.00,
];
// Server-side validation and processing
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate shipping details
    if (empty($_POST['name']) || empty($_POST['address']) || empty($_POST['city']) || empty($_POST['zip'])) {
        $errors['shipping'] = 'All shipping details are required.';
    }

    // Validate payment details
    if (empty($_POST['card_number']) || empty($_POST['expiry']) || empty($_POST['cvv'])) {
        $errors['payment'] = 'All payment details are required.';
    }

    if (empty($errors)) {
         // Calculate total with tax and shipping
    $sub_total = array_sum(array_map(function ($item) {
        return $item['quantity'] * $item['price'];
    }, $_SESSION['cart']));

    $tax = $sub_total * 0.13; // Assuming a 13% tax rate
    $selected_shipping_method = $_POST['shipping_method'];
    $shipping_cost = $shipping_methods[$selected_shipping_method];
    $total = $sub_total + $tax + $shipping_cost;


        // Fetch user_id from the database using the username stored in the session
        $username = $_SESSION['username'];
        $user_query = $conn->prepare("SELECT user_id FROM Users WHERE username = ?");
        $user_query->bind_param("s", $username);
        $user_query->execute();
        $result = $user_query->get_result();
        $user_row = $result->fetch_assoc();
        $user_id = $user_row['user_id'];
        $user_query->close();

        if ($user_id) {
            // Database insertion logic for the Orders table
            $stmt = $conn->prepare("INSERT INTO Orders (user_id, total) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("id", $user_id, $total);
                if ($stmt->execute()) {
                    $order_id = $conn->insert_id; // Get the last inserted order ID

                    // Now insert each item into OrderItems
                    $item_stmt = $conn->prepare("INSERT INTO OrderItems (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)");
                    foreach ($_SESSION['cart'] as $product_id => $details) {
                        $item_stmt->bind_param("iiid", $order_id, $product_id, $details['quantity'], $details['price']);
                        $item_stmt->execute();
                    }
                    $item_stmt->close();

                    $_SESSION['order'] = [
                        'order_id' => $order_id,
                        'shipping_details' => $_POST,
                        'payment_details' => $_POST,
                        'total' => $total,
                    ];

                    header('Location: order_confirmation.php');
                    exit;
                } else {
                    $error_msg = "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error_msg = "Error preparing statement: " . $conn->error;
            }
        } else {
            $error_msg = "User not found. Invalid session.";
        }
    }
}
?>

<script>
   let currentStep = 1;

function validateStep(step) {
    let valid = true;
    const requiredFields = document.querySelectorAll(`#step${step} input[required]`);
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            valid = false;
            field.classList.add('error');
            showError(field, 'This field is required');
        } else {
            field.classList.remove('error');
            clearError(field);
            
            // Additional validation for specific fields
            if (step === 1) {
                if (field.name === 'zip' && !validateZipCode(field.value)) {
                    valid = false;
                    field.classList.add('error');
                    showError(field, 'Please enter a valid 5-digit ZIP code');
                }
            } else if (step === 2) {
                switch(field.name) {
                    case 'card_number':
                        if (!validateCardNumber(field.value)) {
                            valid = false;
                            field.classList.add('error');
                            showError(field, 'Please enter a valid card number');
                        }
                        break;
                    case 'expiry':
                        if (!validateExpiry(field.value)) {
                            valid = false;
                            field.classList.add('error');
                            showError(field, 'Please enter a valid expiry date (MM/YY)');
                        }
                        break;
                    case 'cvv':
                        if (!validateCVV(field.value)) {
                            valid = false;
                            field.classList.add('error');
                            showError(field, 'Please enter a valid 3 or 4 digit CVV');
                        }
                        break;
                }
            }
        }
    });
    return valid;
}

function validateZipCode(zip) {
    return /^\d{5}$/.test(zip);
}

function validateCardNumber(cardNumber) {
    return /^[0-9]{13,19}$/.test(cardNumber.replace(/\s/g, ''));
}

function validateExpiry(expiry) {
    return /^(0[1-9]|1[0-2])\/([0-9]{2})$/.test(expiry);
}

function validateCVV(cvv) {
    return /^[0-9]{3,4}$/.test(cvv);
}

function showError(field, message) {
    let errorElement = field.nextElementSibling;
    if (!errorElement || !errorElement.classList.contains('error-message')) {
        errorElement = document.createElement('span');
        errorElement.classList.add('error-message');
        field.parentNode.insertBefore(errorElement, field.nextSibling);
    }
    errorElement.textContent = message;
}

function clearError(field) {
    const errorElement = field.nextElementSibling;
    if (errorElement && errorElement.classList.contains('error-message')) {
        errorElement.remove();
    }
}

function showStep(step) {
    document.querySelectorAll('.step').forEach(div => div.style.display = 'none');
    document.getElementById(`step${step}`).style.display = 'block';
    currentStep = step;
}

document.addEventListener('DOMContentLoaded', () => {
    showStep(1);

    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', (event) => {
            const step = parseInt(event.target.dataset.step);
            if (validateStep(step)) {
                showStep(step + 1);
            }
        });
    });

    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', (event) => {
            const step = parseInt(event.target.dataset.step);
            showStep(step - 1);
        });
    });

    // Add real-time validation
    document.querySelectorAll('input[required]').forEach(input => {
        input.addEventListener('blur', () => {
            validateStep(currentStep);
        });
    });

    
});


</script>


<div class="container">
    <form action="checkout.php" method="post" >
        <!-- Step 1: Shipping Details -->
        <div id="step1" class="step">
    <h2>Shipping Details</h2>
    <p><input type="text" name="name" placeholder="Full Name" required></p>
    <p><input type="text" name="address" placeholder="Address" required></p>
    <p><input type="text" name="city" placeholder="City" required></p>
    <p><input type="text" name="zip" placeholder="ZIP Code" required pattern="[0-9]{5}"></p>
    <button type="button" class="next-step" data-step="1">Next</button>
</div>

        <!-- Step 2: Payment Information -->
        <div id="step2" class="step">
            <h2>Payment Information</h2>
            <p><input type="text" name="card_number" placeholder="Card Number" required></p>
            <p><input type="text" name="expiry" placeholder="Expiry Date" required></p>
            <p><input type="text" name="cvv" placeholder="CVV" required></p>
            <button type="button" class="prev-step" data-step="2">Previous</button>
            <button type="button" class="next-step" data-step="2">Next</button>
        </div>

        <!-- Step 3: Review Order -->
        <div id="step3" class="step">
            <h2>Review Order</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                <?php
                $sub_total = 0;
                foreach ($_SESSION['cart'] as $product_id => $details) {
                    $name = $details['name'];
                    $quantity = intval($details['quantity']);
                    $price = $details['price'];
                    $total = $price * $quantity;
                    $sub_total += $total;
                    echo "<tr>
                            <td>$name</td>
                            <td>$quantity</td>
                            <td>$" . number_format($price, 2) . "</td>
                            <td>$" . number_format($total, 2) . "</td>
                          </tr>";
                }
                ?>
                <tr>
                    <td colspan="3" style="text-align: right;">Subtotal:</td>
                    <td>$<?php echo number_format($sub_total, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Tax (13%):</td>
                    <td>$<?php echo number_format($sub_total * 0.13, 2); ?></td>
                    </tr>
                    <table>
    <tr>
        <td colspan="3" style="text-align: right;">Shipping:</td>
        <td>
        <select name="shipping_method" id="shipping-method" required>
    <?php
    foreach ($shipping_methods as $method => $cost) {
        echo "<option value='$method'>$method - $" . number_format($cost, 2) . "</option>";
    }
    ?>
</select>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: right;">Total:</td>
        <td>$<span id="total"><?php echo number_format($sub_total * 1.13 + $shipping_methods['standard'], 2); ?></span></td>
    </tr>
</table>
            </table>
         
                <button type="button" class="prev-step" data-step="3" style="margin-top:10px">Previous</button>
            <button type="submit">Order</button>
        </div>

    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const shippingMethodSelect = document.getElementById('shipping-method');
    const totalSpan = document.getElementById('total');

    // Assuming these values are available from your PHP backend
    const subTotal = <?php echo $sub_total; ?>;
    const taxRate = 0.13;
    const shippingMethods = <?php echo json_encode($shipping_methods); ?>;

    function updateTotal() {
        const selectedMethod = shippingMethodSelect.value;
        const selectedShippingCost = shippingMethods[selectedMethod];
        const tax = subTotal * taxRate;
        const total = subTotal + tax + selectedShippingCost;

        totalSpan.textContent = total.toFixed(2);
    }

    // Update total on page load
    updateTotal();

    // Update total when shipping method changes
    shippingMethodSelect.addEventListener('change', updateTotal);
});
</script>
<style>
      .error {
        border: 1px solid red;
    }
    .error-message {
        color: red;
        font-size: 0.8em;
        display: block;
        margin-top: 5px;
    }

    .mobile-order-review{
        display: none;
    }
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

.step {
    margin-bottom: 20px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #f9f9f9;
}

.step h2 {
    margin-bottom: 10px;
    font-size: 18px;
    font-weight: 600;
}

.step p {
    margin-bottom: 10px;
}

.step input[type="text"] {
    width: 90%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.step select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.next-step, .prev-step {
    padding: 10px 20px;
    background-color: #333;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.next-step:hover, .prev-step:hover {
    background-color: #555;
}

button[type="submit"] {
    padding: 10px 20px;
    background-color: #333;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button[type="submit"]:hover {
    background-color: #555;
}
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

th {
    background-color: #333;
}


@media (max-width: 1000px) {
    .container {
        width: 90%;
        padding: 20px;
    }

    .step {
        margin-bottom: 10px;
        padding: 10px;
    }

    .step h2 {
        font-size: 16px;
    }

    .step input[type="text"], .step select {
        width: 90%;
    }

    table {
        font-size: 14px;
    }
}
/* Media query for mobile devices */
@media screen and (max-width: 1000px) {

   
  /* Mobile-friendly layout */
  /* .mobile-order-review {
    display: flex;
    flex-direction: column;
    gap: 15px;
  } */

  .order-item, .order-summary-item {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 5px;
  }

  .item-label {
    font-weight: bold;
  }

  select[name="shipping_method"] {
    width: 100%;
    margin-top: 5px;
    padding: 5px;
  }

  button {
    padding: 10px 20px;
    margin-top: 10px;
    background-color: #333;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  button:hover {
    background-color: #555;
  }

  .prev-step {
    background-color: #333;
  }

  .prev-step:hover {
    background-color: #555;
  }
}





</style>

<?php
include 'includes/footer.php';
?>