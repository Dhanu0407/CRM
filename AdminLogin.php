<?php
// Start session to track the admin login status
session_start();

// Include the connection file
require 'connection.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve user input from the form
    $admin_email = $_POST['email'];
    $admin_password = $_POST['password'];

    // Prepare the SQL query to retrieve the admin by email
    $sql = "SELECT * FROM admins WHERE admin_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if admin exists
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Verify the provided password against the stored hashed password
        if (password_verify($admin_password, $admin['admin_password'])) {
            // Set session to indicate admin is logged in
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $admin_email;
            
            // Redirect to admin dashboard or any protected page
            header("Location: index.html");
            exit;
        } else {
            // Invalid password
            header("Location: AdminLogin.html?error=Invalid credentials");
            exit;
        }
    } else {
        // Admin with the given email does not exist
        header("Location: AdminLogin.html?error=Invalid credentials");
        exit;
    }
}
?>
