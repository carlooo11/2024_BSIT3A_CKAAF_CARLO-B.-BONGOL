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
}

// Fetch completed orders from the database
$sql_fetch_order_items = "SELECT * FROM orders WHERE userId = '$user_id' AND orderStatus = 'Canceled' ORDER BY order_reference_number";
$result_order_items = mysqli_query($conn, $sql_fetch_order_items);
?>

<!DOCTYPE html> 
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
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
                        <a href="my_orders.php">My Orders</a>
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
        <div class="myordersnav">
            <ul id="ordernavbar">
                <li><a href="my_orders.php">Pending</a></li>
                <li><a href="myOrders_shipped.php">Shipped</a></li>
                <li><a href="myOrders_delivered.php">Delivered</a></li>
                <li><a href="myOrders_completed.php">Completed</a></li>
                <li><a class="active" href="myOrders_canceled.php">Canceled</a></li>
            </ul>
        </div>
  
        <h2>#my orders</h2>
        <p>Add your coupon code & SAVE up to 70%!</p>
    </section>

    <section id="cart" class="section-p1">
        <form action="../request/cancel_order_request.php" method="post">
            <table width="100%">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Image</td>
                        <td>Product</td>
                        <td>Price</td>
                        <td>Quantity</td>
                        <td>Size</td>
                        <td>Total Price</td>
                        <td>Cancellation Date</td>
                        <td>Status</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1;
                    $current_order_reference = "";
                    $toggle_class = false;

                    // Check if there are any completed orders found
                    if (mysqli_num_rows($result_order_items) > 0) {
                        // Loop through fetched completed orders and display them
                        while ($row = mysqli_fetch_assoc($result_order_items)) {
                            if ($current_order_reference != $row['order_reference_number']) {
                                if ($current_order_reference != "") {
                                    // Close the previous order group
                                    echo "<tr class='$row_class'><td colspan='9' id='totalorder'>Total: $total_price</td></tr>";
                                }
                                $current_order_reference = $row['order_reference_number'];
                                $subtotal = $row['totalPrice'];
                                $total_price =+ $subtotal;
                                $size = $row['size'];
                                $toggle_class = !$toggle_class;
                                $row_class = $toggle_class ? "order-batch-1" : "order-batch-2";
                                echo "<tr class='$row_class'><td colspan='9' id='refnum'><strong style='color:black';>Order Reference Number: </strong>" . $current_order_reference . "</td></tr>";
                            }

                            // Fetch additional product details if needed
                            $item_id = $row['prodId'];
                            $sql_fetch_product = "SELECT * FROM products WHERE prodId = '$item_id'";
                            $result_product = mysqli_query($conn, $sql_fetch_product);
                            $product = mysqli_fetch_assoc($result_product);

                    ?>

                            <tr class="<?php echo $row_class; ?>">
                                <td><?php echo $counter?></td>
                                <td><img src="data:image/jpg;base64,<?php echo base64_encode($product['prodpic']); ?>" alt="<?php echo $product['prodname']; ?>" /></td>
                                <td><?php echo $product['prodname']; ?></td>
                                <td>$<span class="price"><?php echo $product['price'];?></span></td>
                                <td><?php echo $row['quantity'];?></td>    
                                <td><?php echo $size;?></td>
                                <td>$<?php echo $subtotal;?></td>
                                <td style="color: green; font-weight: 600;"><?php echo $row['orderStatus'];?></td>
                            </tr>

                    <?php
                            $counter++;
                        }
                        // Close the last order group
                        echo "<tr class='$row_class' ><td colspan='9' id='totalorder'>Total: $total_price</td></tr>";
                        
                    } else {
                        // Display a message if no products are ordered
                        echo "<tr><td colspan='9'>No canceled orders found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </section>

    <section id="cart-add" class="section-p1">
        <div id="coupon">
            <h3>Apply Coupon</h3>
            <div>
                <input type="text" placeholder="Enter Your Coupon">
                <button class="normal">Apply</button>
            </div>
        </div>
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
        <p>Payment Options</p>
                <img src="../img/pay/pays.png" alt="">
        </div>

        <div class="copyright">
            <p>© 2024, Ckaaf Clothing Shop</p>
        </div>
    </footer>
   
    <style>
        .myordersnav{
            margin-bottom: 50px;
            margin-top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            padding: 14px;
        }
        #ordernavbar {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #ordernavbar li {
            list-style: none;
            padding: 0 20px;
            position: relative;
        }

        #ordernavbar li a {
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            color: #e9e9e9;
        }

        #ordernavbar li a:hover,
        #ordernavbar li a.active {
            color: lightseagreen;
        }

        #ordernavbar li a.active::after,
        #ordernavbar li a:hover::after {
            content: "";
            width: 30%;
            height: 2px;
            background: #088178;
            position: absolute;
            bottom: -4px;
            left: 20px;
        }
        .order-batch-1 {
            background-color: #f9f9f9;
        }
        .order-batch-2 {
            background-color: #e9e9e9;
        }
        #totalorder, #refnum{
            font-weight: 700;
            color: #088178;
        }
    </style>

    <script src="script.js"></script>
</body>

</html>