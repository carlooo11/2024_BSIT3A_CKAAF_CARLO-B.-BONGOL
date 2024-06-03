<?php
// Include the connection file
include "../connection.php";

session_start();

if (isset($_POST['selected_items'])) {
    $selected_items = $_POST['selected_items'];
    $orderTotal = 0;

    // Prepare statements for inserting into orders and reducing stock
    $sql_insert_order = "INSERT INTO orders (userId, prodId, quantity, totalPrice) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert_order);

    $sql_reduce_stock = "UPDATE products SET stock = stock - ? WHERE prodId = ?";
    $stmt_reduce = $conn->prepare($sql_reduce_stock);

    // Process each selected item
    foreach ($selected_items as $cartId) {
        // Fetch the details of each selected cart item
        $sql_fetch_cart_item = "SELECT * FROM carts WHERE cartId = ?";
        $stmt = $conn->prepare($sql_fetch_cart_item);
        $stmt->bind_param("i", $cartId);
        $stmt->execute();
        $result_cart_item = $stmt->get_result();
        
        if ($result_cart_item->num_rows > 0) {
            while ($cart_item = $result_cart_item->fetch_assoc()) {
                $userId = $cart_item['userId'];
                $prodId = $cart_item['prodId'];
                $quantity = $cart_item['quantity'];
                $totalPrice = $cart_item['totalPrice'];

                // Insert into orders table
                $stmt_insert->bind_param("iiid", $userId, $prodId, $quantity, $totalPrice);
                $stmt_insert->execute();

                // Reduce stock in products table
                $stmt_reduce->bind_param("ii", $quantity, $prodId);
                $stmt_reduce->execute();

                // Add to order total
                $orderTotal += $totalPrice;

                // Optionally, delete the item from the cart
                $sql_delete_cart_item = "DELETE FROM carts WHERE cartId = ?";
                $stmt_delete = $conn->prepare($sql_delete_cart_item);
                $stmt_delete->bind_param("i", $cartId);
                $stmt_delete->execute();
            }
        }
    }

    // Close prepared statements
    $stmt_insert->close();
    $stmt_reduce->close();

    // Redirect to a success page or the same cart page with a success message
    header("Location: cart.php?status=Order placed successfully! Total: $" . number_format($orderTotal, 2));
    exit();
} else {
    // No items were selected
    header("Location: cart.php?status=No items selected for order.");
    exit();
}

// Close the connection
$conn->close();
?>
