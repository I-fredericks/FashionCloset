<?php
include 'db_connection.php';

// Example Query
$sql = "SELECT * FROM admins";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Admin ID: " . $row['AdminID'] . " - Name: " . $row['AdminName'] . " - Email: ". $row['Email'] . "<br>";
    }
} else {
    echo "No results found.";
}

$conn->close();
?>
