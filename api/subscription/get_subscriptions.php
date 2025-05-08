<?php
header('Content-Type: application/json');
require_once '../../config/db_connect.php';

// Get member subscriptions from the database
$sql = "SELECT ms.MEMBER_ID, ms.SUB_ID, ms.START_DATE, ms.END_DATE, ms.IS_ACTIVE, 
        m.MEMBER_FNAME, m.MEMBER_LNAME, m.EMAIL, m.PHONE_NUMBER,
        s.SUB_NAME, s.DURATION, s.PRICE
        FROM MEMBER_SUBSCRIPTION ms
        JOIN `MEMBER` m ON ms.MEMBER_ID = m.MEMBER_ID
        JOIN SUBSCRIPTION s ON ms.SUB_ID = s.SUB_ID
        ORDER BY ms.START_DATE DESC";

$result = $conn->query($sql);

$subscriptions = array();

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $subscriptions[] = array(
            "member_id" => $row["MEMBER_ID"],
            "member_name" => $row["MEMBER_FNAME"] . " " . $row["MEMBER_LNAME"],
            "member_email" => $row["EMAIL"],
            "member_phone" => $row["PHONE_NUMBER"],
            "sub_id" => $row["SUB_ID"],
            "sub_name" => $row["SUB_NAME"],
            "duration" => $row["DURATION"],
            "price" => $row["PRICE"],
            "start_date" => $row["START_DATE"],
            "end_date" => $row["END_DATE"],
            "is_active" => $row["IS_ACTIVE"] == 1 ? true : false
        );
    }
}

// Get expiring subscriptions (next 7 days)
$expiringSubscriptions = array();
$currentDate = date('Y-m-d');
$sevenDaysLater = date('Y-m-d', strtotime('+7 days'));

foreach ($subscriptions as $subscription) {
    if ($subscription['is_active'] && $subscription['end_date'] <= $sevenDaysLater && $subscription['end_date'] >= $currentDate) {
        $expiringSubscriptions[] = $subscription;
    }
}

// Return JSON response
echo json_encode(array(
    "success" => true,
    "expiring_count" => count($expiringSubscriptions),
    "subscriptions" => $subscriptions
));

$conn->close();
?> 