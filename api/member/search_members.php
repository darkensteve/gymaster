<?php
require_once '../../config/database.php';
header('Content-Type: application/json');

try {
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    // Only proceed if at least 2 characters are entered
    if (strlen($search) < 2) {
        echo json_encode(['success' => true, 'members' => []]);
        exit;
    }
    
    $query = "
        SELECT 
            m.MEMBER_ID,
            m.MEMBER_FNAME,
            m.MEMBER_LNAME,
            m.EMAIL,
            m.PHONE_NUMBER,
            p.PROGRAM_NAME,
            p.PROGRAM_ID
        FROM `MEMBER` m
        LEFT JOIN PROGRAM p ON m.PROGRAM_ID = p.PROGRAM_ID
        WHERE m.IS_ACTIVE = 1
        AND (
            m.MEMBER_FNAME LIKE :search
            OR m.MEMBER_LNAME LIKE :search
            OR CONCAT(m.MEMBER_FNAME, ' ', m.MEMBER_LNAME) LIKE :search
            OR m.EMAIL LIKE :search
        )
        ORDER BY 
            CASE 
                WHEN m.MEMBER_FNAME LIKE :exactSearch THEN 1
                WHEN m.MEMBER_LNAME LIKE :exactSearch THEN 2
                ELSE 3
            END,
            m.MEMBER_FNAME, 
            m.MEMBER_LNAME
        LIMIT 8
    ";
    
    $stmt = $conn->prepare($query);
    $searchTerm = '%' . $search . '%';
    $exactSearch = $search . '%';
    $stmt->bindValue(':search', $searchTerm);
    $stmt->bindValue(':exactSearch', $exactSearch);
    $stmt->execute();
    
    echo json_encode([
        'success' => true,
        'members' => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error searching members: ' . $e->getMessage()
    ]);
}
?>
