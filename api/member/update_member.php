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
    
    // Get member ID from form data
    $memberId = $_POST['memberId'];
    
    // Check if member exists
    $checkStmt = $conn->prepare("SELECT MEMBER_ID FROM `MEMBER` WHERE MEMBER_ID = :memberId");
    $checkStmt->bindParam(':memberId', $memberId);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Member not found'
        ]);
        exit();
    }
    
    // Update member information
    $memberStmt = $conn->prepare(
        "UPDATE `MEMBER` 
         SET MEMBER_FNAME = :firstName, 
             MEMBER_LNAME = :lastName, 
             EMAIL = :email, 
             PHONE_NUMBER = :phoneNumber, 
             PROGRAM_ID = :programId, 
             IS_ACTIVE = :isActive
         WHERE MEMBER_ID = :memberId"
    );
    
    $memberStmt->bindParam(':firstName', $_POST['MEMBER_FNAME']);
    $memberStmt->bindParam(':lastName', $_POST['MEMBER_LNAME']);
    $memberStmt->bindParam(':email', $_POST['EMAIL']);
    $memberStmt->bindParam(':phoneNumber', $_POST['PHONE_NUMBER']);
    $memberStmt->bindParam(':programId', $_POST['PROGRAM_ID']);
    $isActive = isset($_POST['IS_ACTIVE']) ? 1 : 0;
    $memberStmt->bindParam(':isActive', $isActive);
    $memberStmt->bindParam(':memberId', $memberId);
    
    $memberStmt->execute();
    
    // Handle coach assignment if changed
    if (isset($_POST['COACH_ID']) && !empty($_POST['COACH_ID'])) {
        // First, check if there is an existing coach assignment
        $checkCoachStmt = $conn->prepare(
            "SELECT COACH_ID FROM MEMBER_COACH WHERE MEMBER_ID = :memberId"
        );
        $checkCoachStmt->bindParam(':memberId', $memberId);
        $checkCoachStmt->execute();
        
        if ($checkCoachStmt->rowCount() > 0) {
            // Update existing coach assignment
            $updateCoachStmt = $conn->prepare(
                "UPDATE MEMBER_COACH SET COACH_ID = :coachId, ASSIGNED_DATE = CURRENT_DATE() 
                 WHERE MEMBER_ID = :memberId"
            );
            $updateCoachStmt->bindParam(':coachId', $_POST['COACH_ID']);
            $updateCoachStmt->bindParam(':memberId', $memberId);
            $updateCoachStmt->execute();
        } else {
            // Insert new coach assignment
            $insertCoachStmt = $conn->prepare(
                "INSERT INTO MEMBER_COACH (MEMBER_ID, COACH_ID, ASSIGNED_DATE) 
                 VALUES (:memberId, :coachId, CURRENT_DATE())"
            );
            $insertCoachStmt->bindParam(':memberId', $memberId);
            $insertCoachStmt->bindParam(':coachId', $_POST['COACH_ID']);
            $insertCoachStmt->execute();
        }
    }
    
    // Handle comorbidities if any
    if (isset($_POST['COMORBIDITIES'])) {
        // First, remove all existing comorbidities for this member
        $deleteComorbidities = $conn->prepare(
            "DELETE FROM MEMBER_COMORBIDITIES WHERE MEMBER_ID = :memberId"
        );
        $deleteComorbidities->bindParam(':memberId', $memberId);
        $deleteComorbidities->execute();
        
        // Now insert the selected comorbidities
        if (is_array($_POST['COMORBIDITIES']) && count($_POST['COMORBIDITIES']) > 0) {
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
    }
    
    // Commit the transaction
    $conn->commit();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Member updated successfully',
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