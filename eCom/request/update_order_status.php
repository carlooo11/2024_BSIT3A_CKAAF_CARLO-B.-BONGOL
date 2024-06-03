<?php
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    if (isset($_POST['order_status'])) {
      $order_status = $_POST['order_status'];
      $query = "UPDATE orders SET order_status = '$order_status' WHERE order_id = '$order_id'";
      mysqli_query($conn, $query);
    }

    if (isset($_POST['cancel_status'])) {
      $cancel_status = $_POST['cancel_status'];
      $query = "UPDATE orders SET cancel_status = '$cancel_status' WHERE order_id = '$order_id'";
      mysqli_query($conn, $query);
    }
  }

  mysqli_close($conn);
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>
