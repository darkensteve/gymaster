<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    // Get filter parameters
    $startDate = $_GET['startDate'] ?? null;
    $endDate = $_GET['endDate'] ?? null;
    $subscriptionId = $_GET['subscriptionId'] ?? null;
    $programId = $_GET['programId'] ?? null;
    $memberSearch = $_GET['memberSearch'] ?? null;

    // Build base query
    $query = "SELECT 
                ms.MEMBER_ID,
                ms.SUB_ID,
                ms.START_DATE,
                ms.END_DATE,
                ms.IS_ACTIVE as SUB_ACTIVE,
                m.MEMBER_FNAME,
                m.MEMBER_LNAME,
                m.EMAIL,
                m.PROGRAM_ID,
                s.SUB_NAME,
                s.PRICE,
                t.TRANSAC_DATE as PAID_DATE,
                p.PAY_METHOD
              FROM MEMBER_SUBSCRIPTION ms
              JOIN `MEMBER` m ON ms.MEMBER_ID = m.MEMBER_ID
              JOIN SUBSCRIPTION s ON ms.SUB_ID = s.SUB_ID
              LEFT JOIN `TRANSACTION` t ON ms.MEMBER_ID = t.MEMBER_ID AND ms.SUB_ID = t.SUB_ID
              LEFT JOIN PAYMENT p ON t.PAYMENT_ID = p.PAYMENT_ID
              WHERE 1=1";

    $params = array();

    // Add date range filter
    if ($startDate && $endDate) {
        $query .= " AND ms.START_DATE BETWEEN :startDate AND :endDate";
        $params[':startDate'] = $startDate;
        $params[':endDate'] = $endDate;
    }

    // Add subscription filter
    if ($subscriptionId && $subscriptionId !== 'all') {
        $query .= " AND s.SUB_ID = :subscriptionId";
        $params[':subscriptionId'] = $subscriptionId;
    }

    // Add program filter
    if ($programId && $programId !== 'all') {
        $query .= " AND m.PROGRAM_ID = :programId";
        $params[':programId'] = $programId;
    }

    // Add member search filter
    if ($memberSearch) {
        $query .= " AND (m.MEMBER_FNAME LIKE :memberSearch 
                        OR m.MEMBER_LNAME LIKE :memberSearch 
                        OR m.EMAIL LIKE :memberSearch)";
        $params[':memberSearch'] = "%$memberSearch%";
    }

    $query .= " ORDER BY ms.END_DATE ASC";

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate subscription status based on end date
    $today = new DateTime();
    foreach ($subscriptions as &$subscription) {
        $endDate = new DateTime($subscription['END_DATE']);
        $daysLeft = $today->diff($endDate)->days;
        $isPast = $today > $endDate;
        
        if ($isPast || $subscription['SUB_ACTIVE'] == 0) {
            $subscription['STATUS'] = 'Inactive';
        } elseif ($daysLeft <= 7) {
            $subscription['STATUS'] = 'Expiring Soon';
            $subscription['DAYS_LEFT'] = $daysLeft;
        } else {
            $subscription['STATUS'] = 'Active';
            $subscription['DAYS_LEFT'] = $daysLeft;
        }
    }

    echo json_encode([
        'success' => true,
        'subscriptions' => $subscriptions
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
