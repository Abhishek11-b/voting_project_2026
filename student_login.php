<?php
session_start();
include("db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

 $student_name = trim($_POST["student_name"]);
$password = trim($_POST["password"]);

$stmt = $conn->prepare(
    "SELECT * FROM students WHERE LOWER(student_name)=LOWER(?)"
);

$stmt->bind_param("s", $student_name);
$stmt->execute();

$result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $row = $result->fetch_assoc();

        // Check password
        if (password_verify($password, $row['password'])) {

            $_SESSION["student_id"] = $row['student_id'];
            header("Location: student_dashboard.php");
            exit();

        } else {
            $message = "Wrong Password";
        }

    } else {
        $message = "Student Not Found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Student Login</title>

<style>
body{
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#2563eb,#0f172a);
}

.login-box{
    width:400px;
    margin:80px auto;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 0 15px rgba(0,0,0,.3);
}

input{
    width:100%;
    padding:12px;
    margin-top:12px;
    box-sizing:border-box;
}

button{
    width:100%;
    padding:12px;
    margin-top:20px;
    border:none;
    background:#2563eb;
    color:#fff;
    cursor:pointer;
}

button:hover{
    background:#1d4ed8;
}

.error{
    color:red;
    text-align:center;
}
</style>

</head>
<body>

<div class="login-box">

<h2 align="center">🎓 Student Login</h2>

<?php
if($message!=""){
    echo "<p class='error'>$message</p>";
}
?>
<form method="POST">

<input
type="text"
name="student_name"
placeholder="Enter Your Name"
required>

<input
type="password"
name="password"
placeholder="Enter Password (same as name)"
required>

<button type="submit">
Login
</button>

</form>

</div>

</body>
</html>