<?php
session_start();
include("db.php");

if (!isset($_SESSION["student_id"])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION["student_id"];
$message = "";

$type = $_GET['type'] ?? null;
$house_id = $_GET['house_id'] ?? null;

// 🚨 Safety check
if ($type != "white" && empty($house_id)) {
    die("❌ House ID missing");
}

// ✅ HANDLE VOTE SUBMIT
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    foreach ($_POST as $key => $value) {

        if (strpos($key, 'vote_') === 0) {

            $candidate_id = intval($value);

            // Get position
            $stmt = $conn->prepare("SELECT position_id FROM candidates WHERE id = ?");
            $stmt->bind_param("i", $candidate_id);
            $stmt->execute();
            $res = $stmt->get_result();
            $candidate = $res->fetch_assoc();

            if ($candidate) {

                $position_id = $candidate["position_id"];

                // Check already voted
                $check = $conn->prepare("
                    SELECT id FROM votes 
                    WHERE student_id = ? AND position_id = ?
                ");
                $check->bind_param("si", $student_id, $position_id);
                $check->execute();

                if ($check->get_result()->num_rows == 0) {

                    $insert = $conn->prepare("
                        INSERT INTO votes (student_id, candidate_id, position_id, voted_at)
                        VALUES (?, ?, ?, NOW())
                    ");
                    $insert->bind_param("sii", $student_id, $candidate_id, $position_id);
                    $insert->execute();
                }
            }
        }
    }

    $message = "✅ Votes submitted successfully!";
}

// ✅ LOAD CANDIDATES
if ($type == "white") {

    $stmt = $conn->prepare("
        SELECT c.*, p.position_name, h.house_name
        FROM candidates c
        LEFT JOIN positions p ON c.position_id = p.id
        LEFT JOIN houses h ON c.house_id = h.id
        WHERE c.position_id IN (1,2,3,4)
        ORDER BY p.id, c.candidate_name
    ");

} else {

    $stmt = $conn->prepare("
        SELECT c.*, p.position_name, h.house_name
        FROM candidates c
        LEFT JOIN positions p ON c.position_id = p.id
        LEFT JOIN houses h ON c.house_id = h.id
        WHERE c.house_id = ?
        ORDER BY p.id, c.candidate_name
    ");

    $stmt->bind_param("i", $house_id);
}

$stmt->execute();
$result = $stmt->get_result();

// ✅ GROUP BY POSITION
$grouped = [];
while ($row = $result->fetch_assoc()) {
    $grouped[$row['position_name']][] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Vote</title>

<style>
body { font-family: Arial; background:#f4f6f9; padding:20px; }
h2 { text-align:center; }
.section { margin-top:30px; }
.grid { display:flex; flex-wrap:wrap; gap:20px; }
.card {
    background:white;
    padding:15px;
    border-radius:10px;
    width:200px;
    text-align:center;
    box-shadow:0 2px 8px rgba(0,0,0,0.2);
}
img { width:100px; height:100px; border-radius:50%; }
button {
    margin-top:20px;
    padding:10px 20px;
    background:#2563eb;
    color:white;
    border:none;
    border-radius:5px;
}
</style>

</head>
<body>

<h2>
<?php echo ($type == "white") ? "🤍 White House Voting" : "🏠 House Voting"; ?>
</h2>

<p style="text-align:center;color:green;">
<?php echo $message; ?>
</p>

<form method="POST">

<?php foreach ($grouped as $position => $list) { ?>

<div class="section">
<h3><?php echo $position; ?></h3>

<div class="grid">

<?php foreach ($list as $row) { ?>

<div class="card">

<?php if ($row["photo"]) { ?>
<img src="uploads/<?php echo $row["photo"]; ?>">
<?php } ?>

<h4><?php echo $row["candidate_name"]; ?></h4>

<input 
    type="radio" 
    name="vote_<?php echo $row["position_id"]; ?>" 
    value="<?php echo $row["id"]; ?>" 
    required
>

</div>

<?php } ?>

</div>
</div>

<?php } ?>

<div style="text-align:center;">
<button type="submit">Submit Vote</button>
</div>

</form>

</body>
</html>