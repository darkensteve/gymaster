<?php
header('Content-Type: application/json');
require_once '../../config/db_connect.php';

// Get filter parameters
$startDate = isset($_GET['start_date']) && !empty($_GET['start_date']) ? $_GET['start_date'] : null;
$endDate = isset($_GET['end_date']) && !empty($_GET['end_date']) ? $_GET['end_date'] : null;
$subscriptionId = isset($_GET['subscription_id']) && !empty($_GET['subscription_id']) && $_GET['subscription_id'] !== 'all' ? $_GET['subscription_id'] : null;
$programId = isset($_GET['program_id']) && !empty($_GET['program_id']) && $_GET['program_id'] !== 'all' ? $_GET['program_id'] : null;
$memberSearch = isset($_GET['member_search']) && !empty($_GET['member_search']) ? $_GET['member_search'] : null;
$memberId = isset($_GET['member_id']) && !empty($_GET['member_id']) ? $_GET['member_id'] : null;

// Build the SQL query with conditional filters
$sql = "SELECT ms.MEMBER_ID, ms.SUB_ID, ms.START_DATE, ms.END_DATE, ms.IS_ACTIVE, 
        m.MEMBER_FNAME, m.MEMBER_LNAME, m.EMAIL, m.PHONE_NUMBER, m.PROGRAM_ID,
        s.SUB_NAME, s.DURATION, s.PRICE
        FROM MEMBER_SUBSCRIPTION ms
        JOIN `MEMBER` m ON ms.MEMBER_ID = m.MEMBER_ID
        JOIN SUBSCRIPTION s ON ms.SUB_ID = s.SUB_ID
        WHERE 1=1";

$params = array();

// Add date range filter
if ($startDate) {
    $sql .= " AND ms.START_DATE >= ?";
    $params[] = $startDate;
}

if ($endDate) {
    $sql .= " AND ms.END_DATE <= ?";
    $params[] = $endDate;
}

// Add subscription filter
if ($subscriptionId) {
    $sql .= " AND ms.SUB_ID = ?";
    $params[] = $subscriptionId;
}

// Add program filter
if ($programId) {
    $sql .= " AND m.PROGRAM_ID = ?";
    $params[] = $programId;
}

// Check for exact member ID match first (from selection in autocomplete)
if ($memberId) {
    $sql .= " AND m.MEMBER_ID = ?";
    $params[] = $memberId;
}
// Otherwise use member search filter (name or email)
else if ($memberSearch) {
    $sql .= " AND (m.MEMBER_FNAME LIKE ? OR m.MEMBER_LNAME LIKE ? OR m.EMAIL LIKE ?)";
    $searchTerm = "%$memberSearch%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

// Add order by clause
$sql .= " ORDER BY ms.START_DATE DESC";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Bind parameters dynamically
    if (!empty($params)) {
        $types = str_repeat('s', count($params)); // Assuming all params are strings
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
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
    
    $stmt->close();
    
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
        "filtered" => true,
        "total" => count($subscriptions),
        "expiring_count" => count($expiringSubscriptions),
        "subscriptions" => $subscriptions
    ));
} else {
    // Error preparing statement
    echo json_encode(array(
        "success" => false,
        "message" => "Database query error: " . $conn->error
    ));
}

$conn->close();
?> 