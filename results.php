<?php
session_start();
include("db.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

$sql = "
SELECT
    c.id,
    c.candidate_name,
    p.position_name,
    h.house_name,
    c.photo,
    COUNT(v.candidate_id) AS total_votes
FROM candidates c
LEFT JOIN positions p ON c.position_id = p.id
LEFT JOIN houses h ON c.house_id = h.id
LEFT JOIN votes v ON c.id = v.candidate_id
GROUP BY
    c.id,
    c.candidate_name,
    p.position_name,
    h.house_name,
    c.photo
ORDER BY
    p.position_name,
    h.house_name,
    c.candidate_name
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Election Results</title>

<style>
body{
    font-family:Arial,sans-serif;
    background:#f4f6f9;
    margin:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
}

th,td{
    border:1px solid #ddd;
    padding:10px;
    text-align:center;
}

th{
    background:#2563eb;
    color:#fff;
}

img{
    width:70px;
    height:70px;
    border-radius:50%;
    object-fit:cover;
}

a.button{
    display:inline-block;
    padding:10px 15px;
    background:#2563eb;
    color:#fff;
    text-decoration:none;
    border-radius:6px;
    margin-bottom:15px;
}
</style>

</head>
<body>

<h2>📊 Election Results</h2>

<a class="button" href="admin_dashboard.php">⬅ Back to Dashboard</a>

<table>
<tr>
    <th>Photo</th>
    <th>Candidate</th>
    <th>Position</th>
    <th>House</th>
    <th>Total Votes</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>

<td>
<?php if(!empty($row["photo"])) { ?>
    <img src="uploads/<?php echo htmlspecialchars($row["photo"]); ?>" alt="Photo">
<?php } else { ?>
    No Image
<?php } ?>
</td>

<td><?php echo htmlspecialchars($row["candidate_name"]); ?></td>

<td><?php echo htmlspecialchars($row["position_name"]); ?></td>

<td><?php echo htmlspecialchars($row["house_name"]); ?></td>

<td><strong><?php echo $row["total_votes"]; ?></strong></td>

</tr>

<?php } ?>

</table>

</body>
</html>