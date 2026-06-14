<?php
session_start();
include("db.php");

if (!isset($_SESSION["student_id"])) {
    header("Location: student_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: student_dashboard.php");
    exit();
}

$student_id = $_SESSION["student_id"];
$candidate_id = intval($_POST["candidate_id"]);

/*
|--------------------------------------------------------------------------
| Get Candidate Position
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT position_id
    FROM candidates
    WHERE id = ?
");

$stmt->bind_param("i", $candidate_id);
$stmt->execute();

$result = $stmt->get_result();
$candidate = $result->fetch_assoc();

if (!$candidate) {
    die("❌ Invalid candidate selected.");
}

$position_id = $candidate["position_id"];

/*
|--------------------------------------------------------------------------
| Check if student already voted for this position
|--------------------------------------------------------------------------
*/

$check = $conn->prepare("
    SELECT id
    FROM votes
    WHERE student_id = ?
      AND position_id = ?
");

$check->bind_param(
    "si",
    $student_id,
    $position_id
);

$check->execute();

$existing = $check->get_result();

if ($existing->num_rows > 0) {

    echo "
    <h2>❌ You have already voted for this position.</h2>
    <br>
    <a href='student_dashboard.php'>⬅ Back to Dashboard</a>
    ";

    exit();
}

/*
|--------------------------------------------------------------------------
| Save Vote
|--------------------------------------------------------------------------
*/

$insert = $conn->prepare("
    INSERT INTO votes
    (
        student_id,
        candidate_id,
        position_id,
        voted_at
    )
    VALUES
    (
        ?, ?, ?, NOW()
    )
");

$insert->bind_param(
    "sii",
    $student_id,
    $candidate_id,
    $position_id
);

if ($insert->execute()) {

    echo "
    <h2>✅ Vote submitted successfully!</h2>
    <br>
    <a href='student_dashboard.php'>⬅ Back to Dashboard</a>
    ";

} else {

    echo "
    <h2>❌ Failed to submit vote.</h2>
    <br>
    <a href='student_dashboard.php'>⬅ Back to Dashboard</a>
    ";
}
?>