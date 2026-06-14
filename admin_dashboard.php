<?php
session_start();
include("db.php");

// Redirect to login if not authenticated
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

// Count records
$studentCount = 0;
$candidateCount = 0;
$voteCount = 0;

$r = $conn->query("SELECT COUNT(*) AS total FROM students");
if ($r) {
    $studentCount = $r->fetch_assoc()["total"];
}

$r = $conn->query("SELECT COUNT(*) AS total FROM candidates");
if ($r) {
    $candidateCount = $r->fetch_assoc()["total"];
}

$r = $conn->query("SELECT COUNT(*) AS total FROM votes");
if ($r) {
    $voteCount = $r->fetch_assoc()["total"];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<style>
body{
    margin:0;
    font-family:Arial,sans-serif;
    background:#eef2f7;
}

.header{
    background:#1e3a8a;
    color:#fff;
    padding:20px;
}

.header h2{
    margin:0;
}

.container{
    width:90%;
    margin:30px auto;
}

.cards{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}

.card{
    flex:1;
    min-width:220px;
    background:#fff;
    border-radius:12px;
    padding:20px;
    box-shadow:0 2px 8px rgba(0,0,0,0.15);
    text-align:center;
}

.card h3{
    margin:0;
    font-size:18px;
}

.card p{
    font-size:32px;
    font-weight:bold;
    color:#2563eb;
}

.menu{
    margin-top:30px;
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

.menu a{
    text-decoration:none;
    background:#2563eb;
    color:#fff;
    padding:12px 18px;
    border-radius:8px;
}

.menu a:hover{
    background:#1d4ed8;
}
</style>

</head>

<body>

<div class="header" style="display:flex;justify-content:space-between;align-items:center;">

    <!-- LEFT SIDE -->
    <div style="display:flex;align-items:center;gap:15px;">
        
        <!-- LOGO -->
        <img src="assets/logo.png" alt="Logo" style="height:60px;">

        <!-- SCHOOL NAME -->
        <div>
            <h2 style="margin:0;">
                Prudence International Residential School & PU College
            </h2>
            <p style="margin:0;font-size:14px;">
                Voting System
            </p>
        </div>
    </div>

    <!-- RIGHT SIDE -->
    <div style="text-align:right;">
        <p style="margin:0;">
            Welcome, <?php echo htmlspecialchars($_SESSION["admin"]); ?>
        </p>
        <small>Designed by <b>CS - Dept</b></small>
    </div>

</div>

<div class="container">

    <div class="cards">

        <div class="card">
            <h3>Total Students</h3>
            <p><?php echo $studentCount; ?></p>
        </div>

        <div class="card">
            <h3>Total Candidates</h3>
            <p><?php echo $candidateCount; ?></p>
        </div>

        <div class="card">
            <h3>Total Votes</h3>
            <p><?php echo $voteCount; ?></p>
        </div>

    </div>

  <div class="menu">
    <a href="add_candidate.php">➕ Add Candidate</a>
    <a href="manage_candidates.php">👥 Manage Candidates</a>

    <a href="add_student.php">👨‍🎓 Add Student</a>
    <a href="manage_students.php">📋 Manage Students</a>

    <a href="import_students.php" style="color:green;">
        📤 Import Students
    </a>

    <a href="results.php">📊 View Results</a>
    <a href="export.php">📥 Export CSV</a>

    <a href="student_login.php">👨‍🎓 Student Login</a>

    <!-- ✅ NEW PUBLIC VOTING LINK -->
    <a href="public_vote.php?type=white" style="color:blue;">
        🌍 Public Voting
    </a>

    <a href="logout.php">🚪 Logout</a>
</div>

</div>

</body>
</html>