<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
$custid = substr(str_shuffle("0123456789"), 0, 3);
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$pwd = $_POST['pwd'];
$pnum = $_POST['pnum'];
$email = $_POST['email'];
$addr = $_POST['addr'];
$gender = $_POST['gender'];
$sql = "INSERT INTO customers 
VALUES ('$custid','$fname', '$lname','$pwd','$pnum','$email','$addr','$gender')";

if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}
header("Refresh: 2; url=signin.html"); 
$conn->close();
?>