<?php
session_start();
include("db.php");

if (!isset($_SESSION["student_id"])) {
    header("Location: student_login.php");
    exit();
}

$house = $_GET["house"] ?? "";

$stmt = $conn->prepare("
    SELECT
        c.id,
        c.candidate_name,
        c.photo,
        p.position_name,
        h.house_name
    FROM candidates c
    INNER JOIN positions p ON c.position_id = p.id
    INNER JOIN houses h ON c.house_id = h.id
    WHERE h.house_name = ?
    ORDER BY p.position_name, c.candidate_name
");

$stmt->bind_param("s", $house);
$stmt->execute();
$result = $stmt->get_result();

$currentPosition = "";
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Vote - <?php echo htmlspecialchars($house); ?></title>

<style>
body{
    font-family: Arial, sans-serif;
    background:#f3f4f6;
    margin:20px;
}

.position-title{
    background:#2563eb;
    color:#fff;
    padding:10px;
    border-radius:8px;
    margin-top:25px;
}

.cards{
    display:flex;
    flex-wrap:wrap;
    gap:20px;
    margin-top:15px;
}

.card{
    width:260px;
    background:#fff;
    border-radius:10px;
    padding:15px;
    text-align:center;
    box-shadow:0 2px 8px rgba(0,0,0,0.15);
}

.card img{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
}

button{
    margin-top:10px;
    padding:10px 18px;
    border:none;
    border-radius:6px;
    background:#2563eb;
    color:#fff;
    cursor:pointer;
}

button:hover{
    opacity:0.9;
}
</style>

</head>
<body>

<h1><?php echo htmlspecialchars($house); ?> House Candidates</h1>

<?php
if ($result->num_rows == 0) {
    echo "<h3>No candidates found for this house.</h3>";
} else {

    while ($row = $result->fetch_assoc()) {

        if ($currentPosition != $row["position_name"]) {

            if ($currentPosition != "") {
                echo "</div>";
            }

            $currentPosition = $row["position_name"];

            echo "<div class='position-title'><h2>" .
                 htmlspecialchars($currentPosition) .
                 "</h2></div>";

            echo "<div class='cards'>";
        }
        ?>

        <div class="card">

            <?php if (!empty($row["photo"])) { ?>
                <img src="uploads/<?php echo htmlspecialchars($row["photo"]); ?>" alt="Candidate">
            <?php } else { ?>
                <p>No Photo</p>
            <?php } ?>

            <h3><?php echo htmlspecialchars($row["candidate_name"]); ?></h3>

            <form action="submit_vote.php" method="POST">
                <input
                    type="hidden"
                    name="candidate_id"
                    value="<?php echo $row["id"]; ?>">

                <button type="submit">Vote</button>
            </form>

        </div>

        <?php
    }

    echo "</div>";
}
?>

</body>
</html>