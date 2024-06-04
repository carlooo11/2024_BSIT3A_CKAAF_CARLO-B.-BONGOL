<?php
// Include the connection file
include "../connection.php";

session_start();

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

if (isset($_GET['prodId']) && isset($_GET['quantity']) && isset($_GET['this_page'])) {
    $item_id = $_GET['prodId'];
    $prev_page = $_GET['this_page'];
    $item_qty = $_GET['quantity'] == 0 ? 1 : $_GET['quantity'];

    // Fetch additional product information from the products table
    $sql_fetch_product_info = "SELECT prodname, price FROM products WHERE prodId = '$item_id'";
    $result_product_info = mysqli_query($conn, $sql_fetch_product_info);

    if ($result_product_info && mysqli_num_rows($result_product_info) > 0) {
        $product_info = mysqli_fetch_assoc($result_product_info);
        $prodname = $product_info['prodname'];
        $price = $product_info['price'];
    }

    $sql_add_to_cart = "INSERT INTO `carts` (`userId`, `prodId`, `price`, `prodname`, `quantity`) VALUES ('$userId', '$item_id', '$price', '$prodname', '$item_qty')";
    $execute_cart = mysqli_query($conn, $sql_add_to_cart);

    if ($execute_cart) {
        $status = "Item added to cart successfully";
        header("Location: $prev_page?status=" . urlencode($status));
        exit();
    } else {
        echo "Error adding item to cart: " . mysqli_error($conn);
    }
}
?>
