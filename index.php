<?php
session_start();
include("db.php");
include("header.php");

// COUNT DATA
$students = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'];
$candidates = $conn->query("SELECT COUNT(*) as total FROM candidates")->fetch_assoc()['total'];
$votes = $conn->query("SELECT COUNT(*) as total FROM votes")->fetch_assoc()['total'];
?>

<style>
.dashboard {
    padding:30px;
    background:#f1f5f9;
    min-height:100vh;
}

.cards {
    display:flex;
    gap:20px;
    margin-top:20px;
}

.card {
    flex:1;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 8px 20px rgba(0,0,0,0.1);
    text-align:center;
}

.card h1 {
    color:#2563eb;
}

.actions {
    margin-top:30px;
    display:flex;
    flex-wrap:wrap;
    gap:15px;
}

.btn {
    padding:12px 20px;
    background:#2563eb;
    color:white;
    border-radius:8px;
    text-decoration:none;
}
</style>

<div class="dashboard">

<div class="cards">
    <div class="card"><h3>Total Students</h3><h1><?php echo $students; ?></h1></div>
    <div class="card"><h3>Total Candidates</h3><h1><?php echo $candidates; ?></h1></div>
    <div class="card"><h3>Total Votes</h3><h1><?php echo $votes; ?></h1></div>
</div>

<div class="actions">
<a class="btn" href="add_candidate.php">Add Candidate</a>
<a class="btn" href="manage_candidates.php">Manage Candidates</a>
<a class="btn" href="add_student.php">Add Student</a>
<a class="btn" href="manage_students.php">Manage Students</a>
<a class="btn" href="public_vote.php">Public Voting</a>
<a class="btn" href="student_vote.php">Student Voting</a>
<a class="btn" href="results.php">View Results</a>
</div>

</div>