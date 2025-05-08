<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit();
}

try {
    // Begin transaction for data integrity
    $conn->beginTransaction();
    
    // Prepare and execute member insertion
    $memberStmt = $conn->prepare(
        "INSERT INTO `MEMBER` (MEMBER_FNAME, MEMBER_LNAME, EMAIL, PHONE_NUMBER, PROGRAM_ID, USER_ID, IS_ACTIVE, JOINED_DATE) 
         VALUES (:firstName, :lastName, :email, :phoneNumber, :programId, :userId, :isActive, CURRENT_DATE())"
    );
    
    // Get the active user ID (admin or staff). In a real app, this would come from a session
    // For now, we'll use the default admin user with ID 1
    $userId = 1;
    
    // Get form data
    $memberStmt->bindParam(':firstName', $_POST['MEMBER_FNAME']);
    $memberStmt->bindParam(':lastName', $_POST['MEMBER_LNAME']);
    $memberStmt->bindParam(':email', $_POST['EMAIL']);
    $memberStmt->bindParam(':phoneNumber', $_POST['PHONE_NUMBER']);
    $memberStmt->bindParam(':programId', $_POST['PROGRAM_ID']);
    $memberStmt->bindParam(':userId', $userId);
    $isActive = isset($_POST['IS_ACTIVE']) ? 1 : 0;
    $memberStmt->bindParam(':isActive', $isActive);
    
    $memberStmt->execute();
    
    // Get the inserted member ID
    $memberId = $conn->lastInsertId();
    
    // Insert member's subscription
    $subStmt = $conn->prepare(
        "INSERT INTO MEMBER_SUBSCRIPTION (MEMBER_ID, SUB_ID, START_DATE, END_DATE, IS_ACTIVE) 
         VALUES (:memberId, :subId, :startDate, :endDate, 1)"
    );
    
    $subStmt->bindParam(':memberId', $memberId);
    $subStmt->bindParam(':subId', $_POST['SUB_ID']);
    $subStmt->bindParam(':startDate', $_POST['START_DATE']);
    $subStmt->bindParam(':endDate', $_POST['END_DATE']);
    $subStmt->execute();
    
    // Insert transaction record
    $transactionStmt = $conn->prepare(
        "INSERT INTO `TRANSACTION` (MEMBER_ID, SUB_ID, PAYMENT_ID, TRANSAC_DATE) 
         VALUES (:memberId, :subId, :paymentId, CURRENT_DATE())"
    );
    
    $transactionStmt->bindParam(':memberId', $memberId);
    $transactionStmt->bindParam(':subId', $_POST['SUB_ID']);
    $transactionStmt->bindParam(':paymentId', $_POST['PAYMENT_ID']);
    $transactionStmt->execute();
    
    // Handle comorbidities if selected
    if (isset($_POST['COMORBIDITIES']) && is_array($_POST['COMORBIDITIES']) && count($_POST['COMORBIDITIES']) > 0) {
        $comorbiditiesStmt = $conn->prepare(
            "INSERT INTO MEMBER_COMORBIDITIES (MEMBER_ID, COMOR_ID) VALUES (:memberId, :comorId)"
        );
        
        foreach ($_POST['COMORBIDITIES'] as $comorId) {
            if (!empty($comorId)) {
                $comorbiditiesStmt->bindParam(':memberId', $memberId);
                $comorbiditiesStmt->bindParam(':comorId', $comorId);
                $comorbiditiesStmt->execute();
            }
        }
    }
    
    // Handle coach assignment if provided
    if (isset($_POST['COACH_ID']) && !empty($_POST['COACH_ID'])) {
        $coachStmt = $conn->prepare(
            "INSERT INTO MEMBER_COACH (MEMBER_ID, COACH_ID, ASSIGNED_DATE) VALUES (:memberId, :coachId, CURRENT_DATE())"
        );
        $coachStmt->bindParam(':memberId', $memberId);
        $coachStmt->bindParam(':coachId', $_POST['COACH_ID']);
        $coachStmt->execute();
    }
    
    // Commit the transaction
    $conn->commit();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Member added successfully',
        'memberId' => $memberId
    ]);
    
} catch (PDOException $e) {
    // If there is an error, roll back the transaction
    $conn->rollBack();
    
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>