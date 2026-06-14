<?php
$conn = new mysqli("localhost", "root", "", "voting_db");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Use UTF-8 for proper text handling
$conn->set_charset("utf8mb4");
?>