<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    // Get active subscriptions
    $subQuery = "SELECT SUB_ID, SUB_NAME FROM SUBSCRIPTION WHERE IS_ACTIVE = 1";
    $subStmt = $conn->query($subQuery);
    $subscriptions = $subStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get active programs
    $progQuery = "SELECT PROGRAM_ID, PROGRAM_NAME FROM PROGRAM WHERE IS_ACTIVE = 1";
    $progStmt = $conn->query($progQuery);
    $programs = $progStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => [
            'subscriptions' => $subscriptions,
            'programs' => $programs
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
