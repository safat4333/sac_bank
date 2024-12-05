<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $account_number = "AC" . rand(100000, 999999);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO customers (name, email, password, account_number) VALUES ('$name', '$email', '$hashed_password', '$account_number')";
    mysqli_query($conn, $query);

    echo "Account created successfully! You can now log in.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Create an Account</h1>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <a href="login.php">Already have an account? Login</a>
        
        <!-- New Home button -->
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <form method="get" action="index.php">
                <button type="submit">Go to Home</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
