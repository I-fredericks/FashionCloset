<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop Categories - Fashion Closet</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #232f3e;
      --accent: #ff9900;
      --light: #f8f9fa;
      --white: #ffffff;
      --text: #333333;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      color: var(--text);
      background-color: var(--light);
      line-height: 1.6;
      overflow-x: hidden;
    }

    header {
      background-color: var(--primary);
      color: var(--white);
      padding: 12px 30px;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .header-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 1200px;
      margin: 0 auto;
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .logo-img {
      height: 40px;
      width: auto;
      object-fit: contain;
    }

    .logo-text {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--accent);
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
    }

    nav ul li a {
      text-decoration: none;
      color: var(--white);
      font-weight: 500;
      font-size: 1rem;
      transition: color 0.2s;
      padding: 5px 0;
      position: relative;
    }

    nav ul li a:hover {
      color: var(--accent);
    }

    nav ul li a::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background-color: var(--accent);
      transition: width 0.2s;
    }

    nav ul li a:hover::after {
      width: 100%;
    }

    .shop-categories {
      padding: 60px 20px;
      text-align: center;
      background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                  url('Clothes_Category.jpg') no-repeat center center;
      background-size: cover;
      color: var(--white);
      min-height: 300px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .shop-categories h2 {
      font-size: 2.2rem;
      margin-bottom: 15px;
      text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
    }

    .categories-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 30px 20px;
    }

    .categories-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
      margin-top: 30px;
    }

    .category-card {
      background: var(--white);
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      text-decoration: none;
      color: var(--text);
      will-change: transform;
    }

    .category-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .category-img-container {
      height: 200px;
      overflow: hidden;
    }

    .category-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .category-card:hover img {
      transform: scale(1.05);
    }

    .category-card h3 {
      font-size: 1.3rem;
      padding: 18px 10px;
      font-weight: 500;
    }

    footer {
      background-color: var(--primary);
      color: var(--white);
      text-align: center;
      padding: 30px 20px;
      margin-top: 50px;
    }

    .footer-content {
      max-width: 1200px;
      margin: 0 auto;
    }

    .social-links {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 15px 0;
    }

    .social-links a {
      color: var(--white);
      font-size: 1.3rem;
      transition: color 0.2s;
    }

    .social-links a:hover {
      color: var(--accent);
    }

    .footer-links {
      margin: 15px 0;
    }

    .footer-links a {
      color: var(--white);
      text-decoration: none;
      margin: 0 10px;
      transition: color 0.2s;
    }

    .footer-links a:hover {
      color: var(--accent);
      text-decoration: underline;
    }

    .copyright {
      font-size: 0.9rem;
      opacity: 0.8;
      margin-top: 15px;
    }

    @media (max-width: 768px) {
      .header-container {
        flex-direction: column;
        gap: 15px;
      }
      
      nav ul {
        gap: 15px;
      }
      
      .categories-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
      }
    }

    @media (max-width: 480px) {
      .categories-grid {
        grid-template-columns: 1fr;
      }
      
      .shop-categories h2 {
        font-size: 1.8rem;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="header-container">
      <div class="logo-container">
        <img src="images/AnnasCloset.png" alt="Fashion Closet Logo" class="logo-img" width="160" height="40">
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
    </div>
  </header>

  <main>
    <section class="shop-categories">
      <h2>Shop by Category</h2>
    </section>

    <div class="categories-container">
      <div class="categories-grid">
        <!-- Category 1 -->
        <a href="womens_wear.php" class="category-card">
          <div class="category-img-container">
            <img src="images/women.jpg" alt="Women's Fashion" width="300" height="200" loading="lazy">
          </div>
          <h3>Women's Fashion</h3>
        </a>

        <!-- Category 2 -->
        <a href="mens_wear.php" class="category-card">
          <div class="category-img-container">
            <img src="images/men.jpg" alt="Men's Fashion" width="300" height="200" loading="lazy">
          </div>
          <h3>Men's Fashion</h3>
        </a>

        <!-- Category 3 -->
        <a href="kids.php" class="category-card">
          <div class="category-img-container">
            <img src="images/kids.jpg" alt="Kids' Fashion" width="300" height="200" loading="lazy">
          </div>
          <h3>Kids' Fashion</h3>
        </a>

        <!-- Category 4 -->
        <a href="accessories.php" class="category-card">
          <div class="category-img-container">
            <img src="images/watches.jpg" alt="Accessories" width="300" height="200" loading="lazy">
          </div>
          <h3>Accessories</h3>
        </a>
      </div>
    </div>
  </main>

  <footer>
    <div class="footer-content">
      <div class="social-links">
        <a href="https://facebook.com" target="_blank" aria-label="Facebook">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://twitter.com" target="_blank" aria-label="Twitter">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="https://instagram.com" target="_blank" aria-label="Instagram">
          <i class="fab fa-instagram"></i>
        </a>
      </div>
      <div class="footer-links">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="about.html">About</a>
        <a href="contact.php">Contact</a>
        <a href="#">Privacy Policy</a>
      </div>
      <p class="copyright">&copy; 2025 Fashion Closet. All rights reserved.</p>
      <p>Developed by Fashion Closet Dev Team</p>
    </div>
  </footer>

  <!-- Load Font Awesome for social icons -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" defer></script>
  
  <!-- Preload hero image -->
  <link rel="preload" href="Clothes_Category.jpg" as="image">
</body>
</html>