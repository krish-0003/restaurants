<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/config.php';  // Ensure this file has the database connection settings

// Create database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch all orders and their items
$sql = "SELECT u.username, o.order_id, o.order_timestamp, o.total, 
               mi.name as item_name, oi.quantity, oi.price 
        FROM Orders o
        INNER JOIN Users u ON o.user_id = u.user_id
        INNER JOIN OrderItems oi ON o.order_id = oi.order_id
        INNER JOIN MenuItems mi ON oi.item_id = mi.item_id
        ORDER BY u.username, o.order_timestamp DESC, oi.order_item_id ASC";
$result = $conn->query($sql);

// Prepare to group orders by users and then by orders
$ordersByUser = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ordersByUser[$row['username']][$row['order_id']]['details'][] = [
            'item_name' => $row['item_name'],
            'quantity' => $row['quantity'],
            'price' => $row['price'],
            'total_item_price' => $row['quantity'] * $row['price']
        ];
        $ordersByUser[$row['username']][$row['order_id']]['order_timestamp'] = $row['order_timestamp'];
        $ordersByUser[$row['username']][$row['order_id']]['total'] = $row['total'];
    }
}
?>
<div style="width: 80%; margin: 20px auto;">
    <?php
    foreach ($ordersByUser as $username => $orders) {
        echo "<h2>Orders for " . htmlspecialchars($username) . "</h2>";
        foreach ($orders as $order_id => $order) {
            echo "<h3>Order ID: $order_id | Date: " . $order['order_timestamp'] . " | Total: $" . number_format($order['total'], 2) . "</h3>";
            echo "<table border='1' cellpadding='10'>";
            echo "<tr class='mobile-disable'>
                    <th >Item Name</th>
                    <th >Quantity</th>
                    <th >Price per Item</th>
                    <th >Total Price</th>
                  </tr>";
                  foreach ($order['details'] as $item) {
                    echo "<tr >
                            <td data-label='Item Name'>" . htmlspecialchars($item['item_name']) . "</td>
                            <td data-label='Quantity'>" . $item['quantity'] . "</td>
                            <td data-label='Price per Item'>$" . number_format($item['price'], 2) . "</td>
                            <td data-label='Total Price'>$" . number_format($item['total_item_price'], 2) . "</td>
                          </tr>";
                }
                
                
            echo "</table><br>";
        }
    }
    if (empty($ordersByUser)) {
        echo "<p>No orders found.</p>";
    }
    ?>
</div>
<style>
 @media (max-width: 1000px) {
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 10px;
  }
.mobile-disable{
    display: none;
}
  h2 {
    font-size: 1.5em;
    margin-bottom: 10px;
  }

  h3 {
    font-size: 1.2em;
    margin-bottom: 15px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  /* Hide table headers */
  thead {
    display: none;
  }

  tr {
    margin-bottom: 10px;
    display: block;
    border: 1px solid #ddd;
  }

  td {
    display: block;
    text-align: right;
    padding: 10px;
    position: relative;
    border-bottom: 1px solid #ddd;
  }

  td:last-child {
    border-bottom: none;
  }

  td:before {
    content: attr(data-label);
    position: absolute;
    left: 10px;
    width: 45%;
    padding-right: 10px;
    white-space: nowrap;
    text-align: left;
    font-weight: bold;
  }
}


</style>
<?php
$conn->close();
include 'includes/footer.php';
?>