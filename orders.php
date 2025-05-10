<?php
session_start();
include 'db_connection.php';

// Check if user is admin
if ($_SESSION['userType'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all orders
$query = "SELECT * FROM Orders ORDER BY OrderDate DESC";
$result = $conn->query($query);

// Handle order confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    $orderID = $_POST['order_id'];
    $stmt = $conn->prepare("UPDATE Orders SET Status = 'Confirmed' WHERE OrderID = ?");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $stmt->close();
    header("Location: orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Anna's Closet</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        header {
            background-color: #232f3e;
            color: #fff;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo-container {
            display: flex;
            align-items: center;
        }
        .logo-img {
            max-height: 50px;
            margin-right: 15px;
        }
        .logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ff9900;
        }
        nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: 600;
        }
        nav ul li a:hover {
            color: #ff9900;
        }
        .sidebar {
            width: 250px;
            background-color: #232f3e;
            color: white;
            height: 100vh;
            position: fixed;
            padding: 20px;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin-bottom: 15px;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px;
            border-radius: 5px;
            display: block;
            transition: background-color 0.3s ease;
        }
        .sidebar ul li a:hover {
            background-color: #37475a;
        }
        .main-content {
            margin-left: 250px;
            padding: 40px;
        }
        h1 {
            color: #232f3e;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #232f3e;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9e9e9;
        }
        .btn-confirm {
            padding: 8px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-confirm:hover {
            background-color: #218838;
        }
        .status-confirmed {
            color: #28a745;
            font-weight: bold;
        }
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        footer {
            background-color: #232f3e;
            text-align: center;
            padding: 20px;
            font-size: 0.9rem;
            color: whitesmoke;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Anna's Closet</h2>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="orders.php">Manage Orders</a></li>
            <li><a href="report.php">Reports</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Order Management</h1>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Full Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Postal Code</th>
                    <th>Country</th>
                    <th>Transaction ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $order['OrderID']; ?></td>
                        <td><?php echo $order['UserID']; ?></td>
                        <td><?php echo date('M j, Y g:i A', strtotime($order['OrderDate'])); ?></td>
                        <td>$<?php echo number_format($order['TotalAmount'], 2); ?></td>
                        <td><?php echo ucfirst($order['PaymentMethod']); ?></td>
                        <td class="<?php echo $order['Status'] === 'Confirmed' ? 'status-confirmed' : 'status-pending'; ?>">
                            <?php echo $order['Status']; ?>
                        </td>
                        <td><?php echo htmlspecialchars($order['FullName']); ?></td>
                        <td><?php echo htmlspecialchars($order['Address']); ?></td>
                        <td><?php echo htmlspecialchars($order['Phone']); ?></td>
                        <td><?php echo htmlspecialchars($order['City']); ?></td>
                        <td><?php echo htmlspecialchars($order['PostalCode']); ?></td>
                        <td><?php echo htmlspecialchars($order['Country']); ?></td>
                        <td><?php echo $order['TransactionID'] ? htmlspecialchars($order['TransactionID']) : 'N/A'; ?></td>
                        <td>
                            <?php if ($order['Status'] !== 'Confirmed'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['OrderID']; ?>">
                                    <button type="submit" name="confirm_order" class="btn-confirm">Confirm</button>
                                </form>
                            <?php else: ?>
                                <span class="status-confirmed">Confirmed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Anna's Closet. All rights reserved.</p>
    </footer>
</body>
</html>