<?php
session_start();
include_once('db.php'); // Include your database connection file

// Enable MySQLi error reporting for better debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Check if the manager is logged in
if (!isset($_SESSION['manager_email'])) {
    header("Location: manager_login.php"); // Redirect to login if not logged in
    exit();
}

// Handle customer deletion
if (isset($_GET['delete'])) {
    $customer_id = $_GET['delete'];

    // Debug: Check if the ID is being passed correctly
    // echo "Customer ID: " . $customer_id;

    // Check if the customer ID exists in the database
    $check_query = "SELECT * FROM customers WHERE id=?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param('i', $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // SQL query to delete the customer from the database
        $delete_query = "DELETE FROM customers WHERE id=?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param('i', $customer_id);

        if ($stmt->execute()) {
            $message = "Customer deleted successfully.";
        } else {
            $message = "Failed to delete customer. Please try again. Error: " . $stmt->error;
        }
    } else {
        $message = "Customer not found.";
    }
}

// Handle search functionality
$search_query = "";
if (isset($_POST['search'])) {
    $search_term = $_POST['search_term'];
    $search_query = "WHERE name LIKE ? OR email LIKE ?";
    $stmt = $conn->prepare("SELECT * FROM customers $search_query");
    $search_term = "%$search_term%"; // Add wildcards for partial matching
    $stmt->bind_param('ss', $search_term, $search_term);
} else {
    // If no search, show all customers
    $stmt = $conn->prepare("SELECT * FROM customers");
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Welcome to Manager Dashboard</h1>
        <p>Logged in as: <?php echo $_SESSION['manager_email']; ?></p>
        <a href="logout.php">Logout</a>

        <h2>Search for a Customer</h2>
        <form method="POST" action="">
            <input type="text" name="search_term" placeholder="Search by Name or Email" required>
            <button type="submit" name="search">Search</button>
        </form>

        <h2>Customer List</h2>
        
        <?php if (isset($message)) {
            echo "<p style='color: green;'>$message</p>";
        } ?>

        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Balance</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) {
                    while ($customer = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $customer['id']; ?></td>
                            <td><?php echo $customer['name']; ?></td>
                            <td><?php echo $customer['email']; ?></td>
                            <td><?php echo '$' . number_format($customer['balance'], 2); ?></td>
                            <td>
                                <a href="?delete=<?php echo $customer['id']; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this customer?')">Delete</a>
                            </td>
                        </tr>
                    <?php }
                } else {
                    echo "<tr><td colspan='5'>No customers found</td></tr>";
                } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
