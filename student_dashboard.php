<?php
session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: student_login.php");
    exit();
}

$student = $_SESSION["student_id"];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Student Dashboard</title>

<style>
body{
    margin:0;
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#0f172a,#2563eb);
}

.header{
    background:#111827;
    color:white;
    padding:20px;
    text-align:center;
}

.container{
    width:90%;
    margin:auto;
    padding:30px;
}

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.card{
    background:white;
    border-radius:15px;
    padding:25px;
    text-align:center;
    text-decoration:none;
    color:black;
    box-shadow:0 4px 10px rgba(0,0,0,.2);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card h2{
    margin:10px 0;
}

.logout{
    margin-top:30px;
    text-align:center;
}

.logout a{
    text-decoration:none;
    background:red;
    color:white;
    padding:12px 20px;
    border-radius:8px;
}
</style>

</head>

<body>

<div class="header">
    <h1>🗳️ Student Voting Portal</h1>
    <p>Welcome: <?php echo htmlspecialchars($student); ?></p>
</div>

<div class="container">

    <div class="cards">

<a class="card" href="student_vote.php?type=sapphire&house_id=1">
    <h2>💙 Sapphire House</h2>
</a>

<a class="card" href="student_vote.php?type=coral&house_id=2">
    <h2>🩷 Coral House</h2>
</a>

<a class="card" href="student_vote.php?type=ruby&house_id=3">
    <h2>❤️ Ruby House</h2>
</a>

<a class="card" href="student_vote.php?type=emerald&house_id=4">
    <h2>💚 Emerald House</h2>
</a>

<a class="card" href="student_vote.php?type=white">
    <h2>🤍 White House</h2>
</a>
    </div>

</div>

</body>
</html>