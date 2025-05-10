<?php
session_start();
include 'db_connection.php'; // Include database connection

// Ensure user session management
$userEmail = isset($_SESSION['userEmail']) ? $_SESSION['userEmail'] : null;
$userDetails = null;

if ($userEmail) {
    // Fetch user details using the email
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ?");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult->num_rows > 0) {
        $userDetails = $userResult->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fashion Closet</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
   * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      color: #333;
      background-color: #f8f9fa;
      margin: 0;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #ffffff;
      padding: 20px 40px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      position: relative;
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

    .search-bar {
      display: flex;
      align-items: center;
      flex: 1;
      margin: 0 20px;
    }

    .search-bar select,
    .search-bar input {
      padding: 10px;
      font-size: 1rem;
      border: 1px solid #ccc;
      outline: none;
    }

    .search-bar button {
      padding: 10px 15px;
      font-size: 1rem;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 0 5px 5px 0;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .search-bar button:hover {
      background-color: #0056b3;
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

    .btn-login {
      padding: 10px 20px;
      font-size: 1rem;
      color: #fff;
      background-color: #0e74f0;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn-login:hover {
      background-color: #0056b3;
    }

    .hamburger-menu {
      display: none;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      cursor: pointer;
    }

    .hamburger-menu div {
      width: 30px;
      height: 3px;
      background-color: #333;
      margin: 4px 0;
      transition: 0.3s;
    }

    .mobile-nav {
      display: none;
      flex-direction: column;
      background: #ffffff;
      position: absolute;
      top: 80px;
      right: 0;
      width: 100%;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .mobile-nav ul {
      flex-direction: column;
    }

    .mobile-nav ul li {
      margin: 10px 0;
      text-align: center;
    }

    #suggestions {
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      background: white;
      border: 1px solid #ccc;
      border-top: none;
      z-index: 1000;
      display: none;
    }
    
    #suggestions div:hover {
      background-color: #f0f0f0;
    }

    @media (max-width: 768px) {
      .search-bar {
        display: none;
      }

      .hamburger-menu {
        display: flex;
      }

      .mobile-nav {
        display: flex;
      }

      nav ul {
        display: none;
      }
    }

    .welcome-box {
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      background-image: url('background.jpg');
      background-size: cover;
      background-position: center;
      color: #fff;
      padding: 60px 20px;
      margin: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .welcome-box h2 {
      font-size: 2.5rem;
      font-weight: 600;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .hero {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 40px;
      background: linear-gradient(135deg, #f0f4f8, #ffffff);
    }

    .hero-content {
      max-width: 50%;
    }

    .hero-content h1 {
      font-size: 3rem;
      color: #007bff;
      margin-bottom: 20px;
    }

    .hero-content p {
      font-size: 1.2rem;
      margin-bottom: 30px;
    }

    .cta-button {
      background-color: #007bff;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s ease;
    }

    .cta-button:hover {
      background-color: #0056b3;
    }

    .hero-image img {
      max-width: 100%;
      border-radius: 10px;
    }

    .stock-section {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      padding: 40px 20px;
    }

    .stock-item {
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      text-align: center;
      padding: 20px;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .stock-item img {
      width: 100%;
      height: auto;
      max-height: 300px;
      object-fit: cover;
      border-radius: 8px;
    }

    .stock-item h3 {
      font-size: 1.5rem;
      margin: 15px 0;
      color: #333;
    }

    .stock-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .see-more {
      text-decoration: none;
      color: blueviolet;
    }

    footer {
      background-color: #232f3e;
      text-align: center;
      padding: 20px;
      font-size: 0.9rem;
      color: whitesmoke;
      margin-top: 40px;
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

    footer .social-links a {
      color: #fff;
      font-size: 1.5rem;
      transition: color 0.3s;
    }

    footer .social-links a:hover {
      color: #007bff;
    }
     @media (max-width: 768px) {
      .hero {
        flex-direction: column;
        text-align: center;
      }

      .hero-content,
      .hero-image {
        max-width: 100%;
        padding: 20px 0;
      }

      .stock-section {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      }

      .stock-item img {
        max-height: 200px;
      }
    }

    @media (max-width: 480px) {
        .mobile-nav {
            display: flex;
            flex-direction: row;
            height: auto;
            align-items: center;
            gap: 10px;
            justify-content: center;
            text-align: center;
        }
        
        nav {
            flex-direction: row;
            display: flex;
            height: auto;
            justify-content: center;
            align-items: center;
            height: auto;
            text-align: center;
        }

        .mobile-nav li {
            display: inline-block;
            list-style: none;
        }
        
      .hero {
            flex-direction: column;
            text-align: center;
      }

      .hero-content,
      .hero-image {
            max-width: 100%;
            padding: 20px 0;
      }

      .stock-section {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      }

      .stock-item img {
            max-height: 200px;
      }
    }
  </style>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="images/AnnasCloset.png" class="logo-img">
        <h1 class="logo-text">Fashion Closet</h1>
    </div>
    <div class="hamburger-menu" onclick="toggleMenu()">
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="mobile-nav" id="mobileNav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if ($userDetails): ?>
                <li><a href="user_dashboard.php"><button class="btn-login">Dashboard</button></a></li>
                <li><a href="login.php"><button class="btn-login">Logout</button></a></li>
            <?php else: ?>
                <li><a href="login.php"><button class="btn-login">Login</button></a></li>
            <?php endif; ?>
        </ul>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if ($userDetails): ?>
                <a href="user_dashboard.php"><button class="btn-login">Dashboard</button></a>
                <a href="login.php"><button class="btn-login">Logout</button></a>
            <?php else: ?>
                <a href="login.php"><button class="btn-login">Login</button></a>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>
    <div class="welcome-box">
      <h2>Welcome to Fashion Closet</h2>
    </div>

    <section class="hero">
      <div class="hero-content">
        <h1>Unlock Your Style</h1>
        <p>Discover the latest trends and redefine your wardrobe with our unique collection.</p>
        <a href="shop.php"><button class="cta-button">Shop Now</button></a>
      </div>
      <div class="hero-image">
        <img src="images\model.jpg" alt="Model wearing trendy clothes">
      </div>
    </section>

    <section class="stock-section">
      <?php
      $query = "SELECT * FROM Products LIMIT 4";
      $result = $conn->query($query);

      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "
              <div class='stock-item'>
                  <img src='{$row['Image']}' alt='{$row['ProductName']}'>
                  <h3>{$row['ProductName']}</h3>
                  <p>\${$row['Price']}</p>
                  <a href='product.php?id={$row['ProductID']}'><button class='see-more'>See more</button></a>
              </div>";
          }
      } else {
          echo "<p>No products available</p>";
      }
      ?>
    </section>
</main>
<footer>
    <p>&copy; 2025 Fashion Closet. All rights reserved.</p>
    <div class="social-links">
      <a href="https://facebook.com" target="_blank" aria-label="Facebook">&#x1F426;</a>
      <a href="https://twitter.com" target="_blank" aria-label="Twitter">&#x1F426;</a>
      <a href="https://instagram.com" target="_blank" aria-label="Instagram">&#x1F426;</a>
    </div>
    <p>Need help? <a href="#">Contact our support team</a>.</p>
    <p>Developed by <a href="#">Fashion Closet Dev Team</a></p>
</footer>
<script>
    function fetchSuggestions() {
        const searchQuery = document.getElementById('search').value;
        const category = document.getElementById('category').value;

        if (searchQuery.length > 0) {
            fetch(`fetch_suggestions.php?query=${searchQuery}&category=${category}`)
                .then(response => response.json())
                .then(data => {
                    const suggestions = document.getElementById('suggestions');
                    suggestions.style.display = 'block';
                    suggestions.innerHTML = '';

                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.textContent = item;
                        div.style.padding = '10px';
                        div.style.cursor = 'pointer';
                        div.addEventListener('click', () => {
                            document.getElementById('search').value = item;
                            suggestions.style.display = 'none';
                            performSearch();
                        });
                        suggestions.appendChild(div);
                    });
                });
        } else {
            document.getElementById('suggestions').style.display = 'none';
        }
    }

    function performSearch() {
        const searchQuery = document.getElementById('search').value;
        const category = document.getElementById('category').value;
        window.location.href = `search_results.php?query=${searchQuery}&category=${category}`;
    }
</script>
</body>
</html>
