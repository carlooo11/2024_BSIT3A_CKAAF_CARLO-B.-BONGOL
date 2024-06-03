<?php
// Include the connection file
include "../connection.php";

session_start();
$_SESSION['selectedItems'] = [];

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['user_id'])) {
    // Retrieve the userId from the session
    $user_id = $_SESSION['user_id'];
}

// Fetch cart items from the database
$sql_fetch_cart_items = "SELECT * FROM carts WHERE userId = '$user_id'";
$result_cart_items = mysqli_query($conn, $sql_fetch_cart_items);
?>

<!DOCTYPE html> 
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
        <h2>#cart</h2>
        <p>Add your coupon code & SAVE up to 70%!</p>
    </section>

    <section id="cart" class="section-p1">
        <?php
        if (isset($_GET['status'])) {
            echo "<p style='color: green;'>" . htmlspecialchars($_GET['status']) . "</p>";
        }
        ?>
        <form id="cart-form" action="checkout.php" method="post">
            <table width="100%">
                <thead>
                    <tr>
                        <td>Remove</td>
                        <td>Image</td>
                        <td>Product</td>
                        <td>Size</td>
                        <td>Price</td>
                        <td>Quantity</td>
                        <td>Subtotal</td>
                        <td>Check</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if there are any cart items found
                    if (mysqli_num_rows($result_cart_items) > 0) {
                        // Loop through fetched cart items and display them
                        while ($row = mysqli_fetch_assoc($result_cart_items)) {
                            // Fetch additional product details if needed
                            $item_id = $row['prodId'];
                            $cart_Id = $row['cartId'];
                            $sql_fetch_product = "SELECT * FROM products WHERE prodId = '$item_id'";
                            $result_product = mysqli_query($conn, $sql_fetch_product);
                            $product = mysqli_fetch_assoc($result_product);

                            $subtotal = $row['quantity'] * $product['price'];
                    ?>

                            <tr>
                                <td><a href="../request/deleteRecord.php?cartId=<?php echo $cart_Id; ?>&this_page=user/cart.php" class="delete_btn" style="text-decoration:none; background-color:brown; padding:1rem; color:white; border-radius:8px;">Delete</a></td>
                                <td><img src="data:image/jpg;base64,<?php echo base64_encode($product['prodpic']); ?>" /></td>
                                <td><?php echo $product['prodname']; ?></td>
                                <td><?php echo $row['size'];?></td>
                                <td>$<span class="price"><?php echo $product['price']; ?></span></td>
                                <td><input type="number" value="<?php echo $row['quantity']; ?>" name="quantities[<?php echo $cart_Id; ?>]" min="1" class="quantity" data-price="<?php echo $product['price']; ?>" style="height:30px; width:80px;"></td>
                                <td class="subtotal">$<?php echo number_format($subtotal, 2); ?></td>
                                <td><input type="checkbox" name="selected_items[]" value="<?php echo $cart_Id; ?>" class="item-check" data-subtotal="<?php echo number_format($subtotal, 2); ?>" style="height: 25px; width: 25px;"></td>
                            </tr>
                    <?php
                        }
                    } else {
                        // Display a message if no products are found in the cart
                        echo "<tr><td colspan='8'>No products added to cart are found.</td></tr>";
                    }
                    ?>
                        <tr><td colspan='8'>-----------------------------------------</td></tr>
                        <?php if (mysqli_num_rows($result_cart_items) > 0) : ?>
                            <tr>
                                <td colspan='8'><h3>Total: $<span id="total-price">0.00</span></h3></td>
                            </tr>
                            <tr>
                                <input type="hidden" name="selected_items_json" id="selected_items_json">
                                <td colspan='8'><button type="submit" class="checkout_btn" style="margin-top: 20px; padding: 10px 20px; background-color: green; color: white; border: none; border-radius: 8px;">Check out</button></td>
                            </tr>
                        <?php endif; ?>

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

    <script>
    // Function to update the total price based on selected items
    function updateTotalPrice() {
        let totalPrice = 0;
        document.querySelectorAll('.item-check:checked').forEach(item => {
            const subtotal = parseFloat(item.getAttribute('data-subtotal').replace(/,/g, ''));
            totalPrice += subtotal;
        });
        document.getElementById('total-price').innerText = totalPrice.toFixed(2);
    }

    // Add event listeners to checkboxes and quantity inputs
    document.querySelectorAll('.item-check').forEach(checkbox => {
        checkbox.addEventListener('change', updateTotalPrice);
    });

    document.querySelectorAll('.quantity').forEach(quantityInput => {
        quantityInput.addEventListener('change', function() {
            const price = parseFloat(this.getAttribute('data-price').replace(/,/g, ''));
            const quantity = parseFloat(this.value);
            const subtotal = price * quantity;
            this.closest('tr').querySelector('.subtotal').innerText = subtotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            
            // Update the subtotal data attribute of the checkbox
            const checkbox = this.closest('tr').querySelector('.item-check');
            checkbox.setAttribute('data-subtotal', subtotal.toFixed(2));

            updateTotalPrice();
        });
    });

    // Initialize total price on page load
    document.addEventListener('DOMContentLoaded', updateTotalPrice);


    document.getElementById('cart-form').addEventListener('submit', function(event) {
        const selectedItems = [];
        document.querySelectorAll('.item-check:checked').forEach(checkbox => {
            selectedItems.push(checkbox.value);
        });
        document.getElementById('selected_items_json').value = JSON.stringify(selectedItems);
    });

</script>


    <script src="script.js"></script>
</body>

</html>

