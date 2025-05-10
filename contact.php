<?php
include 'db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve input
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Insert the data into the Messages table
    $stmt = $conn->prepare("INSERT INTO Messages (Name, Email, Message, CreatedAt) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        $successMessage = "Your message has been sent successfully!";
    } else {
        $errorMessage = "An error occurred while sending your message. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Fashion Closet</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
   body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      color: #333;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #ffffff;
      padding: 20px 40px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .logo-container {
      display: flex;
      align-items: center;
    }

    .logo-img {
      max-height: 50px;
      margin-right: 10px;
    }

    .logo-text {
      font-size: 1.5rem;
      color: #007bff;
      font-weight: 600;
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
      color: #333;
      font-weight: 600;
    }

    main {
      max-width: 1200px;
      margin: 40px auto;
      padding: 20px;
      background: #ffffff;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    h1 {
      font-size: 2rem;
      color: #007bff;
      margin-bottom: 20px;
    }

    .contact-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .contact-info, .contact-form {
      flex: 1;
      min-width: 300px;
    }

    .contact-info {
      background: #f7f7f7;
      padding: 20px;
      border-radius: 8px;
    }

    .contact-info h2 {
      font-size: 1.5rem;
      margin-bottom: 10px;
    }

    .contact-info p {
      margin: 10px 0;
      line-height: 1.5;
    }

    .contact-form h2 {
      font-size: 1.5rem;
      margin-bottom: 10px;
    }

    .contact-form form {
      display: flex;
      flex-direction: column;
    }

    .contact-form label {
      margin-bottom: 5px;
      font-weight: 600;
    }

    .contact-form input, .contact-form textarea {
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1rem;
    }

    .contact-form button {
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
    }

    .contact-form button:hover {
      background-color: #0056b3;
    }

     footer {
      background-color: #232f3e;
      text-align: center;
      padding: 20px;
      font-size: 0.9rem;
      color: whitesmoke;
      margin-top: 40px;
    }
    footer .social-links a {
        color: #fff;
        font-size: 1.5rem;
        transition: color 0.3s;
      }
  
      footer .social-links a:hover {
        color: #007bff;
      }

  
      footer a {
        color: #007bff;
        text-decoration: none;
      }
  
      footer a:hover {
        text-decoration: underline;
      }

    footer p {
      margin: 0;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo-container">
      <img src="images/AnnasCloset.png" class="logo-img" alt="Anna's Closet Logo">
      <h1 class="logo-text">Fashion Closet</h1>
    </div>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <h1>Contact Us</h1>
    <div class="contact-container">
      <div class="contact-info">
        <h2>Get in Touch</h2>
        <p>Have questions or need assistance? We're here to help! You can reach us through the following channels:</p>
        <p><strong>Email:</strong> support@fashioncloset.com</p>
        <p><strong>Phone:</strong> +233 (555) 945-568</p>
        <p><strong>Address:</strong> Valley View University, JJ Norty</p>
      </div>

      <div class="contact-form">
        <h2>Send Us a Message</h2>
        <?php if (isset($successMessage)): ?>
          <div class="message success"><?php echo $successMessage; ?></div>
        <?php elseif (isset($errorMessage)): ?>
          <div class="message error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <form action="contact.php" method="post">
          <label for="name">Your Name</label>
          <input type="text" id="name" name="name" required placeholder="Enter your name">

          <label for="email">Your Email</label>
          <input type="email" id="email" name="email" required placeholder="Enter your email">

          <label for="message">Your Message</label>
          <textarea id="message" name="message" rows="5" required placeholder="Type your message here"></textarea>

          <button type="submit">Submit</button>
        </form>
      </div>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 Faashion Closet. All rights reserved.</p>
    <div class="social-links">
      <a href="https://facebook.com" target="_blank" aria-label="Facebook">&#x1F426;</a>
      <a href="https://twitter.com" target="_blank" aria-label="Twitter">&#x1F426;</a>
      <a href="https://instagram.com" target="_blank" aria-label="Instagram">&#x1F426;</a>
    </div>
    <p>Need help? <a href="#">Contact our support team</a>.</p>
    <p>Developed by <a href=#">Faashion Closet Dev Team</a></p>
  </footer>
</body>
</html>
