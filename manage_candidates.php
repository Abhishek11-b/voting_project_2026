<?php
session_start();
include("db.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

// ================= DELETE CANDIDATE =================
if (isset($_GET["delete"])) {

    $id = intval($_GET["delete"]);

    // Get candidate photo
    $stmt = $conn->prepare("SELECT photo FROM candidates WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        if (!empty($row["photo"])) {
            $filepath = "uploads/" . $row["photo"];
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
    }
    $stmt->close();

    // Delete votes related to this candidate
    $stmt = $conn->prepare("DELETE FROM votes WHERE candidate_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Delete candidate
    $stmt = $conn->prepare("DELETE FROM candidates WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_candidates.php");
    exit();
}
// ================= END DELETE =================

$result = $conn->query("
    SELECT
        c.*,
        p.position_name,
        h.house_name
    FROM candidates c
    LEFT JOIN positions p ON c.position_id = p.id
    LEFT JOIN houses h ON c.house_id = h.id
    ORDER BY c.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Candidates</title>
<style>
body{
    font-family:Arial,sans-serif;
    background:#f4f6f9;
    margin:20px;
}
h2{text-align:center;}
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
    object-fit:cover;
    border-radius:50%;
}
.btn{
    padding:8px 12px;
    color:#fff;
    text-decoration:none;
    border-radius:5px;
}
.add{background:green;}
.delete{background:red;}
.back{background:#2563eb;}
</style>
</head>
<body>

<h2>Manage Candidates</h2>

<p>
    <a class="btn add" href="add_candidate.php">Add Candidate</a>
    <a class="btn back" href="admin_dashboard.php">Dashboard</a>
</p>

<table>
<tr>
    <th>ID</th>
    <th>Photo</th>
    <th>Name</th>
    <th>Position</th>
    <th>House</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>
    <td><?php echo $row["id"]; ?></td>

    <td>
    <?php if (!empty($row["photo"])) { ?>
        <img src="uploads/<?php echo htmlspecialchars($row["photo"]); ?>">
    <?php } else { ?>
        No Image
    <?php } ?>
    </td>

    <td><?php echo htmlspecialchars($row["candidate_name"]); ?></td>
    <td><?php echo htmlspecialchars($row["position_name"]); ?></td>
    <td><?php echo htmlspecialchars($row["house_name"]); ?></td>

    <td>
        <a class="btn delete"
           href="manage_candidates.php?delete=<?php echo $row["id"]; ?>"
           onclick="return confirm('Are you sure you want to delete this candidate?');">
           Delete
        </a>
    </td>
</tr>

<?php } ?>

</table>

</body>
</html>