<?php
include "../connection.php";
session_start();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['ref_num'])) {
    // Get the order reference number
    $orderReference = $_GET['ref_num'];

    // Update the cancel_status of items under the specified order reference number
    $sql_update_cancel_status = "UPDATE `orders` SET `cancel_request` = 'Pending' WHERE `order_reference_number` = ?";
    $stmt_update = $conn->prepare($sql_update_cancel_status);
    $stmt_update->bind_param("s", $orderReference); // Note: 's' for string

    if ($stmt_update->execute()) {
        $message = "Cancellation request sent for order reference number: $orderReference";
    } else {
        $message = "Error updating cancel status: " . $stmt_update->error;
    }

    $stmt_update->close();
}

$conn->close();

// Redirect back to the previous page with a status message
header("Location: {$_SERVER['HTTP_REFERER']}?status=" . urlencode($message));
exit();
?>
