<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../connection.php";  // Adjust the path based on your actual directory structure

    // Retrieve user input from the registration form
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = htmlspecialchars($_POST['role']);

    // Check if the username or email already exists in the database
    $checkExistingUser = "SELECT username, email FROM users WHERE username = ? OR email = ?";
    $stmtCheck = $conn->prepare($checkExistingUser);
    $stmtCheck->bind_param("ss", $username, $email);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        // Username or email already exists
        $error_message = "Username or email already exists. Please choose a different one.";
        header("Location: ../register.html?error=$error_message");
        exit;
    }

    // Prepare and execute the SQL query to insert data into the database
    $insertUser = "INSERT INTO users (firstname, lastname, username, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($insertUser);
    $stmtInsert->bind_param("sssss", $firstname, $lastname, $username, $email, $password);
    
    if ($stmtInsert->execute()) {
        // Registration successful, redirect to login page
        header("Location: ../login.php");
        exit;
    } else {
        // Registration failed
        $error_message = "Registration failed. Please try again.";
        header("Location: ../register.html?error=$error_message");
        exit;
    }

    $stmtCheck->close();
    $stmtInsert->close();
    $conn->close();
} else {
    // Redirect to the registration form if the request method is not POST
    $error_message = "Invalid request method.";
    header("Location: ../register.html?error=$error_message");
    exit;
}
?>
    