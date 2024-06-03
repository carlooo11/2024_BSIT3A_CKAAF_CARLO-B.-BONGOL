<?php
// Include the connection file
include "../connection.php";

session_start();

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['user_id'])) {
    // Retrieve the userId from the session
    $user_id = $_SESSION['user_id'];

    // Fetch orders from the database for the logged-in user
    $sql_fetch_orders = "SELECT o.*, p.prodname, p.price, p.prodpic 
                         FROM orders o
                         JOIN products p ON o.prodId = p.prodId
                         WHERE o.userId = '$user_id'";
    $result_orders = mysqli_query($conn, $sql_fetch_orders);
} else {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Ckaaf</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <section id="header">
        <a href="#"><img src="../img/logo.png" class="logo" alt=""></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li id="lg-bag"><a href="cart.php"><i class="far fa-shopping-bag"></i></a></li>
                <div class="dropdown">
                    <button class="dropbtn"><img src="../img/people/uicon.jpeg" class="uicon" alt=""></button>
                    <div class="dropdown-content">
                        <a href="order.php">My Orders</a>
                        <a href="../request/logout.php">Logout</a>
                    </div>
                </div>
                <a id="close" href="#"><i class="far fa-times"></i></a>
            </ul>
        </div>
        <div id="mobile">
            <a href="cart.php"><i class="far fa-shopping-bag"></i></a>
            <i id="bar" class="fas fa-outdent"></i>
        </div>
    </section>

    <section id="page-header" class="about-header">
        <h2>#MyOrders</h2>
        <p>Review your purchase history and track orders</p>
    </section>

    <section id="orders" class="section-p1">
        <table width="100%">
            <thead>
                <tr>
                    <td>Image</td>
                    <td>Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Total</td>
                    <td>Order Date</td>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if there are any orders found
                if (mysqli_num_rows($result_orders) > 0) {
                    // Loop through fetched orders and display them
                    while ($order = mysqli_fetch_assoc($result_orders)) {
                ?>
                        <tr>
                            <td><img src="data:image/jpg;base64,<?php echo base64_encode($order['prodpic']); ?>" /></td>
                            <td><?php echo $order['prodname']; ?></td>
                            <td>$<?php echo $order['price']; ?></td>
                            <td><?php echo $order['quantity']; ?></td>
                            <td>$<?php echo $order['totalPrice']; ?></td>
                            <td><?php echo date("Y-m-d", strtotime($order['orderDate'])); ?></td>
                        </tr>
                <?php
                    }
                } else {
                    // Display a message if no orders are found
                    echo "<tr><td colspan='6'>No orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <footer class="section-p1">
        <div class="col">
            <h4>Contact</h4>
            <p><strong>Address: </strong> Purok Earth, Centro Occidental, Buhi - Polangui Rd, Polangui, 4506 Albay</p>
            <p><strong>Phone:</strong>(+63) 9462243688</p>
            <p><strong>Hours:</strong> 9:00 - 4:00, Mon - Sat</p>
            <div class="follow">
                <h4>Follow Us</h4>
                <div class="icon">
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-twitter"></i>
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-pinterest-p"></i>
                    <i class="fab fa-youtube"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <h4>About</h4>
            <a href="#">About Us</a>
            <a href="#">Delivery Information</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms & Conditions</a>
            <a href="#">Contact Us</a>
        </div>

        <div class="col">
            <h4>My Account</h4>
            <a href="#">My Orders</a>
            <a href="cart.php">View Cart</a>
            <a href="#">My Wishlist</a>
            <a href="#">Track My Order</a>
            <a href="#">Help</a>
        </div>

        <div class="col install">
            <p>Secured Payment Gateways </p>
            <img src="../img/pay/pay.png" alt="">
        </div>

        <div class="copyright">
            <p>© 2024, Ckaaf Clothing Shop</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>

</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
