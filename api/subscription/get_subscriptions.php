<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    // Get all active subscriptions from the database
    $query = "SELECT SUB_ID, SUB_NAME, DURATION, PRICE FROM SUBSCRIPTION WHERE IS_ACTIVE = 1";
    $stmt = $conn->query($query);
    $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
