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
    $receiver_account = $_POST['receiver_account'];

    // Check if the sender has sufficient balance
    $query = "SELECT balance FROM customers WHERE id=$user_id";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user['balance'] >= $amount) {
        // Check if the receiver exists
        $query = "SELECT id, balance FROM customers WHERE account_number='$receiver_account'";
        $result = mysqli_query($conn, $query);
        $receiver = mysqli_fetch_assoc($result);

        if ($receiver) {
            // Update sender's balance
            $new_sender_balance = $user['balance'] - $amount;
            $query = "UPDATE customers SET balance=$new_sender_balance WHERE id=$user_id";
            mysqli_query($conn, $query);

            // Update receiver's balance
            $new_receiver_balance = $receiver['balance'] + $amount;
            $query = "UPDATE customers SET balance=$new_receiver_balance WHERE id=" . $receiver['id'];
            mysqli_query($conn, $query);

            // Insert transaction history for sender
            $query = "INSERT INTO transaction_history (customer_id, type, amount) 
                      VALUES ($user_id, 'Transfer', $amount)";
            mysqli_query($conn, $query);

            // Insert transaction history for receiver
            $query = "INSERT INTO transaction_history (customer_id, type, amount) 
                      VALUES (" . $receiver['id'] . ", 'Transfer', $amount)";
            mysqli_query($conn, $query);

            echo "Transfer successful! Your new balance is $" . $new_sender_balance;
        } else {
            echo "Receiver account not found!";
        }
    } else {
        echo "Insufficient funds!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transfer Money</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Transfer Money</h1>
        <form method="POST">
            <input type="text" name="receiver_account" placeholder="Receiver's Account Number" required>
            <input type="number" name="amount" placeholder="Amount to Transfer" required>
            <button type="submit">Transfer</button>
        </form>
        <a href="customer_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
