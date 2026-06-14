<?php
session_start();
include("db.php");

$house_id = $_GET['house_id'] ?? null;
$isWhiteHouse = ($house_id === 'white');
$message = "";

// ✅ GET USER IP
$ip = $_SERVER['REMOTE_ADDR'];

// ✅ CHECK IF COLUMN EXISTS
$columnCheck = $conn->query("SHOW COLUMNS FROM votes LIKE 'ip_address'");
$ipColumnExists = ($columnCheck && $columnCheck->num_rows > 0);

// ✅ CHECK IF ALREADY VOTED (ONLY IF COLUMN EXISTS)
if ($ipColumnExists) {
    $stmt = $conn->prepare("SELECT id FROM votes WHERE ip_address=? LIMIT 1");
    $stmt->bind_param("s", $ip);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        die("<h2 style='color:red;text-align:center;'>❌ You have already voted!</h2>");
    }
}

// ✅ HANDLE VOTE
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    foreach ($_POST as $key => $value) {

        if (strpos($key, 'vote_') === 0) {

            $candidate_id = intval($value);

            $stmt = $conn->prepare("SELECT position_id FROM candidates WHERE id=?");
            $stmt->bind_param("i", $candidate_id);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();

            if ($row) {
                $position_id = $row['position_id'];

                // ✅ INSERT BASED ON COLUMN EXISTENCE
                if ($ipColumnExists) {
                    $insert = $conn->prepare("
                        INSERT INTO votes (student_id, candidate_id, position_id, voted_at, ip_address)
                        VALUES (NULL, ?, ?, NOW(), ?)
                    ");
                    $insert->bind_param("iis", $candidate_id, $position_id, $ip);
                } else {
                    $insert = $conn->prepare("
                        INSERT INTO votes (student_id, candidate_id, position_id, voted_at)
                        VALUES (NULL, ?, ?, NOW())
                    ");
                    $insert->bind_param("ii", $candidate_id, $position_id);
                }

                $insert->execute();
            }
        }
    }

    $message = "✅ Vote submitted successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Voting System</title>

<style>
body { font-family: Arial; background:#f4f6f9; text-align:center; }

.grid {
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:25px;
    margin-top:40px;
}

.card {
    width:180px;
    height:120px;
    border-radius:12px;
    color:white;
    font-size:18px;
    font-weight:bold;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    transition:0.3s;
}

.card:hover { transform:scale(1.05); }

.sapphire { background:#2563eb; }
.emerald { background:#16a34a; }
.ruby { background:#dc2626; }
.coral { background:#db2777; }
.white { background:#444; }

.section { margin-top:40px; }

.section h2 {
    background:#111;
    color:white;
    padding:10px;
    border-radius:6px;
}

.candidates {
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:20px;
    margin-top:15px;
}

.box {
    background:white;
    padding:15px;
    border-radius:10px;
    width:200px;
    box-shadow:0 2px 8px rgba(0,0,0,0.2);
}

img { width:100px; height:100px; border-radius:50%; }

button {
    margin-top:30px;
    padding:12px 25px;
    background:#2563eb;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-size:16px;
}

.success { color:green; font-weight:bold; margin-top:20px; }

a { text-decoration:none; }
</style>
</head>

<body>

<h2>🗳️ School Voting System</h2>

<?php if(!$house_id){ ?>

<!-- ✅ HOUSE CARDS -->
<div class="grid">
<a href="?house_id=1"><div class="card sapphire">💙 Sapphire</div></a>
<a href="?house_id=4"><div class="card emerald">💚 Emerald</div></a>
<a href="?house_id=3"><div class="card ruby">❤️ Ruby</div></a>
<a href="?house_id=2"><div class="card coral">🩷 Coral</div></a>
<a href="?house_id=white"><div class="card white">🤍 White House</div></a>
</div>

<?php } else { ?>

<a href="public_vote.php">⬅ Back</a>

<?php if($message){ ?>
<p class="success"><?php echo $message; ?></p>
<?php } ?>

<form method="POST">

<?php
if ($isWhiteHouse) {

    $positions = [
        "President",
        "Vice President",
        "Sports Captain",
        "Sports Vice Captain"
    ];

    foreach ($positions as $pos) {

        $stmt = $conn->prepare("
            SELECT c.*, h.house_name
            FROM candidates c
            JOIN positions p ON c.position_id = p.id
            LEFT JOIN houses h ON c.house_id = h.id
            WHERE p.position_name = ?
        ");
        $stmt->bind_param("s", $pos);
        $stmt->execute();
        $result = $stmt->get_result();
?>

<div class="section">
<h2><?php echo strtoupper($pos); ?></h2>

<div class="candidates">

<?php while($c = $result->fetch_assoc()){ ?>

<div class="box">
<?php if($c['photo']){ ?>
<img src="uploads/<?php echo $c['photo']; ?>">
<?php } ?>

<h4><?php echo $c['candidate_name']; ?></h4>
<p><?php echo $c['house_name']; ?></p>

<input type="radio" name="vote_<?php echo $pos; ?>" value="<?php echo $c['id']; ?>" required>
</div>

<?php } ?>

</div>
</div>

<?php } } else {

    $stmt = $conn->prepare("
        SELECT c.*, p.position_name
        FROM candidates c
        JOIN positions p ON c.position_id = p.id
        WHERE c.house_id = ? 
        AND p.position_name IN ('House Captain','House Vice Captain')
    ");
    $stmt->bind_param("i", $house_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $group = [];
    while($row = $result->fetch_assoc()){
        $group[$row['position_name']][] = $row;
    }

    foreach($group as $position => $list){
?>

<div class="section">
<h2><?php echo strtoupper($position); ?></h2>

<div class="candidates">

<?php foreach($list as $c){ ?>

<div class="box">
<?php if($c['photo']){ ?>
<img src="uploads/<?php echo $c['photo']; ?>">
<?php } ?>

<h4><?php echo $c['candidate_name']; ?></h4>

<input type="radio" name="vote_<?php echo $position; ?>" value="<?php echo $c['id']; ?>" required>
</div>

<?php } ?>

</div>
</div>

<?php } } ?>

<button type="submit">Submit Vote</button>

</form>

<?php } ?>

</body>
</html>