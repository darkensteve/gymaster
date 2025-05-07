<?php
require_once '../../config/database.php';

header('Content-Type: application/json');

try {
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    if (empty($search)) {
        echo json_encode([
            'success' => true,
            'members' => []
        ]);
        exit;
    }
    
    $query = "
        SELECT 
            m.MEMBER_ID,
            m.MEMBER_FNAME,
            m.MEMBER_LNAME,
            m.EMAIL,
            m.PHONE_NUMBER,
            p.PROGRAM_NAME
        FROM `MEMBER` m
        LEFT JOIN PROGRAM p ON m.PROGRAM_ID = p.PROGRAM_ID
        WHERE
            m.IS_ACTIVE = 1
            AND (
                m.MEMBER_FNAME LIKE :search
                OR m.MEMBER_LNAME LIKE :search
                OR CONCAT(m.MEMBER_FNAME, ' ', m.MEMBER_LNAME) LIKE :search
                OR m.EMAIL LIKE :search
                OR m.PHONE_NUMBER LIKE :search
            )
        ORDER BY m.MEMBER_FNAME, m.MEMBER_LNAME
        LIMIT 10
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search', '%' . $search . '%');
    $stmt->execute();
    
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'members' => $members
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error searching members: ' . $e->getMessage()
    ]);
}
?>
