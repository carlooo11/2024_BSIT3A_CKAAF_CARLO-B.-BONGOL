<?php
include "../connection.php";
session_start();

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // echo "User ID: " . $user_id . "<br>";
} else {
    echo "User not logged in.<br>";
}

if (isset($_POST['selected_items_json'])) {
    $_SESSION['selectedItems'] = json_decode($_POST['selected_items_json'], true);
}

// Function to generate a unique order reference number
function generateOrderReferenceNumber() {
    return uniqid('order_', true);
}

// Initialize checkout summary variable
$checkout_summary = '';
$order_reference_number = generateOrderReferenceNumber();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['selected_items']) && isset($_POST['quantities'])) {
        $selected_items = $_POST['selected_items'];
        $selectedItems = [];
        $selectedItems = $_SESSION['selectedItems'];
        $quantities = $_POST['quantities'];
        $subtotal = 0;
        $shipping_fee_per_product = 1; // Shipping fee per product

        // Construct checkout summary HTML
        $checkout_summary .= "<h3>Checkout Summary</h3>";

        foreach ($selected_items as $cartId) {
            // $checkout_summary .= "Processing cart item: " . $cartId . "<br>";
            $quantity = $quantities[$cartId];
            $sql_fetch_item = "SELECT * FROM carts WHERE cartId = '$cartId'";
            $result_item = mysqli_query($conn, $sql_fetch_item);
            if ($result_item) {
                $item = mysqli_fetch_assoc($result_item);
                $prodId = $item['prodId'];

                $sql_fetch_product = "SELECT * FROM products WHERE prodId = '$prodId'";
                $result_product = mysqli_query($conn, $sql_fetch_product);
                if ($result_product) {
                    $product = mysqli_fetch_assoc($result_product);
                    $price = $product['price'];

                    // Calculate subtotal for this item
                    $item_subtotal = $price * $quantity;
                    $shipping_fee = $shipping_fee_per_product * $quantity;
                    $item_total = $item_subtotal + $shipping_fee;
                    $subtotal += $item_total;

                    // Construct product details in checkout summary
                    $checkout_summary .= "<div class='product'>";
                    $checkout_summary .= "<img src='data:image/jpeg;base64," . base64_encode($product['prodpic']) . "' style='max-width: 150px; max-height: 150px;' />";
                    $checkout_summary .= "<p>Product: " . $product['prodname'] . "</p>";
                    $checkout_summary .= "<p>Size: " . $item['size'];
                    $checkout_summary .= "<p>Price: $" . $price . "</p>";
                    $checkout_summary .= "<p>Quantity: " . $quantity . "</p>";
                    $checkout_summary .= "<p>Shipping Fee: $" . number_format($shipping_fee, 2) . "</p>";
                    $checkout_summary .= "<p>Subtotal: $" . number_format($item_subtotal, 2) . "</p>";
                    $checkout_summary .= "<p>Total for this item: $" . number_format($item_total, 2) . "</p>";
                    $checkout_summary .= "</div>";
                } else {
                    $checkout_summary .= "Product not found for prodId: $prodId<br>";
                }
            } else {
                $checkout_summary .= "Cart item not found for cartId: $cartId<br>";
            }
        }

        $checkout_summary .= "<h3 class='total'>Order reference number: " . $order_reference_number;
        $checkout_summary .= "<h3 class='total'>Total: $" . number_format($subtotal, 2) . "</h3>";
    } else {
        // Handle case where no items are selected
        $checkout_summary .= "No items selected for checkout.<br>";
    }
} else {
    $checkout_summary .= "Form not submitted.<br>";
}
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
    <style>
        /* Additional CSS for checkout summary */
        .checkout-summary {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .checkout-summary h3 {
            margin-top: 0;
            color: #333;
        }

        .checkout-summary img {
            max-width: 150px;
            max-height: 150px;
            margin-right: 20px;
            float: left;
        }

        .checkout-summary p {
            margin: 5px 0;
            color: #555;
        }

        .checkout-summary .total {
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
        }

        .checkout-summary .product-details {
            overflow: hidden;
        }

        .checkout-summary .product-details:after {
            content: "";
            display: table;
            clear: both;
        }

        .checkout-summary .product {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .checkout-summary .product:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .card {
            padding: 5rem;
            background-color: #e5f2ff;
            border-color: #555;
            border-radius: 10px;
        }

        .paymentChoices {
            padding: 1rem;
        }

        #placeOrder {
            padding: 1rem;
            border-radius: 5px;
            border-color: orangered;
            background-color: orangered;
            margin-top: 2rem;
        }

        #placeOrder:hover {
            background-color: #ffb380;
        }

        .gcash-form {
            display: none;
            margin-top: 20px;
            background-color: #b3d9ff;
            width: fit-content;
            padding: 1em;
            border-radius: 20px;
            border: 2px solid #66b3ff;
        }

        .gcash-form input {
            border-color: #66b3ff;
        }

        .cod-form {
            display: none;
            margin-top: 20px;
            background-color: #b3d9ff;
            width: fit-content;
            padding: 1em;
            border-radius: 20px;
            border: 2px solid #66b3ff;
        }

        .gcash-form input, .cod-form input {
            margin-bottom:10px;
            height: 30px;
            width: 300px;
            border-radius: 10px;
            padding: 1rem;
        }
        .gcash-form h4, .cod-form h4 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <section id="header">
        <a href="#"><img src="../img/logo.png" class="logo" alt=""></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a class="active" href="shop.php">Shop</a></li>
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

    <section id="page-header" class="about-header">
        <h2>#checkout</h2>
        <p>Complete your purchase!</p>
    </section>

    <section id="checkout" class="section-p1">
        <!-- Checkout summary -->
        <div class="checkout-summary">
            <?php echo $checkout_summary; ?>
        </div>

        <!-- Payment method selection -->
        <div class="card">
            <h3>Choose Payment Method</h3>
            <div class="paymentChoices">
                <form action="../request/checkout_process.php" method="POST">
                    <input type="radio" name="f_payment_method" value="GCash" id="gcash" checked> Gcash<br>
                    <input type="radio" name="f_payment_method" value="COD" id="cod"> Cash On Delivery<br>
            </div>

            <!-- Gcash Form -->
            <div class="gcash-form" id="gcash-form">
                <h4>Gcash Payment Details</h4>
                <label for="gcash-phone">Phone Number:</label><br>
                <input type="text" id="gcash-phone" name="phone" required><br>
                <label for="gcash-reference">Transaction Reference Number:</label><br>
                <input type="text" id="gcash-reference" name="reference" required><br>
                <label for="gcash-name">Account Name:</label><br>
                <input type="text" id="gcash-name" name="name" required><br>
                <label for="account_number">Account Number:</label><br>
                <input type="text" id="account_number" name="account_number" required><br>
                <label for="gcash-address">Address:</label><br>
                <input type="text" id="gcash-address" name="address" required><br>
                <input type="hidden" name="order_reference_number" value="<?php echo $order_reference_number; ?>">
            </div>

            <!-- Gcash Form -->
            <div class="cod-form" id="cod-form">
                <h4>COD Payment Details</h4>
                <label for="phone">Phone Number:</label><br>
                <input type="text" id="phone" name="phone" required><br>
                <label for="gcash-address">Address:</label><br>
                <input type="text" id="gcash-address" name="address" required><br>
                <input type="hidden" name="order_reference_number" value="<?php echo $order_reference_number; ?>">
            </div>

            <input type="hidden" name="selected_items" value="<?php echo json_encode($selected_items); ?>">
            <button id="placeOrder" type="submit" class="btn btn-primary">Place Order</button>
        </form>
        </div>
    </section>

    <script>
  document.addEventListener("DOMContentLoaded", function() {
    const gcashRadio = document.getElementById('gcash');
    const codRadio = document.getElementById('cod');
    const gcashForm = document.getElementById('gcash-form');
    const codForm = document.getElementById('cod-form');

    function toggleForm() {
        if (gcashRadio.checked) {
            gcashForm.style.display = 'block';
            enableRequiredFields(gcashForm);
            codForm.style.display = 'none';
            disableRequiredFields(codForm);
        } else {
            codForm.style.display = 'block';
            enableRequiredFields(codForm);
            gcashForm.style.display = 'none';
            disableRequiredFields(gcashForm);
        }
    }

    // Enable required fields
    function enableRequiredFields(form) {
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            field.removeAttribute('disabled');
        });
    }

    // Disable required fields
    function disableRequiredFields(form) {
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            field.setAttribute('disabled', 'disabled');
        });
    }

    // Initial check on page load
    toggleForm();

    // Event listeners for radio buttons
    gcashRadio.addEventListener('change', toggleForm);
    codRadio.addEventListener('change', toggleForm);
});

    </script>

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
            <p>GCash Number: +639 912 3456 789</p>
        </div>

        <div class="copyright">
            <p>© 2024, Ckaaf Clothing Shop</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>