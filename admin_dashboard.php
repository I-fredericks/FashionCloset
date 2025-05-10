<?php
session_start();
if ($_SESSION['userType'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'db_connection.php';

// Handle Delete Request
if (isset($_GET['delete'])) {
    $productID = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM Products WHERE ProductID = ?");
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_dashboard.php");
    exit();
}

// Handle Edit Request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit-product-id'])) {
    $productID = intval($_POST['edit-product-id']);
    $productName = $_POST['edit-product-name'];
    $category = $_POST['edit-category'];
    $price = $_POST['edit-price'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("UPDATE Products SET ProductName = ?, CategoryID = ?, Price = ?, Stock = ? WHERE ProductID = ?");
    $stmt->bind_param("sidi", $productName, $category, $price, $stock, $productID);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_dashboard.php");
    exit();
}

// Handle Product Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['edit-product-id'])) {
    $productName = $_POST['product-name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Ensure uploads directory exists
    $uploadsDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
    }

    // Upload image
    $image = $_FILES['image'];
    $imagePath = $uploadsDir . basename($image['name']);
    $imageUrl = 'uploads/' . basename($image['name']); // URL for database

    if (move_uploaded_file($image['tmp_name'], $imagePath)) {
        $stmt = $conn->prepare("INSERT INTO Products (ProductName, CategoryID, Price, Stock, Image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sidis", $productName, $category, $price, $stock, $imageUrl);
        if ($stmt->execute()) {
            $successMessage = "Product uploaded successfully!";
        } else {
            $errorMessage = "Failed to insert product into the database.";
        }
        $stmt->close();
    } else {
        $errorMessage = "Failed to upload image.";
    }
}

// Get product count for stats
$productCountQuery = "SELECT COUNT(*) as total FROM Products";
$productCountResult = $conn->query($productCountQuery);
$productCount = $productCountResult->fetch_assoc()['total'];

// Get low stock products count
$lowStockQuery = "SELECT COUNT(*) as low_stock FROM Products WHERE Stock <= 5";
$lowStockResult = $conn->query($lowStockQuery);
$lowStockCount = $lowStockResult->fetch_assoc()['low_stock'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Fashion Closet</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
      --primary: #232f3e;
      --secondary: #37475a;
      --accent: #ff9900;
      --success: #28a745;
      --danger: #dc3545;
      --warning: #ffc107;
      --info: #17a2b8;
      --light: #f8f9fa;
      --dark: #343a40;
      --white: #ffffff;
      --gray: #6c757d;
      --light-gray: #e9ecef;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f7fa;
      color: #333;
      line-height: 1.6;
    }

    .sidebar {
      width: 250px;
      background-color: var(--primary);
      color: var(--white);
      height: 100vh;
      position: fixed;
      padding: 20px;
      transition: all 0.3s;
      z-index: 1000;
    }

    .sidebar-header {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 30px;
      padding-bottom: 15px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header h2 {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--accent);
    }

    .sidebar-menu {
      list-style: none;
    }

    .sidebar-menu li {
      margin-bottom: 10px;
    }

    .sidebar-menu li a {
      color: var(--white);
      text-decoration: none;
      font-size: 16px;
      padding: 12px 15px;
      border-radius: 5px;
      display: flex;
      align-items: center;
      transition: all 0.3s;
    }

    .sidebar-menu li a:hover,
    .sidebar-menu li a.active {
      background-color: var(--secondary);
      color: var(--accent);
    }

    .sidebar-menu li a i {
      margin-right: 10px;
      font-size: 18px;
    }

    .main-content {
      margin-left: 250px;
      transition: all 0.3s;
      padding: 20px;
    }

    .top-navbar {
      background-color: var(--white);
      padding: 15px 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .page-title h1 {
      font-size: 1.8rem;
      font-weight: 600;
      color: var(--primary);
    }

    .user-profile {
      display: flex;
      align-items: center;
    }

    .user-profile img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 10px;
      object-fit: cover;
    }

    .user-profile .user-name {
      font-weight: 500;
    }

    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background-color: var(--white);
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
      transition: transform 0.3s;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }

    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      font-size: 24px;
    }

    .stat-icon.products {
      background-color: rgba(40, 167, 69, 0.1);
      color: var(--success);
    }

    .stat-icon.low-stock {
      background-color: rgba(220, 53, 69, 0.1);
      color: var(--danger);
    }

    .stat-info h3 {
      font-size: 14px;
      color: var(--gray);
      margin-bottom: 5px;
    }

    .stat-info h2 {
      font-size: 24px;
      font-weight: 600;
      color: var(--dark);
    }

    .card {
      background-color: var(--white);
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      padding: 25px;
      margin-bottom: 30px;
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 1px solid var(--light-gray);
    }

    .card-header h2 {
      font-size: 1.3rem;
      font-weight: 600;
      color: var(--primary);
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: var(--dark);
    }

    .form-control {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid var(--light-gray);
      border-radius: 6px;
      font-size: 16px;
      font-family: 'Poppins', sans-serif;
      transition: border-color 0.3s;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--accent);
    }

    .btn {
      padding: 12px 20px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn i {
      margin-right: 8px;
    }

    .btn-primary {
      background-color: var(--primary);
      color: var(--white);
    }

    .btn-primary:hover {
      background-color: var(--secondary);
    }

    .btn-success {
      background-color: var(--success);
      color: var(--white);
    }

    .btn-danger {
      background-color: var(--danger);
      color: var(--white);
    }

    .btn-warning {
      background-color: var(--warning);
      color: var(--dark);
    }

    .btn-sm {
      padding: 8px 12px;
      font-size: 14px;
    }

    .table-responsive {
      overflow-x: auto;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table th {
      background-color: var(--primary);
      color: var(--white);
      padding: 15px;
      text-align: left;
      font-weight: 500;
    }

    .table td {
      padding: 12px 15px;
      border-bottom: 1px solid var(--light-gray);
    }

    .table tr:nth-child(even) {
      background-color: var(--light);
    }

    .table tr:hover {
      background-color: rgba(0, 0, 0, 0.02);
    }

    .badge {
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 500;
    }

    .badge-success {
      background-color: rgba(40, 167, 69, 0.1);
      color: var(--success);
    }

    .badge-warning {
      background-color: rgba(255, 193, 7, 0.1);
      color: #d39e00;
    }

    .badge-danger {
      background-color: rgba(220, 53, 69, 0.1);
      color: var(--danger);
    }

    .alert {
      padding: 15px;
      border-radius: 6px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
    }

    .alert-success {
      background-color: rgba(40, 167, 69, 0.1);
      color: var(--success);
      border-left: 4px solid var(--success);
    }

    .alert-danger {
      background-color: rgba(220, 53, 69, 0.1);
      color: var(--danger);
      border-left: 4px solid var(--danger);
    }

    .alert i {
      margin-right: 10px;
      font-size: 20px;
    }

    .edit-form {
      background-color: var(--light);
      padding: 20px;
      border-radius: 8px;
      margin-top: 15px;
      border-left: 4px solid var(--warning);
    }

    .text-danger {
      color: var(--danger);
      font-weight: 600;
    }

    .text-success {
      color: var(--success);
    }

    .text-muted {
      color: var(--gray);
    }

    .strikethrough {
      text-decoration: line-through;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 80px;
        overflow: hidden;
      }
      .sidebar-header h2,
      .sidebar-menu li a span {
        display: none;
      }
      .sidebar-menu li a {
        justify-content: center;
      }
      .sidebar-menu li a i {
        margin-right: 0;
        font-size: 20px;
      }
      .main-content {
        margin-left: 80px;
      }
      .stats-container {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="sidebar-header">
      <h2>Fashion Closet</h2>
    </div>
    <ul class="sidebar-menu">
      <li><a href="admin_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
      <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> <span>Orders</span></a></li>
      <li><a href="customers.php"><i class="fas fa-users"></i> <span>Customers</span></a></li>
      <li><a href="report.php"><i class="fas fa-chart-bar"></i> <span>Reports</span></a></li>
      <li><a href="settings.php"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
      <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
    </ul>
  </div>

  <div class="main-content">
    <div class="top-navbar">
      <div class="page-title">
        <h1>Admin Dashboard</h1>
      </div>
      <div class="user-profile">
        <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin">
        <span class="user-name">Admin</span>
      </div>
    </div>

    <div class="stats-container">
      <div class="stat-card">
        <div class="stat-icon products">
          <i class="fas fa-tshirt"></i>
        </div>
        <div class="stat-info">
          <h3>Total Products</h3>
          <h2><?php echo $productCount; ?></h2>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon low-stock">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-info">
          <h3>Low Stock Items</h3>
          <h2><?php echo $lowStockCount; ?></h2>
        </div>
      </div>
    </div>

    <?php if (isset($successMessage)): ?>
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo $successMessage; ?>
      </div>
    <?php endif; ?>

    <?php if (isset($errorMessage)): ?>
      <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo $errorMessage; ?>
      </div>
    <?php endif; ?>

    <div class="card">
      <div class="card-header">
        <h2>Add New Product</h2>
      </div>
      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="product-name">Product Name</label>
          <input type="text" id="product-name" name="product-name" class="form-control" required>
        </div>
        
        <div class="form-group">
          <label for="category">Category</label>
          <select id="category" name="category" class="form-control" required>
            <option value="">Select a Category</option>
            <?php
            $categoriesQuery = "SELECT * FROM Categories";
            $categoriesResult = $conn->query($categoriesQuery);
            while ($category = $categoriesResult->fetch_assoc()) {
              echo "<option value='{$category['CategoryID']}'>{$category['CategoryName']}</option>";
            }
            ?>
          </select>
        </div>
        
        <div class="form-group">
          <label for="price">Price ($)</label>
          <input type="number" id="price" name="price" step="0.01" class="form-control" required>
        </div>
        
        <div class="form-group">
          <label for="stock">Stock Quantity</label>
          <input type="number" id="stock" name="stock" step="1" class="form-control" required>
        </div>
        
        <div class="form-group">
          <label for="image">Product Image</label>
          <input type="file" id="image" name="image" accept="image/*" class="form-control" required>
          <small class="text-muted">Recommended size: 800x800px</small>
        </div>
        
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-plus-circle"></i> Add Product
        </button>
      </form>
    </div>

    <div class="card">
      <div class="card-header">
        <h2>Product Inventory</h2>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $productsQuery = "SELECT Products.ProductID, Products.ProductName, Categories.CategoryName, Products.Price, Products.Stock
                              FROM Products 
                              JOIN Categories ON Products.CategoryID = Categories.CategoryID
                              ORDER BY Products.Stock ASC";
            $productsResult = $conn->query($productsQuery);
            while ($product = $productsResult->fetch_assoc()) {
              // Apply discount if stock is 5 or less
              $displayPrice = $product['Price'];
              $isLowStock = $product['Stock'] <= 5;
              if ($isLowStock) {
                $displayPrice = $product['Price'] * 0.8;
              }
              
              echo "
              <tr>
                <td>{$product['ProductName']}</td>
                <td>{$product['CategoryName']}</td>
                <td>";
              if ($isLowStock) {
                echo "<span class='strikethrough text-muted'>\${$product['Price']}</span> ";
                echo "<span class='text-danger'>\${" . number_format($displayPrice, 2) . "}</span>";
              } else {
                echo "\${$product['Price']}";
              }
              echo "</td>
                <td>{$product['Stock']}</td>
                <td>";
              if ($isLowStock) {
                echo "<span class='badge badge-danger'>Low Stock</span>";
              } else if ($product['Stock'] > 5 && $product['Stock'] <= 10) {
                echo "<span class='badge badge-warning'>Medium Stock</span>";
              } else {
                echo "<span class='badge badge-success'>In Stock</span>";
              }
              echo "</td>
                <td>
                  <button class='btn btn-warning btn-sm' onclick=\"document.getElementById('edit-form-{$product['ProductID']}').style.display='block'\">
                    <i class='fas fa-edit'></i> Edit
                  </button>
                  <a href='admin_dashboard.php?delete={$product['ProductID']}' class='btn btn-danger btn-sm'>
                    <i class='fas fa-trash-alt'></i> Delete
                  </a>
                </td>
              </tr>
              <tr id='edit-form-{$product['ProductID']}' style='display: none;'>
                <td colspan='6'>
                  <div class='edit-form'>
                    <form method='POST'>
                      <input type='hidden' name='edit-product-id' value='{$product['ProductID']}'>
                      <div class='form-group'>
                        <label for='edit-product-name'>Product Name</label>
                        <input type='text' name='edit-product-name' value='{$product['ProductName']}' class='form-control' required>
                      </div>
                      <div class='form-group'>
                        <label for='edit-category'>Category</label>
                        <select name='edit-category' class='form-control' required>
                          <option value=''>Select a Category</option>";
                          $categoriesResult->data_seek(0); // Reset category result pointer
                          while ($category = $categoriesResult->fetch_assoc()) {
                            $selected = $category['CategoryID'] === $product['CategoryID'] ? "selected" : "";
                            echo "<option value='{$category['CategoryID']}' $selected>{$category['CategoryName']}</option>";
                          }
                          echo "
                        </select>
                      </div>
                      <div class='form-group'>
                        <label for='edit-price'>Price ($)</label>
                        <input type='number' name='edit-price' step='0.01' value='{$product['Price']}' class='form-control' required>
                      </div>
                      <div class='form-group'>
                        <label for='stock'>Stock Quantity</label>
                        <input type='number' name='stock' step='1' value='{$product['Stock']}' class='form-control' required>
                      </div>
                      <button type='submit' class='btn btn-success'>
                        <i class='fas fa-save'></i> Update Product
                      </button>
                    </form>
                  </div>
                </td>
              </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    // Simple script to toggle sidebar on mobile
    document.addEventListener('DOMContentLoaded', function() {
      // Close all edit forms when clicking outside
      document.addEventListener('click', function(e) {
        if (!e.target.closest('.edit-form') && !e.target.closest('[onclick*="edit-form"]')) {
          document.querySelectorAll('[id^="edit-form-"]').forEach(form => {
            form.style.display = 'none';
          });
        }
      });
      
      // Add confirmation for delete actions
      document.querySelectorAll('a[href*="delete"]').forEach(link => {
        link.addEventListener('click', function(e) {
          if (!confirm('Are you sure you want to delete this product?')) {
            e.preventDefault();
          }
        });
      });
    });
  </script>
</body>
</html>