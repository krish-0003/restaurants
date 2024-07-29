<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/config.php'; // Include your database configuration file

// Create database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for error message and form data
$username = $password = $email = "";
$error_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $email = htmlspecialchars($_POST['email']);
    $confirm_password = htmlspecialchars($_POST['reenter-password']);

    // Check if all fields are filled and passwords match
    if (empty($username) || empty($password) || empty($email) || $password !== $confirm_password) {
        $error_msg = "Please fill in all fields and ensure passwords match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Database insertion logic
        $sql = "INSERT INTO `Users` (`username`, `email`, `password_hash`, `created_at`, `user_type`) VALUES (?, ?, ?, NOW(), 'customer')";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            if ($stmt->execute()) {
                $success_msg = "Registration successful! You can now login.";
            } else {
                $error_msg = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error_msg = "Error preparing statement: " . $conn->error;
        }
    }
}
?>

<div class="login-container">
    <h1>Sign Up</h1>
    <?php
    if ($error_msg) {
        echo "<p class='error-msg'>$error_msg</p>";
    }
    if ($success_msg) {
        echo "<p class='success-msg'>$success_msg</p>";
    }
    ?>
    <form action="register.php" method="POST" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="reenter-password">Re-enter password</label>
            <input type="password" id="reenter-password" name="reenter-password" required>
        </div>
        <button type="submit" name="signup">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

<script>
function validatePassword(password) {
    const minLength = 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasNumber = /\d/.test(password);
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

    return password.length >= minLength && hasUppercase && hasLowercase && hasNumber && hasSpecialChar;
}

function updatePasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthSpan = document.getElementById('password-strength');
    
    if (validatePassword(password)) {
        strengthSpan.textContent = 'Strong password';
        strengthSpan.style.color = 'green';
    } else {
        strengthSpan.textContent = 'Weak password';
        strengthSpan.style.color = 'red';
    }
}

function validateForm() {
    const password = document.getElementById('password').value;
    const reenterPassword = document.getElementById('reenter-password').value;

    if (!validatePassword(password)) {
        alert('Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.');
        return false;
    }

    if (password !== reenterPassword) {
        alert('Passwords do not match.');
        return false;
    }

    return true;
}

document.getElementById('password').addEventListener('input', updatePasswordStrength);
</script>


<?php
include 'includes/footer.php';
?>