<?php
session_start();
include 'db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userType = $_POST['userType']; // Fetch the user type (admin or user)
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Determine table and dashboard based on user type
    $table = $userType === 'admin' ? 'Admins' : 'Users';
    $dashboard = $userType === 'admin' ? 'admin_dashboard.php' : 'user_dashboard.php';

    // Prepare the query
    $stmt = $conn->prepare("SELECT * FROM $table WHERE Email = ?");
    if (!$stmt) {
        die("Database query failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['PasswordHash'])) {
            // Set session variables
            $_SESSION['userID'] = $user['AdminID'] ?? $user['UserID'];
            $_SESSION['userEmail'] = $user['Email']; // Store email for fetching orders
            $_SESSION['userType'] = $userType;

            // Redirect to the respective dashboard
            header("Location: $dashboard");
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "Invalid email. Please check your login details.";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background: url('login-background(1).jpg') no-repeat center center fixed;
      background-size: cover;
    }

    header {
      background: #232f3e;
      color: #fff;
      padding: 10px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    header h1 {
      margin: 0;
      font-size: 1.5rem;
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

    .content {
      display: flex;
      justify-content: center;
      align-items: center;
      height: calc(100vh - 80px);
      padding: 20px;
    }

    .login-container {
      background: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      width: 400px;
    }

    .login-container h1 {
      font-size: 1.5rem;
      color: #232f3e;
      margin-bottom: 20px;
      text-align: center;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-size: 1rem;
      margin-bottom: 5px;
      color: #333;
    }

    .form-group select,
    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
    }

    .btn-login {
      padding: 10px 20px;
      font-size: 1rem;
      color: #fff;
      background-color: #232f3e;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
      width: 100%;
    }

    .btn-login:hover {
      background-color: #1d2936;
    }

    .error {
      color: #dc3545;
      margin-top: 10px;
      text-align: center;
    }
  </style>
</head>
<body>
<header>
  <h1>Fashion Closet</h1>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="shop.php">Shop</a></li>
      <li><a href="about.html">About</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
  </nav>
</header>

<div class="content">
  <div class="login-container">
    <h1>Login</h1>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form action="login.php" method="POST">
      <div class="form-group">
        <label for="userType">Login as</label>
        <select name="userType" id="userType" required>
          <option value="user">Customer</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div class="form-group">
        <label for="username">Email</label>
        <input type="email" name="username" id="username" placeholder="Enter your email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn-login">Login</button>
    </form>
    <div class="login-links">
      <a href="create_account.php">Create Account</a>
      <a href="forget_password.php">Forgot Password?</a>
    </div>
  </div>
</div>
</body>
</html>
