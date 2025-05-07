<?php
require_once '../../config/database.php';
header('Content-Type: application/json');

try {
    if (!isset($_POST['memberId']) || !isset($_POST['subId'])) {
        throw new Exception('Missing required parameters');
    }

    $memberId = $_POST['memberId'];
    $subId = $_POST['subId'];

    // Update the subscription status to inactive
    $query = "UPDATE MEMBER_SUBSCRIPTION 
              SET IS_ACTIVE = 0 
              WHERE MEMBER_ID = :memberId 
              AND SUB_ID = :subId 
              AND IS_ACTIVE = 1";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':memberId', $memberId, PDO::PARAM_INT);
    $stmt->bindParam(':subId', $subId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Subscription deactivated successfully'
        ]);
    } else {
        throw new Exception('No active subscription found or already deactivated');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
