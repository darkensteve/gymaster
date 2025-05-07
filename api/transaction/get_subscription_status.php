<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    $query = "SELECT 
        m.MEMBER_ID,
        m.MEMBER_FNAME,
        m.MEMBER_LNAME,
        m.EMAIL,
        s.SUB_NAME,
        ms.START_DATE,
        ms.END_DATE,
        ms.IS_ACTIVE as SUB_ACTIVE,
        t.TRANSAC_DATE as PAID_DATE,
        p.PAY_METHOD
    FROM MEMBER_SUBSCRIPTION ms
    JOIN `MEMBER` m ON ms.MEMBER_ID = m.MEMBER_ID
    JOIN SUBSCRIPTION s ON ms.SUB_ID = s.SUB_ID
    LEFT JOIN `TRANSACTION` t ON (ms.MEMBER_ID = t.MEMBER_ID AND ms.SUB_ID = t.SUB_ID)
    LEFT JOIN PAYMENT p ON t.PAYMENT_ID = p.PAYMENT_ID
    ORDER BY ms.END_DATE ASC";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Process each subscription to determine status
    foreach ($subscriptions as &$sub) {
        $endDate = new DateTime($sub['END_DATE']);
        $today = new DateTime();
        $daysDiff = $today->diff($endDate)->days;
        
        if ($sub['SUB_ACTIVE'] == 0) {
            $sub['STATUS'] = 'Inactive';
            $sub['STATUS_CLASS'] = 'bg-red-100 text-red-800';
        } else if ($today > $endDate) {
            $sub['STATUS'] = 'Expired';
            $sub['STATUS_CLASS'] = 'bg-red-100 text-red-800';
        } else if ($daysDiff <= 7) {
            $sub['STATUS'] = 'Expiring Soon';
            $sub['STATUS_CLASS'] = 'bg-yellow-100 text-yellow-800';
        } else {
            $sub['STATUS'] = 'Active';
            $sub['STATUS_CLASS'] = 'bg-green-100 text-green-800';
        }

        // Format dates
        $sub['START_DATE'] = date('M d, Y', strtotime($sub['START_DATE']));
        $sub['END_DATE'] = date('M d, Y', strtotime($sub['END_DATE']));
        $sub['PAID_DATE'] = $sub['PAID_DATE'] ? date('M d, Y', strtotime($sub['PAID_DATE'])) : '-';
    }

    // Even if no subscriptions are found, return success with empty array
    echo json_encode([
        'success' => true,
        'subscriptions' => $subscriptions ?? []
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
