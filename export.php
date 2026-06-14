<?php
include("db.php");

// Download settings
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="results.csv"');

$output = fopen("php://output", "w");

// CSV Headers
fputcsv($output, ["Candidate Name", "Total Votes"]);

// Your correct query
$sql = "
SELECT c.candidate_name, COUNT(v.id) AS total_votes
FROM candidates c
LEFT JOIN votes v ON c.id = v.candidate_id
GROUP BY c.id
";

$result = mysqli_query($conn, $sql);

// Output data
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [$row['candidate_name'], $row['total_votes']]);
}

fclose($output);
exit;
?>