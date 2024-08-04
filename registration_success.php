<?php
include 'includes/header.php';
include 'includes/navbar.php';

// Check if the user was redirected here after successful registration
// if (!isset($_SESSION['registration_success']) || $_SESSION['registration_success'] !== true) {
//     header("Location: register.php");
//     exit();
// }

// Clear the session variable
unset($_SESSION['registration_success']);
?>

<div class="confirmation-container">
    <h1>Registration Successful!</h1>
    <p>Thank you for registering with us. Your account has been created successfully.</p>  
    <a href="login.php" class="btn btn-primary">Login</a>
</div>

<style>
    .confirmation-container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    text-align: center;
}

.confirmation-container h1 {
    color: #333;
    margin-bottom: 20px;
}

.confirmation-container p {
    margin-bottom: 15px;
}

.additional-info {
    text-align: left;
    margin-top: 30px;
}

.additional-info h2 {
    font-size: 1.2em;
    margin-bottom: 10px;
}

.additional-info ul {
    padding-left: 20px;
}

.btn-primary {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #333;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.btn-primary:hover {
    background-color: #555;
}
</style>
<?php
include 'includes/footer.php';
?>