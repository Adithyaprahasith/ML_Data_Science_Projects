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

// Process form submission for adding items to cart
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'add_to_cart_') === 0 && $value > 0) {
            // Extract product ID from key (e.g., 'add_to_cart_123' -> 123)
            $productId = substr($key, strlen('add_to_cart_'));

            // Get quantity from corresponding quantity dropdown
            $quantityKey = 'quantity_' . $productId;
            $quantity = $_POST[$quantityKey];

            // Retrieve product information from the 'products' table
            $sql_product = "SELECT product_name, price, product_imgpath FROM oms.products WHERE product_id = '$productId'";
            $result_product = $conn->query($sql_product);

            if ($result_product->num_rows > 0) {
                $row_product = $result_product->fetch_assoc();
                $productName = $row_product["product_name"];
                $productPrice = $row_product["price"];
                $imagePath = $row_product["product_imgpath"];

                // Calculate total price based on quantity
                $totalPrice = $productPrice * $quantity;

                // Insert data into 'carts' table
                $sql_insert = "INSERT INTO oms.cart (product_id, product_name, product_imgpath, quantity, price, total_price) 
                               VALUES ('$productId', '$productName', '$imagePath', '$quantity', '$productPrice', '$totalPrice')";

                if ($conn->query($sql_insert) === TRUE) {
                    echo "Product added to cart successfully.";
                } else {
                    echo "Error: " . $sql_insert . "<br>" . $conn->error;
                }
            } else {
                echo "Product not found.";
            }
        }
    }
}

// Process form submission for removing all items from the cart
if (isset($_POST['remove_items'])) {
    $sql_truncate = "truncate TABLE cart";
    if ($conn->query($sql_truncate) === TRUE) {
        echo "All items removed from the cart.";
    } else {
        echo "Error truncating the cart: " . $conn->error;
    }
}

// Retrieve product information including the image data from the 'carts' table
$sql_retrieve = "SELECT product_name, quantity, price, sum(total_price) as total_price FROM oms.cart GROUP BY 1,2,3";
$result = $conn->query($sql_retrieve);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <style>
        body{
            margin: 0;
            font-family: 'Arvo';
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        input[type="submit"] {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #1E90FF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #000000;
  position: fixed;
  top: 0;
  width: 100%;
  text-align: center;
}

li {
  float: left;
display: inline-block;
}

li a {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  
}

li a:hover:not(.active) {
  background-color: #1E90FF;
}

.active {
  background-color: #1E90FF;

}

        .log{
             float: right;
        }
    </style>
</head>
<body>

</style>

<body>
  <div>
   <ul>
  <li><a class="" href="main_page_loggedin.html">Home</a></li>
  <li><a href="mobiles.php">Mobiles</a></li>
  <li><a href="laptops.php">Laptops</a></li>
  <li><a href="watches.php">Watches</a></li>
 <div class="log">
    <li><a class="active" href="cart.php" id="sinn">Cart</a></li>
  </div>
 
</ul>
<h1></h1><br><br>
    <h1> Your Shopping Cart</h1>

    <!-- Displayed Items in Cart -->
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["product_name"]; ?></td>
                        <td><?php echo $row["quantity"]; ?></td>
                        <td>$<?php echo $row["price"]; ?></td>
                        <td>$<?php echo $row["total_price"]; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- Form for removing items from the cart -->
        <form method="post">
            <input type="submit" name="remove_items" value="Remove Items">
        </form>
    <?php else: ?>
        <p>No items in the cart.</p>
    <?php endif; ?>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
