<?php
session_start();
include 'db_connection.php'; // Include database connection

// Fetch all reports from the Messages table
$stmt = $conn->prepare("SELECT * FROM Messages ORDER BY CreatedAt DESC");
$stmt->execute();
$reportsResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Reports - Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
      color: #333;
    }

    header {
      background-color: #232f3e;
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

    header nav ul {
      list-style: none;
      display: flex;
      padding: 0;
      margin: 0;
    }

    header nav ul li {
      margin-left: 20px;
    }

    header nav ul li a {
      color: white;
      text-decoration: none;
      font-weight: bold;
    }

    .container {
      max-width: 1200px;
      margin: 30px auto;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h2 {
      font-size: 1.8rem;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    table th, table td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: left;
    }

    table th {
      background-color: #f2f2f2;
      color: #232f3e;
      font-weight: 600;
    }

    .no-reports {
      text-align: center;
      color: #777;
    }

    footer {
      text-align: center;
      padding: 15px;
      background: #232f3e;
      color: white;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <header>
    <h1>Admin Panel - Reports</h1>
    <nav>
      <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="index.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <h2>All Reports</h2>
    <?php if ($reportsResult->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Report ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($report = $reportsResult->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($report['MessageID']); ?></td>
              <td><?php echo htmlspecialchars($report['Name']); ?></td>
              <td><?php echo htmlspecialchars($report['Email']); ?></td>
              <td><?php echo htmlspecialchars($report['Message']); ?></td>
              <td><?php echo htmlspecialchars($report['CreatedAt']); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="no-reports">No reports found.</p>
    <?php endif; ?>
  </div>

  <footer>
    <p>&copy; 2025 Faashion Closet. All rights reserved.</p>
  </footer>
</body>
</html>
