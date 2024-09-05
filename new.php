<?php
$servername = "localhost:3308";
$username = "root";
$pass = "";
$database = "rt";

$conn = mysqli_connect($servername, $username, $pass, $database);

if (!$conn) {
    die("NOPE: " . mysqli_connect_error());
}
if (isset($_POST['save'])) {
    $fname = $_POST['fname'];
    $email= $_POST['email'];
    $phone= $_POST['phone'];
    $license= $_POST['license'];
    $car= $_POST['car'];
    $password= $_POST['password'];


    // Use prepared statement to prevent SQL injection
    $query = "INSERT INTO driver(fname, email, phone, license, car, `password`) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, 'ssssss', $fname, $email, $phone, $license, $car, $password);
    if (mysqli_stmt_execute($stmt)) {
        echo "New Details entered successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
