<!--Username: admin
Password: admin123-->

<?php
session_start();
include("db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare(
        "SELECT * FROM admins WHERE username=? AND password=?"
    );

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $_SESSION["admin"] = $username;

        header("Location: admin_dashboard.php");
        exit();

    } else {

        $message = "Invalid Username or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Admin Login</title>

<style>

body{
    margin:0;
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
}

.login-box{

    width:380px;
    margin:80px auto;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 0 15px rgba(0,0,0,.3);

}

h2{

    text-align:center;

}

input{

    width:100%;
    padding:12px;
    margin-top:15px;
    box-sizing:border-box;

}

button{

    width:100%;
    padding:12px;
    margin-top:20px;
    border:none;
    background:#2563eb;
    color:white;
    cursor:pointer;
    font-size:16px;

}

button:hover{

    background:#1d4ed8;

}

.error{

    color:red;
    text-align:center;
    margin-top:10px;

}

</style>

</head>

<body>

<div class="login-box">

<h2>🔐 Admin Login</h2>

<?php
if($message!=""){
    echo "<div class='error'>$message</div>";
}
?>

<form method="POST">

<input
type="text"
name="username"
placeholder="Admin Username"
required>

<input
type="password"
name="password"
placeholder="Password"
required>

<button type="submit">
Login
</button>

</form>

</div>

</body>
</html>