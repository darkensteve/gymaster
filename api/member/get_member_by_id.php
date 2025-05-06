<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

// Check if member ID is provided
if (!isset($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Member ID is required'
    ]);
    exit();
}

try {
    $memberId = $_GET['id'];
    
    // Query to get member details with all related information
    $query = "SELECT 
                m.MEMBER_ID, 
                m.MEMBER_FNAME, 
                m.MEMBER_LNAME, 
                m.EMAIL, 
                m.PHONE_NUMBER,
                m.IS_ACTIVE,
                m.JOINED_DATE,
                p.PROGRAM_ID,
                p.PROGRAM_NAME,
                ms.START_DATE,
                ms.END_DATE,
                s.SUB_NAME,
                s.SUB_ID,
                pay.PAYMENT_ID,
                pay.PAY_METHOD,
                c.COACH_FNAME,
                c.COACH_LNAME,
                c.COACH_ID
              FROM `MEMBER` m
              LEFT JOIN PROGRAM p ON m.PROGRAM_ID = p.PROGRAM_ID
              LEFT JOIN (
                SELECT ms1.MEMBER_ID, ms1.SUB_ID, ms1.START_DATE, ms1.END_DATE
                FROM MEMBER_SUBSCRIPTION ms1
                WHERE ms1.MEMBER_ID = :memberId
                ORDER BY ms1.END_DATE DESC
                LIMIT 1
              ) ms ON m.MEMBER_ID = ms.MEMBER_ID
              LEFT JOIN SUBSCRIPTION s ON ms.SUB_ID = s.SUB_ID
              LEFT JOIN MEMBER_COACH mc ON m.MEMBER_ID = mc.MEMBER_ID
              LEFT JOIN COACH c ON mc.COACH_ID = c.COACH_ID
              LEFT JOIN (
                SELECT t.MEMBER_ID, t.PAYMENT_ID 
                FROM `TRANSACTION` t
                WHERE t.MEMBER_ID = :memberId
                ORDER BY t.TRANSAC_DATE DESC
                LIMIT 1
              ) t ON m.MEMBER_ID = t.MEMBER_ID
              LEFT JOIN PAYMENT pay ON t.PAYMENT_ID = pay.PAYMENT_ID
              WHERE m.MEMBER_ID = :memberId";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':memberId', $memberId);
    $stmt->execute();
    
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$member) {
        echo json_encode([
            'success' => false,
            'message' => 'Member not found'
        ]);
        exit();
    }
    
    // Get comorbidities for this member
    $comorQuery = "SELECT c.COMOR_ID, c.COMOR_NAME 
                   FROM MEMBER_COMORBIDITIES mc 
                   JOIN COMORBIDITIES c ON mc.COMOR_ID = c.COMOR_ID 
                   WHERE mc.MEMBER_ID = :memberId";
    $comorStmt = $conn->prepare($comorQuery);
    $comorStmt->bindParam(':memberId', $memberId);
    $comorStmt->execute();
    $comorbidities = $comorStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Add comorbidities to member data
    $member['comorbidities'] = $comorbidities;
    
    echo json_encode([
        'success' => true,
        'member' => $member
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
