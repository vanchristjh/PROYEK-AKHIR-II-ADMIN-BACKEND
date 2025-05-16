<?php

// Database credentials - you may need to adjust these
$servername = "localhost";  // Usually localhost for local development
$username = "root";  // Default MySQL username
$password = "";  // Default MySQL password (empty)
$dbname = "laravel";  // Your database name

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Get assignments table structure
    $query = "SHOW COLUMNS FROM assignments";
    $result = $conn->query($query);
    
    if ($result === false) {
        echo "Error: " . $conn->error . "\n";
    } else {
        echo "Assignments Table Structure:\n";
        echo "--------------------------\n";
        
        while ($row = $result->fetch_assoc()) {
            echo $row['Field'] . " - " . $row['Type'];
            if ($row['Default'] !== null) {
                echo " [Default: " . $row['Default'] . "]";
            }
            if ($row['Null'] === 'NO') {
                echo " [Required]";
            }
            echo "\n";
        }
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>
