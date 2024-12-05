<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM customers WHERE id=$user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $user['name']; ?>!</h1>
        <p>Account Number: <?php echo $user['account_number']; ?></p>
        <p>Balance: $<?php echo $user['balance']; ?></p>
        <a href="withdraw.php">Withdraw Money</a> | 
        <a href="deposit.php">Deposit Money</a> | 
        <a href="transfer.php">Transfer Money</a> | 
        <a href="loan_request.php">Request Loan</a> | 
        <a href="transaction_history.php">Transaction History</a> | 
        <a href="customer_profile.php">Edit Profile</a> | 
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
