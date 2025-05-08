<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    // Get all active programs from the database
    $query = "SELECT PROGRAM_ID, PROGRAM_NAME FROM PROGRAM WHERE IS_ACTIVE = 1";
    $stmt = $conn->query($query);
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'programs' => $programs
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>