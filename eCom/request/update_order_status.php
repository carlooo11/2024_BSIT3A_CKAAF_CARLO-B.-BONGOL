<?php
// Include the database connection
include "../connection.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the orderId is set in the POST data
    if (isset($_POST['orderId'])) {
        // Sanitize the input data to prevent SQL injection
        $orderId = mysqli_real_escape_string($conn, $_POST['orderId']);

        // Initialize the base update query and an array to store the query parts
        $updateParts = [];

        // Check if orderStatus is set in the POST data
        if (isset($_POST['orderStatus'])) {
            $orderStatus = mysqli_real_escape_string($conn, $_POST['orderStatus']);
            $updateParts[] = "orderStatus = '$orderStatus'";
        }

        // Check if the shipping tracking number is set in the POST data
        if (isset($_POST['shipnum'])) {
            $shipnum = mysqli_real_escape_string($conn, $_POST['shipnum']);
            $updateParts[] = "shipping_tracking_number = '$shipnum'";
        }

        // Check if payment status is set in the POST data
        if (isset($_POST['payment'])) {
            $paid = mysqli_real_escape_string($conn, $_POST['payment']);
            $updateParts[] = "payment_status = '$paid'";
        }

        // Check if cancel status is set in the POST data
        if (isset($_POST['cancel_status'])) {
            $stat = mysqli_real_escape_string($conn, $_POST['cancel_status']);
            $updateParts[] = "cancel_request = '$stat'";
            
            if($cstat == 'Approve') {
                $updateParts[] = "orderStatus = 'Cancelled'";
            } elseif ($cstat == 'Reject') {
                $updateParts[] = "orderStatus = 'Pending'";
            }
        }

        // Only proceed if there are fields to update
        if (!empty($updateParts)) {
            // Combine the update parts into a single string
            $updateQuery = "UPDATE orders SET " . implode(", ", $updateParts);
            $updateQuery .= " WHERE order_reference_number = '$orderId'";

            // Execute the update query
            $updateResult = $conn->query($updateQuery);

            // Check if the update was successful
            if ($updateResult) {
                // Redirect to the previous page
                header("Location: {$_SERVER['HTTP_REFERER']}");
                exit(); // Stop executing the script after redirection
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update order status.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No fields to update.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing orderId in POST data.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
