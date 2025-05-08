<?php
header('Content-Type: application/json');
require_once '../../config/db_connect.php';

// Get subscription options from the database
$sql = "SELECT SUB_ID, SUB_NAME, DURATION, PRICE, IS_ACTIVE 
        FROM SUBSCRIPTION 
        WHERE IS_ACTIVE = 1
        ORDER BY SUB_NAME ASC";

$result = $conn->query($sql);

$subscriptions = array();

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $subscriptions[] = array(
            "id" => $row["SUB_ID"],
            "name" => $row["SUB_NAME"],
            "duration" => $row["DURATION"],
            "price" => $row["PRICE"]
        );
    }
}

// Return JSON response
echo json_encode(array(
    "success" => true,
    "subscriptions" => $subscriptions
));

$conn->close();
?> 