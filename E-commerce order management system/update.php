<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

//$user_email = $_SESSION['email'];

// Retrieve new phone number from form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
     var_dump($_POST);
    $user_email=$_POST['uname'];
    $new_phone_number = $_POST['pnum'];

    // Database connection parameters
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

    // Prepare and execute SQL update statement
    $sql = "UPDATE oms.customers SET pnumber = ? WHERE email_addr = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_phone_number, $user_email);

    if ($stmt->execute()) {
        echo "Phone number updated successfully.";
    } else {
        echo "Error updating phone number: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
header("Location: main_page_loggedin.html");
?>
