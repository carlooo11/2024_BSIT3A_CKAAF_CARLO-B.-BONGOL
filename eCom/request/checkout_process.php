<?php
include "../connection.php";
session_start();

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $cartIds = isset($_SESSION['selectedItems']) ? $_SESSION['selectedItems'] : [];
} else {
    // Redirect the user if not logged in
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['f_payment_method'])) {
        // Retrieve form data
        $payment_method = $_POST['f_payment_method'];
        $order_reference_number = $_POST['order_reference_number'];
        $account_number = $_POST['phone'];
        $address = $_POST['address'];
        $quantity = $_POST['quantity'];

        if ($payment_method == 'GCash') {
            $payment_status = 'Completed';
            $account_name = $_POST['name'];
            $account_reference_number = $_POST['reference'];
            $account_number = $_POST['account_number'];
        } else {
            $account_reference_number = "COD";
            $sql_fetch_account_name = "SELECT firstname, lastname FROM users WHERE user_id = '$user_id'";
            $result_account_name = mysqli_query($conn, $sql_fetch_account_name);
            $row = mysqli_fetch_assoc($result_account_name);
            $account_name = $row['firstname'] . " " . $row['lastname'];
            $payment_status = 'Pending';
        }

        // Initialize total price
        $total_price = 0;

        // Begin transaction
        mysqli_begin_transaction($conn);

        // Loop through selected cart IDs
        foreach ($cartIds as $cartId) {
            // Get the cart item details
            $sql_fetch_cart_item = "SELECT * FROM carts WHERE cartId = '$cartId' AND userId = '$user_id'";
            $result_cart_item = mysqli_query($conn, $sql_fetch_cart_item);

            if ($result_cart_item && mysqli_num_rows($result_cart_item) > 0) {
                $cart_item = mysqli_fetch_assoc($result_cart_item);
                $prodId = $cart_item['prodId'];
                $quantity = $cart_item['quantity'];
                $size = $cart_item['size'];

                // Get product details
                $sql_fetch_product = "SELECT * FROM products WHERE prodId = '$prodId'";
                $result_product = mysqli_query($conn, $sql_fetch_product);

                if ($result_product && mysqli_num_rows($result_product) > 0) {
                    $product = mysqli_fetch_assoc($result_product);
                    $price = $product['price'];

                    // Calculate total price
                    $item_total_price = $price * $quantity;
                    $total_price += $item_total_price;

                    // Insert into orders table
                    $sql_insert_order = "INSERT INTO orders (order_reference_number, userId, totalPrice, orderStatus, prodId, quantity, `size`, price, payment_method, phone, account_reference_number, account_name, payment_status, `address`, cancel_request) 
                                                 VALUES ('$order_reference_number', '$user_id', '$item_total_price', 'Pending', '$prodId', '$quantity', '$size', '$price', '$payment_method', '$account_number', '$account_reference_number', '$account_name', '$payment_status', '$address', 'No action')";

                    if (!mysqli_query($conn, $sql_insert_order)) {
                        // Rollback transaction on error
                        mysqli_rollback($conn);
                        die("Error inserting order: " . mysqli_error($conn));
                    }
                } else {
                    // Product not found, rollback transaction and exit
                    mysqli_rollback($conn);
                    die("Product not found");
                }
            } else {
                // Cart item not found, rollback transaction and exit
                mysqli_rollback($conn);
                die("Cart item not found");
            }
        }

        // Delete only the processed cart items
        $cart_ids_string = implode(',', array_map('intval', $cartIds)); // Ensure IDs are integers
        $sql_delete_cart_items = "DELETE FROM carts WHERE cartId IN ($cart_ids_string)";

        if (!mysqli_query($conn, $sql_delete_cart_items)) {
            // Rollback transaction on error
            mysqli_rollback($conn);
            die("Error deleting cart items: " . mysqli_error($conn));
        }

        // Commit transaction if everything is successful
        mysqli_commit($conn);

        // Optionally, you can also unset the session variables if you no longer need them
        unset($_SESSION['selectedItems']);

        // Redirect user to a success page
        header("Location: ../user/my_orders.php");
        exit();
    } else {
        // Payment method not selected
        header("Location: checkout.php");
        exit();
    }
} else {
    // Redirect user if form is not submitted
    header("Location: checkout.php");
    exit();
}
?>
