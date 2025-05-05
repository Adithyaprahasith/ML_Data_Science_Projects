<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); // Start session for storing user data

$servername = "localhost:3306";
$username = "root";
$password = "1234";
$dbname = "oms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = $_POST["uname"];
    $pwd = $_POST["pwd"];

    // Prepare SQL statement to fetch user from database
    $sql = "SELECT * FROM customers WHERE email_addr = ? AND c_password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $uname, $pwd);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows == 1) {
        // Login successful
        $_SESSION["username"] = $uname; // Store username in session
        echo "Login successful! Welcome, " . $uname;
        header("Refresh: 2; url=main_page_loggedin.html");
        exit();
    } else {
        // Login failed
        echo "Invalid username or password. Please try again.";
        // Redirect back to login page
        header("Refresh: 2; url=signin.html"); // Redirect after 3 seconds
        exit();
    }
}

$stmt->close();
$conn->close();
?>
