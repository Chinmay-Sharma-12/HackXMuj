<?php
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "hackx";  
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customer_id = $_POST['customer_id'];

$customer_id = $conn->real_escape_string($customer_id);

$sql = "SELECT id, name, email, return_rate FROM customers WHERE id = '$customer_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Customer ID: " . $row['id'] . "<br>";
        echo "Name: " . $row['name'] . "<br>";
        echo "Email: " . $row['email'] . "<br>";
        echo "Return Rate: " . $row['return_rate'] . "%<br>";
    }
} else {
    echo "No customer found with ID: $customer_id";
}

$conn->close();
?>
