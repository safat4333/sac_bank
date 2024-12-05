<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];

    // Update balance after deposit
    $query = "SELECT balance FROM customers WHERE id=$user_id";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    $new_balance = $user['balance'] + $amount;
    $query = "UPDATE customers SET balance=$new_balance WHERE id=$user_id";
    mysqli_query($conn, $query);

    // Insert transaction history
    $query = "INSERT INTO transaction_history (customer_id, type, amount) 
              VALUES ($user_id, 'Deposit', $amount)";
    mysqli_query($conn, $query);

    echo "Deposit successful! Your new balance is $" . $new_balance;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deposit Money</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Deposit Money</h1>
        <form method="POST">
            <input type="number" name="amount" placeholder="Amount to Deposit" required>
            <button type="submit">Deposit</button>
        </form>
        <a href="customer_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
