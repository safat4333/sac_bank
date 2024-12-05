<?php
session_start();
include_once('db.php'); // Include your database connection file

// Check if the customer is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM transaction_history WHERE customer_id = ?";
$stmt = $conn->prepare($query);


if ($stmt === false) {
    // If there's an error with preparing the query, output the error and stop execution
    die('Error preparing the query: ' . $conn->error);
}

$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the query execution returned results
if ($result->num_rows > 0) {
    // Process the result if there are transactions
} else {
    // Handle the case where there are no transactions
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Transaction History</h1>
        <p>Logged in as: <?php echo $_SESSION['user_name']; ?></p>
        <a href="customer_dashboard.php">Back to Dashboard</a>

        <h2>Transactions</h2>
        
        <?php if ($result->num_rows > 0) { ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($transaction = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $transaction['id']; ?></td>
                            <td><?php echo $transaction['type']; ?></td>
                            <td><?php echo '$' . number_format($transaction['amount'], 2); ?></td>
                            <td><?php echo $transaction['date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else {
            echo "<p>No transactions found</p>";
        } ?>
    </div>
</body>
</html>
