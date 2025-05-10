<?php
// fetch_suggestions.php
include 'db_connection.php';

header('Content-Type: application/json');

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : 'all';

if ($query === '') {
    echo json_encode([]);
    exit;
}

$sql = "SELECT ProductName FROM Products WHERE ProductName LIKE ?";
$params = ["%$query%"];

if ($category !== 'all') {
    $sql .= " AND Category = ?";
    $params[] = $category;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];

while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row['ProductName'];
}

echo json_encode($suggestions);
