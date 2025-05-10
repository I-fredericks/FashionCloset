<?php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productID = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Check if product already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productID) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    if (!$found) {
        // Add new product to cart
        $_SESSION['cart'][] = [
            'product_id' => $productID,
            'product_name' => $productName,
            'price' => $price,
            'quantity' => $quantity,
        ];
    }

    header('Location: cart.php');
    exit();
}

// Handle removing from cart
if (isset($_GET['remove'])) {
    $productID = $_GET['remove'];
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($productID) {
        return $item['product_id'] != $productID;
    });
    header('Location: cart.php');
    exit();
}

// Update cart quantities
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $productID => $quantity) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $productID) {
                $item['quantity'] = max(1, (int)$quantity);
                break;
            }
        }
    }
    header('Location: cart.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart - Fashion Closet</title>
  <style>
    /* Styling for cart page */
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

    .cart-container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 20px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 2rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 15px;
      text-align: center;
      border: 1px solid #ddd;
    }

    th {
      background-color: #232f3e;
      color: #fff;
    }

    .cart-item img {
      width: 80px;
      height: auto;
    }

    .quantity-input {
      width: 60px;
      text-align: center;
      padding: 5px;
    }

    .btn-remove {
      padding: 5px 10px;
      color: #fff;
      background-color: #dc3545;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn-remove:hover {
      background-color: #bd2130;
    }

    .cart-summary {
      text-align: right;
      margin-top: 20px;
    }

    .cart-summary p {
      font-size: 1.2rem;
      margin: 10px 0;
    }

    .btn-back {
      padding: 12px 20px;
      background-color: #ff9900;
      display: inline-block;
      text-decoration: none;
      color: #fff;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .btn-back:hover {
      background-color: #e68a00;
    }

    .btn-checkout {
      display: inline-block;
      padding: 10px 20px;
      background-color: #ff9900;
      color: #fff;
      text-decoration: none;
      font-size: 1.2rem;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .btn-checkout:hover {
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
    <img src="images/logo.png" alt="Logo" class="logo-img">
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

<div class="cart-container">
  <h2>Your Shopping Cart</h2>
  <?php if (!empty($_SESSION['cart'])): ?>
    <form method="POST">
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $grandTotal = 0;
          foreach ($_SESSION['cart'] as $item):
            $total = $item['price'] * $item['quantity'];
            $grandTotal += $total;
          ?>
            <tr>
              <td><?php echo htmlspecialchars($item['product_name']); ?></td>
              <td>$<?php echo number_format($item['price'], 2); ?></td>
              <td>
                <input type="number" class="quantity-input" name="quantities[<?php echo $item['product_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
              </td>
              <td>$<?php echo number_format($total, 2); ?></td>
              <td><a href="cart.php?remove=<?php echo $item['product_id']; ?>" class="btn-remove">Remove</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="cart-summary">
        <p>Total: $<?php echo number_format($grandTotal, 2); ?></p>
        <a href="#" class="btn-back" onclick="history.back()">Add more to Cart</a>
        <button type="submit" name="update_cart" class="btn-checkout">Update Cart</button>
        <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
      </div>
    </form>
  <?php else: ?>
    <p>Your cart is empty.</p>
    <a href="shop.php" class="btn-back">Continue Shopping</a>
  <?php endif; ?>
</div>
<footer >
    <p>&copy; 2024 Fashion Closet. All rights reserved.</p>
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
