<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/config.php';

// Check if the user is already logged in, if yes then redirect them to the homepage
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("Location: index.php");
    exit;
}

// Create database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for error message and form data
$username = $password = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Prepare a select statement
    $sql = "SELECT password_hash FROM Users WHERE username = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $username);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();

            // Check if username exists, if yes then verify password
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($password_hash);
                if ($stmt->fetch()) {
                    // Use password_verify to check if the entered password matches the stored hash
                    if (password_verify($password, $password_hash)) {
                        // Password is correct, so start a new session
                        // session_start();

                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["username"] = $username;

                        // Redirect user to home page
                        header("Location: index.php");
                        exit();
                    } else {
                        // Display an error message if password is not valid
                        $error_msg = "Invalid username or password.";
                    }
                }
            } else {
                // Display an error message if username doesn't exist
                $error_msg = "Invalid username or password.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }
}

$conn->close();
?>

<div class="login-container">
    <h1>Login</h1>
    <?php
    if ($error_msg) {
        echo "<p class='error-msg'>$error_msg</p>";
    }
    ?>
    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="login">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Sign up here</a></p>
</div>

<?php
include 'includes/footer.php';
?>