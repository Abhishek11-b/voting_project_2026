<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

   $name = trim($_POST["candidate_name"]);
    $position_name = trim($_POST["position"]);
$house_name = trim($_POST["house"]);

// Get or create position
$stmt = $conn->prepare("SELECT id FROM positions WHERE position_name=?");
$stmt->bind_param("s", $position_name);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $position_id = $row['id'];
}else{
    $stmt = $conn->prepare("INSERT INTO positions(position_name) VALUES(?)");
    $stmt->bind_param("s", $position_name);
    $stmt->execute();
    $position_id = $conn->insert_id;
}

// Get or create house
$stmt = $conn->prepare("SELECT id FROM houses WHERE house_name=?");
$stmt->bind_param("s", $house_name);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $house_id = $row['id'];
}else{
    $stmt = $conn->prepare("INSERT INTO houses(house_name) VALUES(?)");
    $stmt->bind_param("s", $house_name);
    $stmt->execute();
    $house_id = $conn->insert_id;
}

    // Check duplicate candidate
   // Skip duplicate check
{

        $photoName = "";

        if (!empty($_FILES["photo"]["name"])) {

            $allowed = ['jpg','jpeg','png','webp'];

            $ext = strtolower(
                pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION)
            );

            if (in_array($ext, $allowed)) {

                $photoName =
                    time() . "_" .
                    preg_replace(
                        '/[^a-zA-Z0-9._-]/',
                        '',
                        $_FILES["photo"]["name"]
                    );

                move_uploaded_file(
                    $_FILES["photo"]["tmp_name"],
                    "uploads/" . $photoName
                );

            } else {

                $msg = "<span style='color:red;'>Only JPG, JPEG, PNG and WEBP files are allowed.</span>";
            }
        }

        if ($msg == "") {

            $stmt = $conn->prepare(
    "INSERT INTO candidates
        (candidate_name, position_id, house_id, photo)
    VALUES (?, ?, ?, ?)"
);

          $stmt->bind_param(
    "siis",
    $name,
    $position_id,
    $house_id,
    $photoName
);

            if ($stmt->execute()) {

                $msg = "<span style='color:green;'>Candidate Added Successfully!</span>";

            } else {

                $msg = "<span style='color:red;'>Database Error!</span>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Candidate</title>

<style>

body{
    margin:0;
    font-family:Arial, sans-serif;
    background:#f3f4f6;
}

.box{
    width:550px;
    margin:40px auto;
    background:#ffffff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 0 15px rgba(0,0,0,0.15);
}

h2{
    text-align:center;
    color:#2563eb;
}

input,
select{
    width:100%;
    padding:12px;
    margin-top:12px;
    border:1px solid #ccc;
    border-radius:8px;
    box-sizing:border-box;
}

button{
    width:100%;
    padding:12px;
    margin-top:15px;
    background:#2563eb;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
}

button:hover{
    background:#1d4ed8;
}

.dashboard-btn{
    display:block;
    text-align:center;
    margin-top:15px;
    text-decoration:none;
    background:#16a34a;
    color:white;
    padding:12px;
    border-radius:8px;
}

.dashboard-btn:hover{
    background:#15803d;
}

.message{
    text-align:center;
    margin-bottom:15px;
    font-weight:bold;
}

</style>

</head>

<body>

<div class="box">

<h2>➕ Add Candidate</h2>

<div class="message">
<?php echo $msg; ?>
</div>

<form method="POST" enctype="multipart/form-data">

<input
type="text"
name="candidate_name"
placeholder="Enter Candidate Name"
required>

<select name="position" required>

<option value="">Select Position</option>

<option>President</option>

<option>Vice President</option>

<option>Sports Captain</option>

<option>Sports Vice Captain</option>

<option>House Captain</option>

<option>House Vice Captain</option>

</select>

<select name="house" required>

<option value="">Select House</option>

<option>Sapphire</option>
<option>Coral</option>
<option>Ruby</option>
<option>Emerald</option>
<option>White</option>

</select>

<input
type="file"
name="photo"
required>

<button type="submit">
Add Candidate
</button>

</form>

<a href="admin_dashboard.php" class="dashboard-btn">
⬅ Back to Dashboard
</a>

</div>

</body>
</html>