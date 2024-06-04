<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ckaaf</title>
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <section id="header">
            <a href="#"><img src="img/logo.png" class="logo" alt=""></a>

            <div>
                <ul id="navbar">
                    <li><a href="index.php">Home</a></li>
                    <li><a class="active" href="shop.php">Shop</a></li>
                    <li><a href="about.html">About</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li id="lg-bag"><a href="cart.php"><i class="far fa-shopping-bag"></i></a></li>
                    <div class="dropdown">
                        <button class="dropbtn"><img src="img/people/uicon.jpeg" class="uicon" alt=""></button>
                        <div class="dropdown-content">
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

        <section id="prodetails" class="section-p1">
            <?php
            // Include the file with the database connection
            include "connection.php";

            session_start();

            
            // Check if product ID is set in the URL
            if (isset($_GET['prodId'])) {
                // Retrieve user ID from the session
                $userId = $_SESSION['user_id'] ?? '';

            // Function to add item to cart
            function addToCart($conn, $userId, $itemId, $price, $prodname, $quantity) {
                if (empty($userId)) {
                    return "User ID not found. Please log in to add items to your cart.";
                }

                // Add the item to the cart table
                $totalPrice = $price * $quantity;
                $sql_add_to_cart = "INSERT INTO `carts` (`userId`, `prodId`, `price`, `prodname`, `quantity`, `totalPrice`) VALUES ('$userId', '$itemId', '$price', '$prodname', '$quantity', '$totalPrice')";
                $execute_cart = mysqli_query($conn, $sql_add_to_cart);

                // Check if the query was successful
                if (!$execute_cart) {
                    return "Error adding item to cart: " . mysqli_error($conn);
                } else {
                    return "Add to cart success!";
                    // Empty the variables after successful addition
                    $productId = '';
                    $quantity = '';
                }


            }

            // Function to fetch product details
            function getProductDetails($conn, $prodId) {
                // Sanitize the input to prevent SQL injection
                $prodId = mysqli_real_escape_string($conn, $prodId);

                // Query to fetch product details based on product ID
                $query = "SELECT prodId, prodname, price, prod_description, prodpic FROM products WHERE prodId = '$prodId'";
                $result = mysqli_query($conn, $query);

                // Check if the query was successful
                if ($result && mysqli_num_rows($result) > 0) {
                    // Fetch product details
                    return mysqli_fetch_assoc($result);
                } else {
                    // Product not found
                    return false;
                }
            }

             // Check if the form has been submitted
             if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addToCart'])) {
                // Retrieve user ID from the session
                $userId = $_SESSION['user_id'] ?? '';

                // Retrieve product ID and quantity from the POST data
                $productId = $_POST['prodId'];
                $quantity = $_POST['quantity'];

                // Fetch product details
                $productDetails = getProductDetails($conn, $productId);

                // Call the addToCart function
                $result = addToCart($conn, $userId, $productId, $productDetails['price'], $productDetails['prodname'], $quantity);
 
            }
        

                // Fetch product details
                $productDetails = getProductDetails($conn, $_GET['prodId']);

                if ($productDetails) {
                    // Display product details
                    ?>

                    <div class="single-pro-image">
                        <img src="data:img/jpg;base64,<?php echo base64_encode($productDetails['prodpic']); ?>" width="100%" id="MainImg" alt="">
                    </div>
                    <div class="single-pro-details">
                        <h4><?php echo $productDetails['prodname']; ?></h4>
                        <h2>$<?php echo $productDetails['price']; ?></h2>
                        <select name="size" form="addToCartForm">
                            <option value="">Select Size</option>
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                        </select>
                        <form method="POST" id="addToCartForm" action="request/action.php">
                            <input type="hidden" name="prodId" value="<?php echo $productDetails['prodId']; ?>">
                            <input type="number" name="quantity" value="<?php echo isset($_GET['quantity']) ? $_GET['quantity'] : '1'; ?>">
                            <button class="normal" type="submit" name="addToCart">Add To Cart</button>
                        </form>

                        <h4>Product Details</h4>
                        <span><?php echo $productDetails['prod_description']; ?></span>
                    </div>
                    <?php 
                } else {
                    echo "Product not found.";
                }
            } else {
                echo "Product ID not provided.";
            }
        ?>
        </section>

        <section id="product1" class="section-p1">
            <h2>Featured Products </h2>
            <p>Summer Collection New Morden Design</p>
            <div class="pro-container">
                <?php
                // Fetch all products from the products table
                $query = "SELECT prodId, prodname, price, prodpic FROM products";
                $result = mysqli_query($conn, $query);

                // Check if there are any products
                if ($result && mysqli_num_rows($result) > 0) {
                    // Iterate over each product
                    while ($productDetails = mysqli_fetch_assoc($result)) {
                ?>
                        <a href="sproduct.php?prodId=<?php echo $productDetails['prodId']; ?>">
                            <div class="pro">
                                <img src="data:image/jpg;base64,<?php echo base64_encode($productDetails['prodpic']); ?>" />
                                <div class="des">
                                    <h5><?php echo $productDetails['prodname']; ?></h5>
                                    <div class="star">
                                        <!-- You can display star ratings here if available -->
                                    </div>
                                    <h4>$<?php echo $productDetails['price']; ?></h4>
                                </div>
                                <a href="request/action.php"><i class="fal fa-shopping-cart cart"></i></a>
                            </div>
                        </a>
                <?php
                    }
                }
                ?>
            </div>
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
                <img class="logo" src="img/logo.png" alt="">
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
                <h4>Install App</h4>
                <p>From App Store or Google Play</p>
                <div class="row">
                    <img src="img/pay/app.jpg" alt="">
                    <img src="img/pay/play.jpg" alt="">
                </div>
                <p>Payment Options</p>
                <img src="img/pay/pays.png" alt="">
            </div>

            <div class="copyright">
                <p>© 2021, Tech2 etc - HTML CSS Ecommerce Template</p>
            </div>
        </footer>   

        <script src="script.js"></script>

    </body>
</html>