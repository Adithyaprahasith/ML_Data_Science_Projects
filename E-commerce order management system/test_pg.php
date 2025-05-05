<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>
    <style>
        body {
            font-family: Arvo;
            background-color: #f7f7f7;
            margin: 0;
        }
        .order-container {
            max-width: 600px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
            color: #333333;
        }
        .order-details {
            margin-top: 20px;
        }
        .order-details p {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
        }
        .status {
            font-weight: bold;
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
     <ul>
  <li><a class="" href="main_page_loggedin.html">Home</a></li>
  <li><a href="mobiles.php">Mobiles</a></li>
  <li><a href="laptops.php">Laptops</a></li>
  <li><a href="watches.php">Watches</a></li>

  <div class="log">

  </div>
</ul><br><br>
    <div class="order-container">
       <br><br>
        <h2>Order Details</h2>
        <?php
        $servername = "localhost:3306";
        $username = "root";
        $password = "1234";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $val = $_POST['ordid'];

        $sql = "SELECT order_id, address, order_status, num_products, est_days_to_ship as estimated_delivery_by FROM oms.orders WHERE order_id = $val";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<div class="order-details">';
                echo '<p><span class="label">Order ID:</span> ' . $row["order_id"] . '</p>';
                echo '<p><span class="label">Status:</span> <span class="status">' . $row["order_status"] . '</span></p>';
                echo '<p><span class="label">Address:</span> ' . $row["address"] . '</p>';
                echo '<p><span class="label">Estimated Delivery By:</span> ' . $row["estimated_delivery_by"] . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No results found for order ID: ' . $val . '</p>';
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
