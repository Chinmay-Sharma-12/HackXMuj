<?php
$servername = "localhost:3308";
$username = "root";
$password = ""; 
$dbname = "hackx";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
  
    header("Location: error.html");
    exit();
}


$employee_id = $_POST['employee_id'];
$hours_worked = $_POST['hours_worked'];


if (!preg_match('/^\d{10}$/', $employee_id)) {
    die("Invalid employee ID.");
}


if ($hours_worked <= 0 || $hours_worked > 200) {
    die("Invalid hours worked.");
}


$stmt = $conn->prepare("INSERT INTO bill_request (employee_id, hours_worked) VALUES (?, ?)");
$stmt->bind_param("si", $employee_id, $hours_worked);


if ($stmt->execute()) {
    header("Location: success.html");
    exit();
} else {
    header("Location: error.html");
    exit();
}

$stmt->close();
$conn->close();
?>
