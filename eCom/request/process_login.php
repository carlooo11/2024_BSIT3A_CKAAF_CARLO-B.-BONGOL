<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

// Check if both username and password are provided
if (isset($_POST['uname']) && isset($_POST['pass'])) {
    include "../connection.php";

    // Extract username and password from the form
    $username = $_POST['uname'];
    $password = $_POST['pass'];

    // Validate if username and password are not empty
    if (empty($username) || empty($password)) {
        $error_message = "Username and password are required";
        header("Location: ../login.php?error=$error_message");
        exit;
    } else {
        // Query the database to find the user by username
        $sqlUser = "SELECT * FROM users WHERE username = ?";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->bind_param("s", $username);
        $stmtUser->execute();

        // Retrieve the result set
        $resultUser = $stmtUser->get_result();
        $rowCountUser = $resultUser->num_rows;

        // If a user with the provided username exists
        if ($rowCountUser == 1) {
            $user = $resultUser->fetch_assoc();
            $stored_password = $user['password'];
            $userType = $user['userType']; // Assuming userType is stored in the database

            // Verify the provided password against the stored hashed password
            if (password_verify($password, $stored_password)) {
                // Password is correct for user
                // Set session variables based on userType and redirect accordingly
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['user_id']; 

                if ($userType === 'client') {
                    echo $user_id;
                    header("Location: ../user/index.php");
                } elseif ($userType === 'admin') {
                    echo $user_id;
                    header("Location: ../admin/index.php");
                } else {
                    // Handle other user types if necessary
                    // For example, redirect to a generic page or display an error message
                    header("Location: ../login.php?error=Invalid user type");
                }
                exit;
            } else {
                // Incorrect Password for user
                $error_message = "Incorrect password";
                header("Location: ../login.php?error=$error_message");
                exit;
            }
        } else {
            // Username does not exist
            $error_message = "Username does not exist";
            header("Location: ../login.php?error=$error_message");
            exit;
        }
    }
} else {
    // Redirect to the login page if the form fields are not provided
    header("Location: ../login.php");
    exit;
}
?>
