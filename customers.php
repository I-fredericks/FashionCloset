<?php
session_start();
include 'db_connection.php';

// Check if user is admin
if ($_SESSION['userType'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle user deletion
if (isset($_GET['delete'])) {
    $userID = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE UserID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->close();
    header("Location: customers.php?success=User+deleted+successfully");
    exit();
}

// Handle user update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit-user-id'])) {
    $userID = intval($_POST['edit-user-id']);
    $username = $_POST['username'];
    $email = $_POST['email'];
    $userType = $_POST['userType'];

    $stmt = $conn->prepare("UPDATE users SET UserName = ?, Email = ?, UserType = ? WHERE UserID = ?");
    $stmt->bind_param("sssi", $username, $email, $userType, $userID);
    
    if ($stmt->execute()) {
        header("Location: customers.php?success=User+updated+successfully");
    } else {
        header("Location: customers.php?error=Error+updating+user");
    }
    $stmt->close();
    exit();
}

// Fetch all users
$usersQuery = "SELECT UserID, UserName, Email, CreatedAt, 
               CASE WHEN UserName = 'admin' THEN 'admin' ELSE 'customer' END as UserType 
               FROM users ORDER BY UserID DESC";
$usersResult = $conn->query($usersQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management - Fashion Closet</title>
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

        .badge-primary {
            background-color: rgba(35, 47, 62, 0.1);
            color: var(--primary);
        }

        .badge-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .badge-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
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

        .btn-warning {
            background-color: var(--warning);
            color: var(--dark);
        }

        .btn-danger {
            background-color: var(--danger);
            color: var(--white);
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

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--light-gray);
            border-radius: 4px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
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
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Fashion Closet</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="customers.php" class="active"><i class="fas fa-users"></i> <span>Customers</span></a></li>
            <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> <span>Orders</span></a></li>
            <li><a href="report.php"><i class="fas fa-chart-bar"></i> <span>Reports</span></a></li>
            <li><a href="settings.php"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
            <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-navbar">
            <div class="page-title">
                <h1>Customer Management</h1>
            </div>
            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin">
                <span class="user-name">Admin</span>
            </div>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h2>All Customers</h2>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Joined Date</th>
                            <th>User Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $usersResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $user['UserID']; ?></td>
                                <td><?php echo htmlspecialchars($user['UserName']); ?></td>
                                <td><?php echo htmlspecialchars($user['Email']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($user['CreatedAt'])); ?></td>
                                <td>
                                    <span class="badge <?php echo $user['UserType'] === 'admin' ? 'badge-primary' : 'badge-success'; ?>">
                                        <?php echo ucfirst($user['UserType']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning" onclick="document.getElementById('edit-form-<?php echo $user['UserID']; ?>').style.display='block'">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <a href="customers.php?delete=<?php echo $user['UserID']; ?>" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <tr id="edit-form-<?php echo $user['UserID']; ?>" style="display: none;">
                                <td colspan="6">
                                    <div class="edit-form">
                                        <form method="POST">
                                            <input type="hidden" name="edit-user-id" value="<?php echo $user['UserID']; ?>">
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['UserName']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="userType">User Type</label>
                                                <select name="userType" class="form-control" required>
                                                    <option value="customer" <?php echo $user['UserType'] === 'customer' ? 'selected' : ''; ?>>Customer</option>
                                                    <option value="admin" <?php echo $user['UserType'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save"></i> Update User
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
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
                    if (!confirm('Are you sure you want to delete this user?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>