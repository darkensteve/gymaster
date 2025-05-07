<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    // Fetch active subscriptions with member details
    $query = "SELECT 
                ms.MEMBER_ID,
                ms.SUB_ID,
                ms.START_DATE,
                ms.END_DATE,
                ms.IS_ACTIVE as SUB_ACTIVE,
                m.MEMBER_FNAME,
                m.MEMBER_LNAME,
                m.EMAIL,
                s.SUB_NAME,
                s.PRICE,
                t.TRANSAC_DATE as PAID_DATE,
                p.PAY_METHOD
              FROM MEMBER_SUBSCRIPTION ms
              JOIN `MEMBER` m ON ms.MEMBER_ID = m.MEMBER_ID
              JOIN SUBSCRIPTION s ON ms.SUB_ID = s.SUB_ID
              LEFT JOIN `TRANSACTION` t ON ms.MEMBER_ID = t.MEMBER_ID AND ms.SUB_ID = t.SUB_ID
              LEFT JOIN PAYMENT p ON t.PAYMENT_ID = p.PAYMENT_ID
              ORDER BY ms.END_DATE ASC";
    
    $stmt = $conn->query($query);
    $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate subscription status based on end date
    $today = new DateTime();
    foreach ($subscriptions as &$subscription) {
        $endDate = new DateTime($subscription['END_DATE']);
        $daysLeft = $today->diff($endDate)->days;
        $isPast = $today > $endDate;
        
        // Check if end date is in the past or subscription is explicitly inactive
        if ($isPast || $subscription['SUB_ACTIVE'] == 0) {
            $subscription['STATUS'] = 'Inactive';
        } 
        // Check if subscription expires within 7 days
        else if ($daysLeft <= 7) {
            $subscription['STATUS'] = 'Expiring Soon';
            $subscription['DAYS_LEFT'] = $daysLeft;
        } 
        else {
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
