<?php
include 'db_connection.php';

$productID = $_GET['id'];
$query = "SELECT * FROM Products WHERE ProductID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

$categoryID = $product['CategoryID'];

// Apply 20% discount if stock is 5 or less
$originalPrice = $product['Price'];
$displayPrice = $product['Price'];
$discountApplied = false;
if ($product['Stock'] <= 5) {
    $displayPrice = $originalPrice * 0.8; // 20% discount
    $discountApplied = true;
}

// Define the redirect URL for the "More" button based on the category
$moreButtonUrl = '#';
if ($categoryID == 1) {
    $moreButtonUrl = 'kids_wear.php';
} elseif ($categoryID == 2) {
    $moreButtonUrl = 'mens_wear.php';
} elseif ($categoryID == 3) {
    $moreButtonUrl = 'womens_wear.php';
} elseif ($categoryID == 4) {
    $moreButtonUrl = 'accessories.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($product['ProductName']); ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }

    header {
      background-color: #232f3e;
      color: white;
      padding: 15px 40px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    header h1 {
      margin: 0;
      font-size: 1.5rem;
      color: #ff9900;
    }

    header a {
      color: white;
      text-decoration: none;
      background-color: #ff9900;
      padding: 10px 15px;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    header a:hover {
      background-color: #e68a00;
    }

    main {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 20px;
      margin: 0 auto;
      max-width: 800px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    main img {
      width: 100%;
      max-width: 300px;
      height: auto;
      margin-bottom: 20px;
      border-radius: 10px;
    }

    main h2 {
      font-size: 1.8rem;
      color: #232f3e;
      margin-bottom: 15px;
    }

    main p {
      font-size: 1rem;
      color: #555;
      margin-bottom: 10px;
    }

    .buttons {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }

    .buttons a, .buttons button {
      text-decoration: none;
      font-size: 1rem;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      border: none;
      transition: background-color 0.3s ease;
    }

    .add-to-cart {
      background-color: #ff9900;
      color: white;
    }

    .add-to-cart:hover {
      background-color: #e68a00;
    }

    .more-details {
      background-color: #232f3e;
      color: white;
    }

    .more-details:hover {
      background-color: #37475a;
    }

    .original-price {
      text-decoration: line-through;
      color: #999;
    }

    .sale-price {
      color: red;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <header>
    <h1>Anna's Closet</h1>
    <a href="shop.php">Back to Shop</a>
  </header>
  <main>
    <h2><?php echo htmlspecialchars($product['ProductName']); ?></h2>
    <img src="<?php echo $product['Image']; ?>" alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
    <?php if ($discountApplied): ?>
      <p><span class="original-price">Original Price: $<?php echo number_format($originalPrice, 2); ?></span></p>
      <p><span class="sale-price">Sale Price: $<?php echo number_format($displayPrice, 2); ?> (20% off!)</span></p>
      <p><strong>Hurry! Only <?php echo $product['Stock']; ?> left in stock!</strong></p>
    <?php else: ?>
      <p><strong>Price:</strong> $<?php echo number_format($displayPrice, 2); ?></p>
      <p><strong>Stock:</strong> <?php echo $product['Stock']; ?></p>
    <?php endif; ?>
    <p><strong>Category:</strong> <?php 
      if ($categoryID == 1) echo "Kids Wear";
      elseif ($categoryID == 2) echo "Men's Wear";
      elseif ($categoryID == 3) echo "Women's Wear";
      elseif ($categoryID == 4) echo "Accessories";
      else echo "Uncategorized";
    ?></p>
    <div class="buttons">
      <button 
        class="add-to-cart" 
        onclick="addToCart(
          '<?php echo htmlspecialchars($product['ProductName'], ENT_QUOTES, 'UTF-8'); ?>', 
          <?php echo $displayPrice; ?>, 
          '<?php echo htmlspecialchars($product['Image'], ENT_QUOTES, 'UTF-8'); ?>',
          <?php echo $product['ProductID']; ?>
        )"
      >
        Add to Cart
      </button>
      <a href="<?php echo $moreButtonUrl; ?>" class="more-details">More</a>
    </div>
  </main>
  <script>
    const addToCart = (productName, price, image, productId) => {
      const cart = JSON.parse(localStorage.getItem('cartItems')) || [];
      
      // Check if product already exists in cart
      const existingItem = cart.find(item => item.productId === productId);
      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        cart.push({ 
          productName, 
          price, 
          image,
          productId,
          quantity: 1
        });
      }
      
      localStorage.setItem('cartItems', JSON.stringify(cart));
      alert(`${productName} added to cart!`);
      window.location.href = 'cart.php';
    };
  </script>
</body>
</html>