<?php
include 'db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Email already exists.";
        header("Location: create_account.php?error=" . urlencode($error));
        exit();
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO Users (UserName, Email, PasswordHash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            // Get the newly inserted user's ID
            $userID = $stmt->insert_id;

            // Redirect to the user dashboard with the user's unique ID
            header("Location: user_dashboard.php?id=" . $userID);
            exit();
        } else {
            $error = "Error creating account.";
            header("Location: create_account.php?error=" . urlencode($error));
            exit();
        }
    }
}
?>
