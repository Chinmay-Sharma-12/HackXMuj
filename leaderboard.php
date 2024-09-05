<?php
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "hackx";  

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get employee data sorted by points in descending order
$sql = "SELECT name, points FROM leaderboard ORDER BY points DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Position</th><th>Employee Name</th><th>Points</th></tr>";
    $position = 1;
    // Fetch and display each row of data
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $position . "</td><td>" . $row['name'] . "</td><td>" . $row['points'] . "</td></tr>";
        $position++;
    }
    echo "</table>";
} else {
    echo "No employees found.";
}

$conn->close();
?>
