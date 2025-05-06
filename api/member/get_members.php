<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    // Modified query to prevent duplicates by using GROUP BY on MEMBER_ID
    // Also improved the JOIN structure for coaches
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
                c.COACH_FNAME,
                c.COACH_LNAME,
                c.COACH_ID
              FROM `MEMBER` m
              LEFT JOIN PROGRAM p ON m.PROGRAM_ID = p.PROGRAM_ID
              LEFT JOIN (
                SELECT MEMBER_ID, SUB_ID, START_DATE, END_DATE
                FROM MEMBER_SUBSCRIPTION
                WHERE (END_DATE >= CURRENT_DATE() OR END_DATE IS NULL)
                GROUP BY MEMBER_ID
                ORDER BY START_DATE DESC
              ) ms ON m.MEMBER_ID = ms.MEMBER_ID
              LEFT JOIN SUBSCRIPTION s ON ms.SUB_ID = s.SUB_ID
              LEFT JOIN MEMBER_COACH mc ON m.MEMBER_ID = mc.MEMBER_ID
              LEFT JOIN COACH c ON mc.COACH_ID = c.COACH_ID
              GROUP BY m.MEMBER_ID
              ORDER BY m.MEMBER_ID DESC";
    
    // Optional program filter
    if (!empty($_GET['programId'])) {
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
                    c.COACH_FNAME,
                    c.COACH_LNAME,
                    c.COACH_ID
                  FROM `MEMBER` m
                  LEFT JOIN PROGRAM p ON m.PROGRAM_ID = p.PROGRAM_ID
                  LEFT JOIN (
                    SELECT MEMBER_ID, SUB_ID, START_DATE, END_DATE
                    FROM MEMBER_SUBSCRIPTION
                    WHERE (END_DATE >= CURRENT_DATE() OR END_DATE IS NULL)
                    GROUP BY MEMBER_ID
                    ORDER BY START_DATE DESC
                  ) ms ON m.MEMBER_ID = ms.MEMBER_ID
                  LEFT JOIN SUBSCRIPTION s ON ms.SUB_ID = s.SUB_ID
                  LEFT JOIN MEMBER_COACH mc ON m.MEMBER_ID = mc.MEMBER_ID
                  LEFT JOIN COACH c ON mc.COACH_ID = c.COACH_ID
                  WHERE p.PROGRAM_ID = :programId
                  GROUP BY m.MEMBER_ID
                  ORDER BY m.MEMBER_ID DESC";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':programId', $_GET['programId']);
        $stmt->execute();
    } else {
        $stmt = $conn->query($query);
    }
    
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Process the result to include comorbidities
    foreach ($members as &$member) {
        // Get comorbidities for this member
        $comorQuery = "SELECT c.COMOR_ID, c.COMOR_NAME 
                       FROM MEMBER_COMORBIDITIES mc 
                       JOIN COMORBIDITIES c ON mc.COMOR_ID = c.COMOR_ID 
                       WHERE mc.MEMBER_ID = :memberId";
        $comorStmt = $conn->prepare($comorQuery);
        $comorStmt->bindParam(':memberId', $member['MEMBER_ID']);
        $comorStmt->execute();
        $comorbidities = $comorStmt->fetchAll(PDO::FETCH_ASSOC);
        
        $member['comorbidities'] = $comorbidities;
    }
    
    echo json_encode([
        'success' => true,
        'members' => $members
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
