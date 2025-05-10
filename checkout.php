<?php
session_start();
include 'db_connection.php';

// Fetch user details if logged in
$userID = $_SESSION['userID'] ?? null;
$userDetails = [];
if ($userID) {
    $stmt = $conn->prepare("SELECT * FROM Users WHERE UserID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $userDetails = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $fullName = $_POST['fullName'];
    $address = $_POST['address'];
    $number = $_POST['number'];
    $city = $_POST['city'];
    $postalCode = $_POST['postalCode'];
    $country = $_POST['country'];
    $paymentMethod = $_POST['paymentMethod'];
    $transactionID = $_POST['transactionID'];
    $cartItems = $_SESSION['cart'] ?? [];

    if (empty($cartItems)) {
        echo "<script>alert('Your cart is empty!');</script>";
        exit();
    }

    // Calculate total amount
    $grandTotal = array_reduce($cartItems, function ($total, $item) {
        return $total + ($item['price'] * $item['quantity']);
    }, 0);

    // Insert order into Orders table
    $stmt = $conn->prepare(
        "INSERT INTO Orders (UserID, OrderDate, TotalAmount, FullName, Address, Phone, City, PostalCode, Country, PaymentMethod, TransactionID) 
        VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "idssssssss",
        $userID,
        $grandTotal,
        $fullName,
        $address,
        $number,
        $city,
        $postalCode,
        $country,
        $paymentMethod,
        $transactionID
    );
    $stmt->execute();
    $orderID = $stmt->insert_id; // Get the last inserted OrderID
    $stmt->close();

    // Insert each cart item into Order_Items table
    foreach ($cartItems as $item) {
        $stmt = $conn->prepare(
            "INSERT INTO Order_Items (OrderID, ProductID, Quantity, UnitPrice) 
            VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "iiid",
            $orderID,
            $item['product_id'],
            $item['quantity'],
            $item['price']
        );
        $stmt->execute();
        $stmt->close();
    }

    // Clear cart and redirect to confirmation
    unset($_SESSION['cart']);
    header("Location: user_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Anna's Closet</title>
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

        .checkout-container {
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
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #232f3e;
            color: #fff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn-place-order {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ff9900;
            color: #fff;
            text-decoration: none;
            font-size: 1.2rem;
            border-radius: 5px;
            transition: background-color 0.3s;
            cursor: pointer;
            border: none;
        }

        .btn-place-order:hover {
            background-color: #e68a00;
        }

        footer {
            background-color: #232f3e;
            text-align: center;
            padding: 20px;
            color: whitesmoke;
            margin-top: 40px;
        }

        footer p {
            margin: 0;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="images/logo.png" alt="Logo" class="logo-img">
        <h1 class="logo-text">Anna's Closet</h1>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
</header>

<div class="checkout-container">
    <h2>Checkout</h2>

    <div class="section">
        <h3>Order Summary</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cartItems = $_SESSION['cart'] ?? [];
                $grandTotal = 0;
                foreach ($cartItems as $item) {
                    $total = $item['price'] * $item['quantity'];
                    $grandTotal += $total;
                    echo "<tr>
                        <td>{$item['product_name']}</td>
                        <td>\${$item['price']}</td>
                        <td>{$item['quantity']}</td>
                        <td>\${$total}</td>
                    </tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;">Grand Total:</td>
                    <td>$<?php echo number_format($grandTotal, 2); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <form method="POST">
        <div class="form-group">
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($userDetails['FullName'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <textarea id="address" name="address" required><?php echo htmlspecialchars($userDetails['Address'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="number">Phone Number:</label>
            <input type="text" id="number" name="number" value="<?php echo htmlspecialchars($userDetails['Phone'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>
        </div>
        <div class="form-group">
            <label for="postalCode">Postal Code:</label>
            <input type="text" id="postalCode" name="postalCode" required>
        </div>
        <div class="form-group">
            <label for="country">Country:</label>
            <input type="text" id="country" name="country" required>
        </div>

        <div>
            <h3>Payment Method</h3>
            <label><input type="radio" name="paymentMethod" value="card" checked onclick="togglePaymentDetails()"> Card Payment</label>
            <label><input type="radio" name="paymentMethod" value="mobileMoney" onclick="togglePaymentDetails()"> Mobile Money</label>
            <label><input type="radio" name="paymentMethod" value="bankTransfer" onclick="togglePaymentDetails()"> Bank Transfer</label>
            <br>
        </div>

        <div id="cardDetails">
            <div class="form-group">
                <label for="cardNumber">Card Number:</label>
                <input type="text" id="cardNumber" name="cardNumber">
            </div>
            <div class="form-group">
                <label for="expiryDate">Expiry Date:</label>
                <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY">
            </div>
            <div class="form-group">
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv">
            </div>
        </div>

        <div id="mobileMoneyDetails" class="hidden">
        <h3>Pay through this details and Provide your Transaction ID</h3>
            <p><strong>Provider:</strong> MTN Mobile Money</p>
            <p><strong>Account No.:</strong> 0555945568</p>
            <p><strong>Account Name:</strong> Annabella Ako-Nnubeng</p>
            <div class="form-group">
                <label for="TransactionID">Transaction Reference or ID:</label>
                <input type="text" id="transactionID" name="transactionID">
            </div>
        </div>

        <div id="bankTransferDetails" class="hidden">
          <h3>Pay through this details and Provide your Transaction ID</h3>
            <p><strong>Bank:</strong> United African Bank (UBA)</p>
            <p><strong>Account No.:</strong> 033374463636223</p>
            <p><strong>Account Name:</strong> Annabella Ako-Nnubeng</p>
            <div class="form-group">
                <label for="TransactionID">Transaction Reference Or ID:</label>
                <input type="text" id="transactionID" name="transactionID">
            </div>
        </div>

        <button type="submit" name="place_order" class="btn-place-order">Place Order</button>
    </form>
</div>

<footer>
    <p>&copy; 2024 Anna's Closet. All rights reserved.</p>
</footer>

<script>
    const togglePaymentDetails = () => {
        const selectedMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
        document.getElementById('cardDetails').style.display = selectedMethod === 'card' ? 'block' : 'none';
        document.getElementById('mobileMoneyDetails').classList.toggle('hidden', selectedMethod !== 'mobileMoney');
        document.getElementById('bankTransferDetails').classList.toggle('hidden', selectedMethod !== 'bankTransfer');
    };
</script>
</body>
</html>
