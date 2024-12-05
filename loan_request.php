<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM customers WHERE id=$user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loan_amount = $_POST['loan_amount'];

    // Check if the loan amount is valid
    if ($loan_amount <= 0) {
        $error_message = "Please enter a valid loan amount.";
    } else {
        // Insert loan request into the loans table
        $insert_query = "INSERT INTO loans (customer_id, loan_amount) VALUES ($user_id, $loan_amount)";
        if (mysqli_query($conn, $insert_query)) {
            $success_message = "Loan request submitted successfully. Your request is pending approval.";
        } else {
            $error_message = "Failed to submit loan request. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loan Request</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Loan Request</h1>
        <p>Welcome, <?php echo $user['name']; ?>!</p>

        <?php if (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        } ?>
        <?php if (isset($success_message)) {
            echo "<p style='color: green;'>$success_message</p>";
        } ?>

        <form method="POST" action="">
            <label for="loan_amount">Enter Loan Amount:</label>
            <input type="number" name="loan_amount" id="loan_amount" required>
            <button type="submit">Request Loan</button>
        </form>

        <br>
        <a href="customer_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
