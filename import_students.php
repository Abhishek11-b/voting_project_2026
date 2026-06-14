<?php
include("db.php");

if (isset($_POST['upload'])) {

    $file = fopen($_FILES['file']['tmp_name'], "r");

    while (($data = fgetcsv($file)) !== FALSE) {

        $student_name = trim($data[0]);
        $class_name = trim($data[1]);

        // Generate ID
        $student_id = strtoupper(substr($student_name,0,3)) . "_" . rand(1000,9999);

        // Password = name
        $password = password_hash($student_name, PASSWORD_DEFAULT);

        $sql = "INSERT INTO students (student_id, student_name, class_name, password)
                VALUES ('$student_id','$student_name','$class_name','$password')";

        mysqli_query($conn, $sql);
    }

    fclose($file);

    echo "Students Imported Successfully!";
}
?>

<h2>Upload Students CSV</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <button name="upload">Upload</button>
</form>