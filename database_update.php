<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db.php");

echo "<h2>Updating Database...</h2>";

$sql1 = "ALTER TABLE candidates CHANGE position_id position VARCHAR(100)";
$sql2 = "ALTER TABLE candidates CHANGE house_id house VARCHAR(100)";

if ($conn->query($sql1)) {
    echo "✅ position_id changed to position<br>";
} else {
    echo "❌ " . $conn->error . "<br>";
}

if ($conn->query($sql2)) {
    echo "✅ house_id changed to house<br>";
} else {
    echo "❌ " . $conn->error . "<br>";
}

echo "<br><b>Database Update Completed.</b>";

?>
