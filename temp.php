<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$host = 'localhost'; // Your database host, e.g., 'localhost'
$dbname = 'feastly'; // Your database name
$dbuser = 'root'; // Your database username
$dbpass = ''; // Your database password

// Create database connection
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = "amal";  // Example username
$hashed_password = "";

// Prepare a select statement
$sql = "SELECT hashed_password FROM users WHERE username = ?";

if ($stmt = $conn->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $username);
    echo $username . " yay1";  // Confirm the username is bound

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Store result to ensure that metadata (like num_rows) is accurate
        $stmt->store_result();

        // Check if the username exists in the database
        if ($stmt->num_rows == 1) {
            // Bind the result variables
            $stmt->bind_result($hashed_password);

            // Fetch the result from the query
            if ($stmt->fetch()) {
                echo " Hashed Password: " . $hashed_password . " yay";  // Display the hashed password
                echo " Reached 4";  // Debug statement to confirm fetch was successful
            } else {
                echo " Error: Unable to fetch the results.";
            }
        } else {
            echo " Error: Username does not exist or there are multiple entries.";
        }
    } else {
        echo " Error: Execution failed. " . $stmt->error;
    }

    // Close statement
    $stmt->close();
} else {
    echo " SQL prepare failed: " . $conn->error;
}
