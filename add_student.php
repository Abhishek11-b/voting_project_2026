<?php
include('db.php');

if (isset($_POST['submit'])) {

    $student_name = $_POST['student_name'];
$class_name = $_POST['class_name'];

// Generate ID
$student_id = strtoupper(substr($student_name,0,3)) . "_" . rand(1000,9999);

// Password = SAME AS NAME
$plain_password = $student_name;
$password = password_hash($plain_password, PASSWORD_DEFAULT);

$sql = "INSERT INTO students 
(student_id, student_name, class_name, password)
VALUES 
('$student_id','$student_name','$class_name','$password')";
    if (mysqli_query($conn, $sql)) {
        echo "✅ Student Added Successfully!<br>";
        echo "Student ID: <b>$student_id</b><br>";
        echo "Password: <b>$plain_password</b>";
    } else {
        echo mysqli_error($conn);
    }
}
?>
<form method="POST">
   <input type="text" name="student_name" placeholder="Name" required><br>
    <input type="text" name="class_name" placeholder="Class" required><br>
    <button name="submit">Add Student</button>
</form>