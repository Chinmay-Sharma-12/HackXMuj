<?php
header('Content-Type: application/json');
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "hackx";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$customerId = $data['customerId'];
$productId = $data['productId'];

$customerSql = "SELECT return_rate FROM Customers WHERE customer_id = ?";
$productSql = "SELECT return_rate FROM Products WHERE product_id = ?";

$stmt = $conn->prepare($customerSql);
$stmt->bind_param("i", $customerId);
$stmt->execute();
$customerResult = $stmt->get_result()->fetch_assoc();
$customerReturnRate = $customerResult ? $customerResult['return_rate'] : 0;

$stmt = $conn->prepare($productSql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$productResult = $stmt->get_result()->fetch_assoc();
$productReturnRate = $productResult ? $productResult['return_rate'] : 0;

$conn->close();

echo json_encode([
    'customerReturnRate' => $customerReturnRate,
    'productReturnRate' => $productReturnRate
]);
?>
