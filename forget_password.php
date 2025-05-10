<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Include database connection file (update path as needed)
require_once 'db_connection.php';

// Define variables
$email = "";
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        try {
            // Check if email exists in the database
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Generate a unique reset token
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Save the token and expiration in the database
                $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expires_at = ?");
                $stmt->bind_param('sssss', $email, $token, $expires, $token, $expires);
                $stmt->execute();

                // Send reset link via email
                $resetLink = "https://youramazonewebsite.com/reset_password.php?token=$token";
                $subject = "Password Reset Request";
                $message = "Hello,\n\nClick the link below to reset your password:\n$resetLink\n\nIf you did not request this, please ignore this email.";
                $headers = "From: no-reply@youramazonewebsite.com";

                if (mail($email, $subject, $message, $headers)) {
                    $_SESSION['success'] = "A password reset link has been sent to your email.";
                    header('Location: forgot_password.php');
                    exit;
                } else {
                    $error = "Failed to send the reset email. Please check your email configuration.";
                }
            } else {
                $error = "No account found with that email address.";
            }

            $stmt->close();
        } catch (Exception $e) {
            $error = "An error occurred: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f3f3;
            color: #333;
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
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #232f3e;
            font-size: 1.8em;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 1em;
            color: #555;
        }

        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #ff9900;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
        }

        button:hover {
            background-color: #e68a00;
        }

        .success {
            color: #28a745;
            font-size: 0.9em;
            margin-bottom: 15px;
        }

        .error {
            color: #d9534f;
            font-size: 0.9em;
            margin-bottom: 15px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header>
  <h1>Anna's Closet</h1>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="shop.php">Shop</a></li>
      <li><a href="about.html">About</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
  </nav>
</header>
    <div class="container">
        <h2>Forgot Password</h2>

        <?php if (!empty($_SESSION['success'])): ?>
            <p class="success"> <?= $_SESSION['success'] ?> </p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p class="error"> <?= $error ?> </p>
        <?php endif; ?>

        <form action="forgot_password.php" method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required>
            </div>

            <button type="submit">Send Reset Link</button>
        </form>
    </div>
</body>
</html>
