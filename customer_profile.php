<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Update profile
    $query = "UPDATE customers SET name='$name', email='$email' WHERE id=$user_id";
    mysqli_query($conn, $query);
    
    echo "Profile updated successfully!";
}

$query = "SELECT * FROM customers WHERE id=$user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <form method="POST">
            <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            <button type="submit">Update Profile</button>
        </form>
        <a href="customer_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
