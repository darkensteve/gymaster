<?php
header('Content-Type: application/json');
require_once '../../config/db_connect.php';

// Get program options from the database
$sql = "SELECT PROGRAM_ID, PROGRAM_NAME, IS_ACTIVE 
        FROM PROGRAM 
        WHERE IS_ACTIVE = 1
        ORDER BY PROGRAM_NAME ASC";

$result = $conn->query($sql);

$programs = array();

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $programs[] = array(
            "id" => $row["PROGRAM_ID"],
            "name" => $row["PROGRAM_NAME"]
        );
    }
}

// Return JSON response
echo json_encode(array(
    "success" => true,
    "programs" => $programs
));

$conn->close();
?> 