<?php
include "../connection.php";
session_start();

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect the user if not logged in
    header("Location: login.php");
    exit();
} 

//fetch completed orders from orders table
$sql_fetch_completed_orders = "SELECT * FROM orders WHERE orderStatus = 'Completed'";
$result_completed_orders = mysqli_query($conn, $fetch_completed_orders);
$row = mysqli_fetch_assoc($result_completed_orders);

$orderRefNum = $row['order_reference_number'];
$orderStatus = $row['orderStatus'];
$orderId = $row['orderId'];
$userId = $row['userId'];
$prodId = $row['prodId'];
$quantity = $row['quantity'];
$totalprice = $row['totalPrice'];
$orderDate = $row['orderDate'];

$sql = "INSERT INTO sales (order_reference_number, orderStatus, orderId, user_id, prodId, quantity, totalPrice, orderDate)
                    VALUES($orderRefNum, $orderStatus, $orderId, $userId, $prodId, $quantity, $totalprice, $orderDate)";
?>
