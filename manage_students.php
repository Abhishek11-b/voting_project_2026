<?php
include('db.php');

$result = mysqli_query($conn, "SELECT * FROM students");
?>

<h2>Manage Students</h2>

<table border="1">
<tr>
    <th>ID</th>
    <th>Student ID</th>
    <th>Name</th>
    <th>Department</th>
    <th>Class</th>
    <th>Phone</th>
    <th>Voted</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['student_id']; ?></td>
    <td><?php echo $row['student_name']; ?></td>
    <td><?php echo $row['department']; ?></td>
    <td><?php echo $row['class_name']; ?></td>
    <td><?php echo $row['phone']; ?></td>
    <td><?php echo $row['has_voted'] ? 'Yes' : 'No'; ?></td>
    <td>
        <a href="delete_student.php?id=<?php echo $row['id']; ?>">Delete</a>
    </td>
</tr>
<?php } ?>
</table>
