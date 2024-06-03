<?php
session_start();

include '../connection.php';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // If not logged in, redirect to login page or handle accordingly
    header("Location: ../login.php");
    exit();
}

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Prepare statement to prevent SQL injection
$sql_fetch_user_type = $conn->prepare("SELECT userType FROM users WHERE user_id = ?");
$sql_fetch_user_type->bind_param("i", $user_id);
$sql_fetch_user_type->execute();
$result_user_type = $sql_fetch_user_type->get_result();

// Check if user was found and fetch userType
if ($result_user_type->num_rows > 0) {
    $row = $result_user_type->fetch_assoc();
    $userType = $row['userType'];

    // Determine the user type and redirect accordingly
    if ($userType === 'admin') {
        header("Location: ../admin/notloggedin.html");
    } elseif ($userType === 'client') {
        header("Location: ../cart.php");
    } else {
        // Handle unexpected user type
        echo "Unknown user type.";
    }
    exit();
} else {
    // Handle case where user was not found
    echo "User not found.";
}

// Close statement and connection
$sql_fetch_user_type->close();
$conn->close();
?>
