<?php
session_start();
include 'db.php';  // Ensure the 'db.php' file contains a working database connection

// Get the login type (customer) from the URL parameter
$type = isset($_GET['type']) ? $_GET['type'] : null;

if ($type) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Handle Customer Login
        if ($type == 'customer') {
            // Prepare and bind the query for customer login
            $query = "SELECT * FROM customers WHERE email=?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 's', $email);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $user = mysqli_fetch_assoc($result);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    header("Location: customer_dashboard.php");
                    exit();
                } else {
                    echo "Invalid customer credentials!";
                }
            } else {
                echo "Error: " . mysqli_error($conn); // Display SQL error if query fails
            }
        }
    }
} else {
    echo "No login type specified!"; // Show this message if no type is specified
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo ucfirst($type); ?> Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1><?php echo ucfirst($type); ?> Login</h1> <!-- Dynamic Heading Based on Login Type -->
        
        <!-- Display login form -->
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        
        <!-- Only display sign-up link for customers, not managers -->
        <?php if ($type == 'customer') { ?>
            <a href="signup.php">Don't have an account? Sign up</a>
        <?php } ?>
    </div>
</body>
</html>
