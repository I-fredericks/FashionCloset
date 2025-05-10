<?php
// search_results.php
include 'db_connection.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : 'all';

$sql = "SELECT * FROM Products WHERE ProductName LIKE ?";
$params = ["%$query%"];

if ($category !== 'all') {
    $sql .= " AND Category = ?";
    $params[] = $category;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
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
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        header .search-bar {
            display: flex;
            align-items: center;
            width: 50%;
        }

        header .search-bar input {
            flex: 1;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-right: none;
            border-radius: 4px 0 0 4px;
        }

        header .search-bar select {
            padding: 10px;
            border: 1px solid #ccc;
            border-right: none;
            border-radius: 4px 0 0 4px;
            outline: none;
        }

        header .search-bar button {
            padding: 10px 20px;
            background-color: #febd69;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            font-size: 1rem;
        }

        header .search-bar button:hover {
            background-color: #f3a847;
        }

        header .nav-links {
            display: flex;
            gap: 15px;
        }

        header .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        .product {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background: #fff;
            display: flex;
            gap: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product img {
            max-width: 150px;
            max-height: 150px;
            object-fit: contain;
        }

        .product-details {
            flex: 1;
        }

        .product h3 {
            font-size: 1.25rem;
            color: #007185;
        }

        .product p {
            font-size: 1rem;
            color: #b12704;
        }

        .no-results {
            font-size: 1.5rem;
            color: #ff0000;
            text-align: center;
            margin-top: 50px;
        }

        footer {
            background-color: #232f3e;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
        }

        footer a {
            color: #febd69;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header>
    <div class="logo">Anna's Closet</div>
    <div class="search-bar">
        <form action="search_results.php" method="GET" style="display: flex; width: 100%;">
            <select name="category">
                <option value="all">All</option>
                <option value="clothing" <?php echo $category === 'clothing' ? 'selected' : ''; ?>>Clothing</option>
                <option value="accessories" <?php echo $category === 'accessories' ? 'selected' : ''; ?>>Accessories</option>
            </select>
            <input type="text" name="query" value="<?php echo htmlspecialchars($query); ?>" placeholder="Search for products">
            <button type="submit">Search</button>
        </form>
    </div>
    <nav class="nav-links">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
    </nav>
</header>
<div class="container">
    <h1>Search Results</h1>
    <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
            <div class="product">
                <img src="<?php echo htmlspecialchars($product['Image']); ?>" alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
                <div class="product-details">
                    <h3><?php echo htmlspecialchars($product['ProductName']); ?></h3>
                    <p>Price: $<?php echo htmlspecialchars($product['Price']); ?></p>
                    <a href="product.php?id=<?php echo htmlspecialchars($product['ProductID']); ?>">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-results">No products found for your search.</p>
    <?php endif; ?>
</div>
<footer>
    <p>&copy; 2024 Anna's Closet. All rights reserved.</p>
    <p><a href="contact.php">Contact Us</a></p>
</footer>
</body>
</html>
