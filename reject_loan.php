<?php
session_start();
include 'db.php';

if (!isset($_SESSION['manager_email'])) {
    header("Location: manager_login.php");
    exit();
}

if (isset($_GET['loan_id'])) {
    $loan_id = $_GET['loan_id'];

    // Update loan status to 'rejected'
    $query = "UPDATE loans SET loan_status = 'rejected' WHERE id = $loan_id";
    if (mysqli_query($conn, $query)) {
        echo "Loan rejected successfully.";
        header("Location: manager_dashboard.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
