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
        <a href="#"><img src="img/logo.png" class="logo" alt=""></a>

        <div>
            <ul id="navbar">
                <li><a class="active" href="index.php">Home</a></li>
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
                <a href="#" id="close"><i class="far fa-times"></i></a>
            </ul>
        </div>
        <div id="mobile">
            <a href="cart.php"><i class="far fa-shopping-bag"></i></a>
            <i id="bar" class="fas fa-outdent"></i>
        </div>
    </section>

    <section id="hero">
        <h4>Trade-in-offer</h4>
        <h2>Super value deals</h2>
        <h1>On all products</h1>
        <p>Save more with coupons & up to 70% off! </p>
        <button>Shop Now</button>
    </section>

    <section id="feature" class="section-p1">
        <div class="fe-box">
            <img src="../img/features/f1.png" alt="">
            <h6>Free Shipping</h6>
        </div>
        <div class="fe-box">
            <img src="../img/features/f2.png" alt="">
            <h6>Online Order</h6>
        </div>
        <div class="fe-box">
            <img src="../img/features/f3.png" alt="">
            <h6>Save Money</h6>
        </div>
        <div class="fe-box">
            <img src="../img/features/f4.png" alt="">
            <h6>Promotions</h6>
        </div>
        <div class="fe-box">
            <img src="../img/features/f5.png" alt="">
            <h6>Happy Sell</h6>
        </div>
        <div class="fe-box">
            <img src="../img/features/f6.png" alt="">
            <h6>F24/7 Support</h6>
        </div>
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
                            <a href="index.php?prodId=<?php echo $row['prodId']; ?>&quantity=1&this_page=index.php"><i class="fal fa-shopping-cart cart"></i></a>
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

 
    <section id="banner" class="section-m1">
        <h4>Repair Services </h4>
        <h2>Up to <span>70% Off</span> – All t-Shirts & Accessories</h2>
        <button class="normal">Explore More</button>
    </section>

    <section id="product1" class="section-p1">
        <h2>New Arrivals</h2>
        <p>Summer Collection New Morden Design</p>
        <div class="pro-container">
            <div class="pro">
                <img src="../img/products/n1.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Cartoon Astronaut T-Shirts</h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <a href="#"><i class="fal fa-shopping-cart cart"></i></a>
            </div>
            <div class="pro">
                <img src="../img/products/n2.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Cartoon Astronaut T-Shirts</h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <a href="#"><i class="fal fa-shopping-cart cart"></i></a>
            </div>
            <div class="pro">
                <img src="../img/products/n3.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Cartoon Astronaut T-Shirts</h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <a href="#"><i class="fal fa-shopping-cart cart"></i></a>
            </div>
            <div class="pro">
                <img src="../img/products/n4.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Cartoon Astronaut T-Shirts</h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <a href="#"><i class="fal fa-shopping-cart cart"></i></a>
            </div>
            <div class="pro">
                <img src="../img/products/n5.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Cartoon Astronaut T-Shirts</h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <a href="#"><i class="fal fa-shopping-cart cart"></i></a>
            </div>
            <div class="pro">
                <img src="../img/products/n6.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Cartoon Astronaut T-Shirts</h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <a href="#"><i class="fal fa-shopping-cart cart"></i></a>
            </div>
            <div class="pro">
                <img src="../img/products/n7.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Cartoon Astronaut T-Shirts</h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <a href="#"><i class="fal fa-shopping-cart cart"></i></a>
            </div>
            <div class="pro">
                <img src="../img/products/n8.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Cartoon Astronaut T-Shirts</h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>$78</h4>
                </div>
                <a href="#"><i class="fal fa-shopping-cart cart"></i></a>
            </div>
        </div>
    </section>

    <section id="sm-banner" class="section-p1">
        <div class="banner-box">
            <h4>crazy deals</h4>
            <h2>buy 1 get 1 free</h2>
            <span>The best classic dress is on sale at cara</span>
            <button class="white">Learn More</button>
        </div>
        <div class="banner-box banner-box2">
            <h4>spring/summer</h4>
            <h2>upcomming season</h2>
            <span>The best classic dress is on sale at cara</span>
            <button class="white">Collection</button>
        </div>
    </section>

    <section id="banner3">
        <div class="banner-box">
            <h2>SEASONAL SALE</h2>
            <h3>Winter Collection -50% OFF</h3>
        </div>
        <div class="banner-box banner-box2">
            <h2>NEW FOOTWEAR COLLECTION </h2>
            <h3>Spring / Summer 2022</h3>
        </div>
        <div class="banner-box banner-box3">
            <h2>T-SHIRTS</h2>
            <h3>New Trendy Prints</h3>
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

    <div> </div>
    <script>
        const wrapper = document.querySelector('.wrapper');
        const registerLink = document.querySelector('.register-link');
        const loginLink = document.querySelector('.login-link');
        
        registerLink.onclick = () =>{
            wrapper.classList.add('active');
        }
        
        loginLink.onclick = () =>{
            wrapper.classList.remove('active');
        }</script>

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