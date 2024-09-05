<?php
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "hackx";  
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize form data
$employee_id = $_POST['employee_id'];
$password = $_POST['password'];

$employee_id = $conn->real_escape_string($employee_id);
$password = $conn->real_escape_string($password);

// Query to check if the employee has checked in but not checked out
$sql = "SELECT * FROM timing WHERE employee_id = '$employee_id' AND password = '$password' AND check_out_time IS NULL ORDER BY check_in_time DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // If already checked in and not checked out
    $last_check_in = new DateTime($row['check_in_time']);
    $current_date = new DateTime();

    // Check if they are checking out on the same day or next day
    if ($last_check_in->format('Y-m-d') === $current_date->format('Y-m-d')) {
        // Check out the employee
        $check_out_time = date('Y-m-d H:i:s');
        $update_sql = "UPDATE timing SET check_out_time='$check_out_time' WHERE id=" . $row['id'];
        if ($conn->query($update_sql) === TRUE) {
            echo "Checked out successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        // If it is a new day, force check out for the previous day at 20:00:00
        $check_out_time = $last_check_in->format('Y-m-d 20:00:00');  // Force previous check-out at 20:00:00
        $update_sql = "UPDATE timing SET check_out_time='$check_out_time' WHERE id=" . $row['id'];
        
        if ($conn->query($update_sql) === TRUE) {
            // New check-in for the current day
            $check_in_time = date('Y-m-d H:i:s');
            $insert_sql = "INSERT INTO timing (employee_id, password, check_in_time) VALUES ('$employee_id', '$password', '$check_in_time')";
            if ($conn->query($insert_sql) === TRUE) {
                echo "Checked in successfully for the new day!";
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Error: " . $conn->error;
        }
    }
} else {
    // First-time check-in for today
    $check_in_time = date('Y-m-d H:i:s');
    $insert_sql = "INSERT INTO timing (employee_id, password, check_in_time) VALUES ('$employee_id', '$password', '$check_in_time')";
    if ($conn->query($insert_sql) === TRUE) {
        echo "Checked in successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
