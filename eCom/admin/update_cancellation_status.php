<?php
include ("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_reference_number = $_POST['order_reference_number'];
    $cancellation_status = $_POST['cancellation_status'];

    $update_query = "UPDATE orders SET cancel_request = ? WHERE order_reference_number = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('ss', $cancellation_status, $order_reference_number);

    if ($stmt->execute()) {
        header('Location: cancellation_requests.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>