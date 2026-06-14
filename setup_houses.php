<?php

include("db.php");

$houses = [
    "Sapphire",
    "Coral",
    "Ruby",
    "Emerald",
    "School",
    "White"
];

foreach($houses as $house){

    $check = $conn->prepare(
        "SELECT id FROM houses WHERE house_name=?"
    );

    $check->bind_param("s", $house);
    $check->execute();

    if($check->get_result()->num_rows == 0){

        $insert = $conn->prepare(
            "INSERT INTO houses (house_name)
             VALUES (?)"
        );

        $insert->bind_param("s", $house);
        $insert->execute();
    }
}

echo "Houses Updated Successfully";
?>