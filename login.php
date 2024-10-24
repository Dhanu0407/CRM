<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Default username in XAMPP
$password = ""; // Default password in XAMPP (empty)
$dbname = "stoic_crm_db";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user inputs
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted using the GET method
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Collect and sanitize form data
    $email = sanitize_input($_GET['email']);
    $password = sanitize_input($_GET['password']);

    // Prepare SQL to retrieve user data
    $sql = "SELECT id, password FROM users WHERE email = ?";

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if email exists in the database
    if ($stmt->num_rows > 0) {
        // Bind result variables
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password matches, store login attempt in the logins table
            $login_sql = "INSERT INTO logins (user_id) VALUES (?)";
            $login_stmt = $conn->prepare($login_sql);
            $login_stmt->bind_param("i", $user_id);
            $login_stmt->execute();

            echo "Login successful! Welcome to the platform.";
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No account found with this email!";
    }

    // Close the statements and connection
    $stmt->close();
    $conn->close();
}
?>


