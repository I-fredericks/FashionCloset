<?php
session_start();
include 'db_connection.php';

// Check if user is admin
if ($_SESSION['userType'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch current settings
$settingsQuery = "SELECT * FROM Settings LIMIT 1";
$settingsResult = $conn->query($settingsQuery);
$currentSettings = $settingsResult->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $storeName = $_POST['store_name'];
    $storeEmail = $_POST['store_email'];
    $storePhone = $_POST['store_phone'];
    $storeAddress = $_POST['store_address'];
    $currency = $_POST['currency'];
    $taxRate = $_POST['tax_rate'];
    $shippingCost = $_POST['shipping_cost'];
    $freeShippingThreshold = $_POST['free_shipping_threshold'];
    $enableMobileMoney = isset($_POST['enable_mobile_money']) ? 1 : 0;
    $enableBankTransfer = isset($_POST['enable_bank_transfer']) ? 1 : 0;
    $maintenanceMode = isset($_POST['maintenance_mode']) ? 1 : 0;

    // Update settings in database
    $updateQuery = "UPDATE Settings SET 
                    store_name = ?,
                    store_email = ?,
                    store_phone = ?,
                    store_address = ?,
                    currency = ?,
                    tax_rate = ?,
                    shipping_cost = ?,
                    free_shipping_threshold = ?,
                    enable_mobile_money = ?,
                    enable_bank_transfer = ?,
                    maintenance_mode = ?
                    WHERE id = 1";
    
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssssddiii", 
        $storeName, $storeEmail, $storePhone, $storeAddress, 
        $currency, $taxRate, $shippingCost, $freeShippingThreshold,
        $enableMobileMoney, $enableBankTransfer, $maintenanceMode);
    
    if ($stmt->execute()) {
        $successMessage = "Settings updated successfully!";
        // Refresh current settings
        $settingsResult = $conn->query($settingsQuery);
        $currentSettings = $settingsResult->fetch_assoc();
    } else {
        $errorMessage = "Error updating settings: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings - Anna's Closet</title>
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
        .sidebar {
            width: 250px;
            background-color: #232f3e;
            color: white;
            height: 100vh;
            position: fixed;
            padding: 20px;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin-bottom: 15px;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px;
            border-radius: 5px;
            display: block;
            transition: background-color 0.3s ease;
        }
        .sidebar ul li a:hover {
            background-color: #37475a;
        }
        .main-content {
            margin-left: 250px;
            padding: 40px;
        }
        .settings-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            color: #232f3e;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
        }
        .checkbox-group {
            margin: 15px 0;
        }
        .checkbox-group label {
            display: inline-block;
            margin-left: 8px;
            font-weight: normal;
        }
        .btn-submit {
            background-color: #232f3e;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s;
            display: block;
            width: 100%;
            margin-top: 20px;
        }
        .btn-submit:hover {
            background-color: #37475a;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .section-title {
            font-size: 1.2rem;
            color: #232f3e;
            margin: 30px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #eee;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Anna's Closet</h2>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="orders.php">Manage Orders</a></li>
            <li><a href="report.php">Reports</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="index.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Store Settings</h1>
        
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        
        <div class="settings-container">
            <form method="POST">
                <h2 class="section-title">Store Information</h2>
                <div class="form-group">
                    <label for="store_name">Store Name</label>
                    <input type="text" id="store_name" name="store_name" value="<?php echo htmlspecialchars($currentSettings['store_name'] ?? 'Anna\'s Closet'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="store_email">Store Email</label>
                    <input type="email" id="store_email" name="store_email" value="<?php echo htmlspecialchars($currentSettings['store_email'] ?? 'info@annascloset.com'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="store_phone">Store Phone</label>
                    <input type="tel" id="store_phone" name="store_phone" value="<?php echo htmlspecialchars($currentSettings['store_phone'] ?? '+233 123 456 789'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="store_address">Store Address</label>
                    <textarea id="store_address" name="store_address" rows="3" required><?php echo htmlspecialchars($currentSettings['store_address'] ?? '123 Fashion Street, Accra, Ghana'); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="currency">Currency</label>
                    <select id="currency" name="currency" required>
                        <option value="GHS" <?php echo ($currentSettings['currency'] ?? 'GHS') === 'GHS' ? 'selected' : ''; ?>>Ghana Cedi (GHS)</option>
                        <option value="USD" <?php echo ($currentSettings['currency'] ?? 'GHS') === 'USD' ? 'selected' : ''; ?>>US Dollar (USD)</option>
                        <option value="EUR" <?php echo ($currentSettings['currency'] ?? 'GHS') === 'EUR' ? 'selected' : ''; ?>>Euro (EUR)</option>
                    </select>
                </div>
                
                <h2 class="section-title">Tax & Shipping</h2>
                <div class="form-group">
                    <label for="tax_rate">Tax Rate (%)</label>
                    <input type="number" id="tax_rate" name="tax_rate" min="0" max="50" step="0.1" value="<?php echo htmlspecialchars($currentSettings['tax_rate'] ?? 12.5); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="shipping_cost">Standard Shipping Cost (<?php echo $currentSettings['currency'] ?? 'GHS'; ?>)</label>
                    <input type="number" id="shipping_cost" name="shipping_cost" min="0" step="0.01" value="<?php echo htmlspecialchars($currentSettings['shipping_cost'] ?? 15.00); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="free_shipping_threshold">Free Shipping Threshold (<?php echo $currentSettings['currency'] ?? 'GHS'; ?>)</label>
                    <input type="number" id="free_shipping_threshold" name="free_shipping_threshold" min="0" step="0.01" value="<?php echo htmlspecialchars($currentSettings['free_shipping_threshold'] ?? 100.00); ?>" required>
                    <small>Enter 0 to disable free shipping</small>
                </div>
                
                <h2 class="section-title">Payment Methods</h2>
                <div class="checkbox-group">
                    <input type="checkbox" id="enable_mobile_money" name="enable_mobile_money" <?php echo ($currentSettings['enable_mobile_money'] ?? 1) ? 'checked' : ''; ?>>
                    <label for="enable_mobile_money">Enable Mobile Money Payments</label>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="enable_bank_transfer" name="enable_bank_transfer" <?php echo ($currentSettings['enable_bank_transfer'] ?? 1) ? 'checked' : ''; ?>>
                    <label for="enable_bank_transfer">Enable Bank Transfer Payments</label>
                </div>
                
                <h2 class="section-title">System Settings</h2>
                <div class="checkbox-group">
                    <input type="checkbox" id="maintenance_mode" name="maintenance_mode" <?php echo ($currentSettings['maintenance_mode'] ?? 0) ? 'checked' : ''; ?>>
                    <label for="maintenance_mode">Enable Maintenance Mode</label>
                    <small>When enabled, only administrators can access the store</small>
                </div>
                
                <button type="submit" class="btn-submit">Save Settings</button>
            </form>
        </div>
    </div>
</body>
</html>