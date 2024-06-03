<?php
// Include the connection file
include "../connection.php";

session_start();

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the checkout button was clicked and selected items are provided
if (isset($_POST['checkout']) && isset($_POST['selected_items'])) {
    $selected_items = $_POST['selected_items'];
    error_log("Selected items: " . print_r($selected_items, true));

    // Initialize total price for the selected items
    $totalPrice = 0;

    // Generate a unique order reference number
    $order_reference_number = uniqid('order_', true);

    // Here you can process the selected items for checkout
    foreach ($selected_items as $cartId) {
        // Fetch the cart item details
        $sql_fetch_cart_item = "SELECT * FROM carts WHERE cartId = '$cartId'";
        $result_cart_item = mysqli_query($conn, $sql_fetch_cart_item);
        $cart_item = mysqli_fetch_assoc($result_cart_item);

        // Extract necessary details for the order
        $userId = $cart_item['userId'];
        $prodId = $cart_item['prodId'];
        $quantity = $cart_item['quantity'];
        $item_totalPrice = $cart_item['totalPrice'];

        // Add the item's total price to the overall total price
        $totalPrice += $item_totalPrice;

        // Insert the order into the orders table
        $sql_insert_order = "INSERT INTO orders (order_reference_number, userId, totalPrice, orderStatus, prodId, quantity) VALUES (?, ?, ?, 'Pending', ?, ?)";
        $stmt = $conn->prepare($sql_insert_order);
        $stmt->bind_param("siidii", $order_reference_number, $userId, $totalPrice, $prodId, $quantity);

        if ($stmt->execute()) {
            // Delete the item from the cart
            $sql_delete_cart_item = "DELETE FROM carts WHERE cartId = ?";
            $stmt_delete = $conn->prepare($sql_delete_cart_item);
            $stmt_delete->bind_param("i", $cartId);
            $stmt_delete->execute();
            $stmt_delete->close();
        }

        $stmt->close();
    }

    // Redirect to the orders page with a success message
    header("Location: ../user/orders.php?status=" . urlencode("Selected items have been successfully checked out.") . "&order_reference_number=" . urlencode($order_reference_number) . "&total_price=" . urlencode($totalPrice));
    exit();
} else {
    $message = "No items selected for checkout.";
    // Redirect back to the cart page with a status message
    header("Location: ../user/cart.php?status=" . urlencode($message));
    exit();
}

// Close the connection
$conn->close();
?>
