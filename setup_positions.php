<?php

include("db.php");

$positions = [
    "President",
    "Vice President",
    "Sports Captain",
    "Sports Vice Captain",
    "House Captain",
    "House Vice Captain"
];

foreach($positions as $position){

    $check = $conn->prepare(
        "SELECT id FROM positions WHERE position_name=?"
    );

    $check->bind_param("s", $position);
    $check->execute();

    if($check->get_result()->num_rows == 0){

        $insert = $conn->prepare(
            "INSERT INTO positions (position_name)
             VALUES (?)"
        );

        $insert->bind_param("s", $position);
        $insert->execute();
    }
}

echo "Positions Updated Successfully";
?>