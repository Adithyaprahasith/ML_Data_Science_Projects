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

$val = $_POST['search'];

// Retrieve product information including the image data
$sql = "SELECT product_id, product_name, price, product_imgpath FROM oms.products where product_name like '%$val%'";
$result = $conn->query($sql);


echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<title>Product Table</title>';
echo '<link rel="stylesheet" >'; // Include external CSS file for styling
echo '<style>';
echo 'body {';
echo '    font-family: Arial, sans-serif;';
echo '    display: flex;';
echo '    justify-content: center;';
echo '    align-items: center;';
echo '    height: 100vh; /* Center vertically */';
echo '}';
echo 'ul {';
echo '    list-style-type: none;';
echo '    margin: 0;';
echo '    padding: 0;';
echo '    overflow: hidden;';
echo '    background-color: #000000;';
echo '    position: fixed;';
echo '    top: 0;';
echo '    width: 100%;';
echo '    text-align: center;';
echo '}';
echo 'li {';
echo '    float: left;';
echo '    display: inline-block;';
echo '}';
echo 'li a {';
echo '    display: block;';
echo '    color: white;';
echo '    text-align: center;';
echo '    padding: 14px 16px;';
echo '    text-decoration: none;';
echo '}';
echo 'li a:hover:not(.active) {';
echo '    background-color: #1E90FF;';
echo '}';
echo '.active {';
echo '    background-color: #1E90FF;';
echo '}';
echo'.log{';
echo'float: right;';
echo'}';
echo 'table {';
echo '    width: 120%; /* Adjust the width of the table */';
echo '    border-collapse: collapse;';
echo '}';
echo 'table, th, td {';
echo '    border: 1px solid #ddd; /* Border style for table cells */';
echo '}';
echo 'th, td {';
echo '    padding: 10px; /* Padding inside table cells */';
echo '    text-align: left; /* Align text within cells */';
echo '}';
echo 'img {';
echo '    max-width: 130px; /* Limit image width */';
echo '    height: auto; /* Maintain aspect ratio */';
echo '}';
echo '</style>';
echo '</head>';
echo '<body>';
echo '<ul>';
echo '  <li><a href="main_page_loggedin.html">Home</a></li>';
echo '  <li><a class="active" href="mobiles.php">Mobiles</a></li>';
echo '  <li><a href="laptops.php">Laptops</a></li>';
echo '  <li><a href="watches.php">Watches</a></li>';
echo'<div class="log">';
echo'<li><a href="cart.php" id="sinn">Cart</a></li>';
echo'</div>';
echo '</ul>';
echo '<h1 style="text-align: center;"></h1><br><br><br>';
echo '<br><br>';
// Display form and table
echo '<form action="cart.php" method="POST" class="product-form">';
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<th></th>';
echo '<th></th>';
echo '<th></th>';
echo '<th></th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $productId = $row["product_id"];
        $productName = $row["product_name"];
        $productPrice = $row["price"];
        $imagePath = $row["product_imgpath"];

        // Generate table row for each product
        echo '<tr>';
        echo '<td>';
        
        // Display image using base64 encoding (if image path is available)
        if ($imagePath !== null) {
            $imageData = file_get_contents($imagePath);
            if ($imageData !== false) {
                $imageDataEncoded = base64_encode($imageData);
                $imageMimeType = mime_content_type($imagePath);
                echo "<img src='data:$imageMimeType;base64,$imageDataEncoded' alt='$productName'>";
            } else {
                echo "Failed to read image data.";
            }
        } else {
            echo "Image not available.";
        }

        echo '</td>';
        echo '<td>';
        echo "<p><strong>$productName</strong></p>";
        echo "<p>Price:$$productPrice</p>";
        echo '</td>';

        // Quantity dropdown menu
        echo '<td>';
        echo '<label for="quantity">Quantity:</label>';
        echo "<select name='quantity_$productId' id='quantity_$productId' onchange=\"updateButtonState($productId)\">"; // Unique ID for each product
        echo "<option value='0'>0</option>"; // Default quantity is zero
        for ($i = 1; $i <= 3; $i++) {
            echo "<option value='$i'>$i</option>";
        }
        echo '</select>';
        echo '</td>';

        // Add to Cart button (enable only if quantity > 0)
        echo '<td>';
        echo "<button type='submit' name='add_to_cart_$productId' value='$productId' id='add_to_cart_$productId' ";
        echo "disabled>Add to Cart</button>";
        echo '</td>';

        echo '</tr>';
    }
} else {
    echo "<tr><td colspan='5'>No products found.</td></tr>";
}

echo '</tbody>';
echo '</table>';

echo '</form>';

// JavaScript function to update button state based on selected quantity
echo '<script>';
echo 'function updateButtonState(productId) {';
echo '    const quantitySelect = document.getElementById(`quantity_${productId}`);';
echo '    const addButton = document.getElementById(`add_to_cart_${productId}`);';
echo '    addButton.disabled = (quantitySelect.value === "0");';
echo '}';
echo '</script>';
echo '</html>';

// Close database connection
$conn->close();
?>
