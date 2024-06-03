<?php
session_start();

// Include the connection file
include "../connection.php";

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['prodId']) && isset($_GET['quantity']) && isset($_GET['this_page'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "";
    $item_id = $_GET['prodId'];
    $prev_page = $_GET['this_page'];
    $item_qty = $_GET['quantity'];

    // Fetch additional product information from the products table
    $sql_fetch_product_info = "SELECT prodname, price FROM products WHERE prodId = '$item_id'";
    $result_product_info = mysqli_query($conn, $sql_fetch_product_info);
    

    // Check if the query was successful
    if ($result_product_info && mysqli_num_rows($result_product_info) > 0) {
        // Fetch the product information
        $product_info = mysqli_fetch_assoc($result_product_info);
        $prodname = $product_info['prodname'];
        $price = $product_info['price'];
    }

    $totalPrice = $item_qty * $price; 

    $sql_add_to_cart = "INSERT INTO `carts` (`userId`, `prodId`, `price`, `prodname`, `quantity`, `totalPrice`) VALUES ('$user_id', '$item_id', '$price', '$prodname', '$item_qty', '$totalPrice')";
    $execute_cart = mysqli_query($conn, $sql_add_to_cart);

    if ($execute_cart) {
        $_SESSION['success_message'] = "Item added to cart successfully."; // Store success message in session
    } else {
        echo "Error adding item to cart: " . mysqli_error($conn);
    }

    header("Location: " . $prev_page . "?status=" . urlencode($_SESSION['success_message'])); // Redirect to the previous page with success message
    exit(); // Stop executing further code
}

// Query to fetch products from the database
$query = "SELECT prodId, prodname, price, prodpic FROM products";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ckaaf</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <section id="header">
        <a href="#"><img src="../img/logo.png" class="logo" alt=""></a>

        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a class="active" href="shop.html">Shop</a></li>
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
                <a href="#" id="close"><i class="far fa-times"></i></a>
            </ul>
        </div>
        <div id="mobile">
            <a href="cart.php"><i class="far fa-shopping-bag"></i></a>
            <i id="bar" class="fas fa-outdent"></i>
        </div>
    </section>

    <section id="page-header">
        <h2>#stayhome</h2>
        <p>Save more with coupons & up to 70% off!</p>
    </section>

    <section id="product1" class="section-p1">
        <div class="pro-container">
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <a href="sproduct.php?prodId=<?php echo $row['prodId']; ?>">
                        <div class="pro">
                            <img src="data:image/jpg;base64,<?php echo base64_encode($row['prodpic']); ?>" />
                            <div class="des">
                                <h5><?php echo $row['prodname']; ?></h5>
                                <div class="star">
                                    <!-- You can display star ratings here if available -->
                                </div>
                                <h4>$<?php echo $row['price']; ?></h4>
                            </div>
                            <a href="shop.php?prodId=<?php echo $row['prodId']; ?>&quantity=1&this_page=shop.php"><i class="fal fa-shopping-cart cart"></i></a>
                        </div>
                    </a>
            <?php
                }
            } else {
                echo "No products available.";
            }
            ?>
        </div>
    </section>

    <section id="pagination" class="section-p1">
        <a href="#">1</a>
        <a href="#">2</a>
        <a href="#"><i class="fal fa-long-arrow-alt-right"></i></a>
    </section>

    <section id="newsletter" class="section-p1 section-m1">
        <div class="newstext">
            <h4>Sign Up For Newsletters</h4>
            <p>Get E-mail updates about our latest shop and <span>special offers.</span> </p>
        </div>
        <div class="form">
            <input type="text" placeholder="Your email address">
            <button class="normal">Sign Up</button>
        </div>
    </section>

    <footer class="section-p1">
        <div class="col">
        <h4>Contact</h4>
                <p><strong>Address: </strong> 562 Wellington Road, Street 32, San Francisco</p>
                <p><strong>Phone:</strong> +01 2222 365 /(+91) 01 2345 6789</p>
                <p><strong>Hours:</strong> 10:00 - 18:00, Mon - Sat</p>
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
                <a href="#">Sign In</a>
                <a href="#">View Cart</a>
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


        <script>
            // Check for status message in URL
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            if (status) {
                // Display status message
                const messageDiv = document.createElement('div');
                messageDiv.id = 'message';
                messageDiv.textContent = decodeURIComponent(status);
                messageDiv.style.position = 'fixed';
                messageDiv.style.top = '0';
                messageDiv.style.left = '0';
                messageDiv.style.width = '100%';
                messageDiv.style.backgroundColor = 'green';
                messageDiv.style.color = 'white';
                messageDiv.style.textAlign = 'center';
                messageDiv.style.padding = '10px';
                messageDiv.style.zIndex = '1000';
                document.body.appendChild(messageDiv);

                // Hide the message after 3 seconds
                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 3000);
            }
        </script>

        <script src="script.js"></script>
    </body>

    </html>

