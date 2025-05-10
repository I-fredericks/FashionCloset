<?php
session_start();
include 'db_connection.php'; // Include database connection

// Ensure the user is logged in
if (!isset($_SESSION['userEmail'])) {
    header("Location: login.php");
    exit();
}

$userEmail = $_SESSION['userEmail'];

// Fetch user details using the email
$stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$userResult = $stmt->get_result();

if ($userResult->num_rows > 0) {
    $user = $userResult->fetch_assoc();
    $userID = $user['UserID']; // Assuming UserID is the primary key in the Users table
} else {
    echo "User not found.";
    exit();
}

// Fetch user orders using the UserID
$ordersStmt = $conn->prepare("
    SELECT o.OrderID, o.TotalAmount, o.Status, o.OrderDate, p.ProductName, oi.Quantity, oi.TotalPrice
    FROM Orders o
    JOIN Order_Items oi ON o.OrderID = oi.OrderID
    JOIN Products p ON oi.ProductID = p.ProductID
    WHERE o.UserID = ?
    ORDER BY o.OrderDate DESC
");
$ordersStmt->bind_param("i", $userID);
$ordersStmt->execute();
$ordersResult = $ordersStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    /* Same styles as your original code */
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }

    header {
      background: #232f3e;
      color: white;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    header h1 {
      margin: 0;
      font-size: 1.8rem;
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

    .container {
      max-width: 1200px;
      margin: 30px auto;
      padding: 20px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .container h2 {
      font-size: 1.8rem;
      margin-bottom: 20px;
      color: #232f3e;
    }

    .section {
      margin-bottom: 40px;
    }

    .section h3 {
      font-size: 1.5rem;
      margin-bottom: 15px;
      color: #232f3e;
    }

    .account-details, .order-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .account-details p, .order-list p {
      margin: 0;
      padding: 10px;
      background: #f9f9f9;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    .btn-logout {
      display: inline-block;
      padding: 10px 20px;
      background: #ff9900;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      text-align: center;
      transition: background-color 0.3s;
    }

    .btn-logout:hover {
      background: #e68a00;
    }

    footer {
      text-align: center;
      padding: 15px;
      background: #232f3e;
      color: #fff;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <header>
    <h1>Welcome, <?php echo htmlspecialchars($user['UserName']); ?>!</h1>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="login.php" class="btn-logout">Logout</a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <div class="section">
      <h2>Account Details</h2>
      <div class="account-details">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['UserName']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
        <p><strong>Joined On:</strong> <?php echo htmlspecialchars($user['CreatedAt']); ?></p>
      </div>
    </div>

    <div class="section">
      <h2>Your Orders</h2>
      <div class="order-list">
        <?php
        if ($ordersResult->num_rows > 0) {
            while ($order = $ordersResult->fetch_assoc()) {
                echo "
                <p>
                  <strong>Order ID:</strong> {$order['OrderID']}<br>
                  <strong>Product:</strong> {$order['ProductName']}<br>
                  <strong>Quantity:</strong> {$order['Quantity']}<br>
                  <strong>Total Price:</strong> \${$order['TotalPrice']}<br>
                  <strong>Status:</strong> {$order['Status']}<br>
                  <strong>Order Date:</strong> {$order['OrderDate']}
                </p>";
            }
        } else {
            echo "<p>You have no orders yet.</p>";
        }
        ?>
      </div>
    </div>

    <div class="section">
      <h2>Quick Links</h2>
      <a href="shop.php" class="btn-logout">Shop Now</a>
    </div>
  </div>

  <footer>
    <p>&copy; 2024 Fashion Closet. All rights reserved.</p>
  </footer>
</body>
</html>
