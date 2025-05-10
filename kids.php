<?php
include 'db_connection.php';

// Fetch products with CategoryID = 2
$query = "SELECT * FROM Products WHERE CategoryID = 1";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kids Clothes - Fashion Collection</title>
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

    .header {
      background-color: #007bff;
      color: black;
      padding: 15px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .kids-clothes {
      background-image: url('kid(3).jpg');
      background-size: cover;
      background-position: center;
      padding: 60px 20px;
      text-align: center;
      color: #fff;
    }

    .kids-clothes h2 {
      font-size: 3rem;
      margin-bottom: 20px;
      text-shadow: 0 2px 5px rgba(0, 0, 0, 0.7);
    }

    .kids-clothes p {
      font-size: 1.5rem;
      margin-bottom: 30px;
    }

    .btn-shop {
      display: inline-block;
      background-color: #ff9900;
      color: #fff;
      padding: 10px 20px;
      font-size: 1.2rem;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .btn-shop:hover {
      background-color: #e68a00;
    }

    .clothes-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      padding: 40px 20px;
    }

    .clothes-card {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: transform 0.3s, box-shadow 0.3s;
      text-align: center;
      padding: 20px;
    }

    .clothes-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .clothes-card img {
      width: 100%;
      height: auto;
      max-height: 300px;
      object-fit: contain;
    }

    .clothes-card h3 {
      font-size: 1.4rem;
      margin: 15px 0;
      color: #333;
    }

    .price {
      font-size: 1.5rem;
      color: #007bff;
      font-weight: bold;
    }

    .availability {
      font-size: 1rem;
      margin-top: 5px;
      color: #28a745;
      font-weight: bold;
    }

    .availability.out-of-stock {
      color: #dc3545;
    }

    .add-to-cart {
      display: inline-block;
      margin-top: 10px;
      padding: 10px 20px;
      background-color: #ff9900;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s;
    }

    .add-to-cart:hover {
      background-color: #e68a00;
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
      <img src="images\logo.png" alt="Logo" class="logo-img">
      <h1 class="logo-text">Fashion Closet</h1>
    </div>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="shop.php">Shop</a></li>
        <li><a href="cart.php" class="active">Go to Cart</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </nav>
  </header>
   <header class="header">
    <div><p>                        </p></div>
    <div>
    <nav>
      <ul>
        <li><a href="mens_wear.php">Mens Fashion</a></li>
        <li><a href="womens_wear.php">Womens Fashion</a></li>
        <li><a href="kids.php">Kids Fashion</a></li>
        <li><a href="accessories.php">Accessories</a></li>
      </ul>
    </nav>
    </div>
   </header>

  <section class="kids-clothes">
    <h2>Explore Kids Fashion</h2>
    <p>Discover premium quality and stylish apparel.</p>
    <a href="shop.html" class="btn-shop">Shop Now</a>
  </section>

  <main>
  <div class="clothes-grid">
    <?php while ($product = $result->fetch_assoc()): ?>
      <div class="clothes-card">
        <img src="<?php echo $product['Image']; ?>" alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
        <h3><?php echo htmlspecialchars($product['ProductName']); ?></h3>
        <span class="price">$<?php echo $product['Price']; ?></span>
        <span class="availability <?php echo $product['Stock'] > 0 ? '' : 'out-of-stock'; ?>">
          <?php echo $product['Stock'] > 0 ? 'Available' : 'Out of Stock'; ?>
        </span>
        <?php if ($product['Stock'] > 0): ?>
          <form method="POST" action="cart.php">
            <input type="hidden" name="add_to_cart" value="1">
            <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['ProductName']); ?>">
            <input type="hidden" name="price" value="<?php echo $product['Price']; ?>">
            <input type="hidden" name="quantity" value="1">
            <button class="add-to-cart" type="submit">Add to Cart</button>
          </form>
        <?php else: ?>
          <button class="add-to-cart" disabled style="opacity: 0.6; cursor: not-allowed;">Out of Stock</button>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </div>
</main>

  <footer >
    <p>&copy; 2025 Fashion Closet. All rights reserved.</p>
 <br><br>
    <div class="social-links">
      <a href="https://facebook.com" target="_blank" aria-label="Facebook">&#x1F426;</a>
      <a href="https://twitter.com" target="_blank" aria-label="Twitter">&#x1F426;</a>
      <a href="https://instagram.com" target="_blank" aria-label="Instagram">&#x1F426;</a>
    </div>
    <p>Need help? <a href="#">Contact our support team</a>.</p>
    <p>Developed by <a href="#">Fashion Closet Dev Team</a></p>
  </footer>
</body>
</html>
