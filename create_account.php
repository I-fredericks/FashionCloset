<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Email already exists.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO Users (UserName, Email, PasswordHash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Error creating account.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Account</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
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

    .container {
      max-width: 400px;
      margin: 50px auto;
      background-color: white;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }

    .container h2 {
      font-size: 1.5rem;
      margin-bottom: 20px;
      color: #232f3e;
      text-align: center;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-size: 1rem;
      color: #333;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
    }

    .btn-submit {
      width: 100%;
      padding: 10px 20px;
      background-color: #ff9900;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn-submit:hover {
      background-color: #e68a00;
    }

    .footer-links {
      text-align: center;
      margin-top: 20px;
    }

    .footer-links a {
      color: #0073e6;
      text-decoration: none;
      margin: 0 10px;
    }

    .footer-links a:hover {
      text-decoration: underline;
    }

    .error {
      color: #dc3545;
      margin-bottom: 20px;
      text-align: center;
    }
  </style>
</head>
<body>
<header>
<h1>Anna's Closet</h1>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.html">Shop</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </nav>
  </header>
  <div class="container">
    <h2>Create Account</h2>
    <!-- Display error if passed as a query parameter -->
    <?php
    if (isset($_GET['error'])) {
      echo '<p class="error">' . htmlspecialchars($_GET['error']) . '</p>';
    }
    ?>
    <form action="create_account_handler.php" method="POST">
      <div class="form-group">
        <label for="name">Your Name</label>
        <input type="text" name="name" id="name" placeholder="Enter your name" required>
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn-submit">Create Account</button>
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <div class="footer-links">
      <p>Already have an account? <a href="login.php">Sign In</a></p>
    </div>
  </div>
</body>
</html>
