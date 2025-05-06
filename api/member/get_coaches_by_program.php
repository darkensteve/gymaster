<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

// Check if program ID is provided
if (!isset($_GET['programId'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Program ID is required'
    ]);
    exit();
}

try {
    // Get coaches for the selected program
    $stmt = $conn->prepare(
        "SELECT c.COACH_ID, c.COACH_FNAME, c.COACH_LNAME, c.GENDER
         FROM COACH c
         JOIN PROGRAM_COACH pc ON c.COACH_ID = pc.COACH_ID
         WHERE pc.PROGRAM_ID = :programId AND c.IS_ACTIVE = 1"
    );
    
    $stmt->bindParam(':programId', $_GET['programId']);
    $stmt->execute();
    
    $coaches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'coaches' => $coaches
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
