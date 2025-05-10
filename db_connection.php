<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fashioncloset";

// Enable detailed error reporting for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // If no error, connection is successful
    // echo "Connected successfully"; (Uncomment for debugging)
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>
