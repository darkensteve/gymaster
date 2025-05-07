<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    // Get filter parameters
    $startDate = $_GET['startDate'] ?? '';
    $endDate = $_GET['endDate'] ?? '';
    $subId = $_GET['subscription'] ?? 'all';
    $programId = $_GET['program'] ?? 'all';
    $searchTerm = $_GET['search'] ?? '';

    // Base query with improved joins and conditions
    $query = "SELECT DISTINCT
        m.MEMBER_ID,
        m.MEMBER_FNAME,
        m.MEMBER_LNAME,
        m.EMAIL,
        s.SUB_NAME,
        ms.START_DATE,
        ms.END_DATE,
        ms.IS_ACTIVE as SUB_ACTIVE,
        t.TRANSAC_DATE as PAID_DATE,
        p.PAY_METHOD,
        pr.PROGRAM_NAME,
        pr.PROGRAM_ID
    FROM MEMBER_SUBSCRIPTION ms
    INNER JOIN `MEMBER` m ON ms.MEMBER_ID = m.MEMBER_ID
    INNER JOIN SUBSCRIPTION s ON ms.SUB_ID = s.SUB_ID
    INNER JOIN PROGRAM pr ON m.PROGRAM_ID = pr.PROGRAM_ID
    LEFT JOIN `TRANSACTION` t ON (ms.MEMBER_ID = t.MEMBER_ID AND ms.SUB_ID = t.SUB_ID)
    LEFT JOIN PAYMENT p ON t.PAYMENT_ID = p.PAYMENT_ID
    WHERE 1=1";

    // Improved date filtering
    if ($startDate) {
        $query .= " AND (ms.START_DATE >= :startDate OR ms.END_DATE >= :startDate)";
    }
    if ($endDate) {
        $query .= " AND (ms.START_DATE <= :endDate OR ms.END_DATE <= :endDate)";
    }

    // Subscription filter
    if ($subId !== 'all') {
        $query .= " AND s.SUB_ID = :subId";
    }

    // Program filter
    if ($programId !== 'all') {
        $query .= " AND pr.PROGRAM_ID = :programId";
    }

    // Improved search condition
    if ($searchTerm) {
        $query .= " AND (
            m.MEMBER_FNAME LIKE :search 
            OR m.MEMBER_LNAME LIKE :search 
            OR CONCAT(m.MEMBER_FNAME, ' ', m.MEMBER_LNAME) LIKE :search 
            OR m.EMAIL LIKE :search
        )";
    }

    // Add proper ordering
    $query .= " ORDER BY 
        CASE 
            WHEN ms.END_DATE >= CURRENT_DATE AND ms.IS_ACTIVE = 1 THEN 1
            WHEN ms.END_DATE < CURRENT_DATE THEN 2
            ELSE 3
        END,
        ms.END_DATE ASC";

    $stmt = $conn->prepare($query);

    // Bind parameters with proper date formatting
    if ($startDate) {
        $stmt->bindValue(':startDate', date('Y-m-d', strtotime($startDate)));
    }
    if ($endDate) {
        $stmt->bindValue(':endDate', date('Y-m-d', strtotime($endDate)));
    }
    if ($subId !== 'all') {
        $stmt->bindValue(':subId', $subId);
    }
    if ($programId !== 'all') {
        $stmt->bindValue(':programId', $programId);
    }
    if ($searchTerm) {
        $stmt->bindValue(':search', '%' . $searchTerm . '%');
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Process status for each result with more accurate status determination
    foreach ($results as &$result) {
        $endDate = new DateTime($result['END_DATE']);
        $today = new DateTime();
        $daysDiff = $today->diff($endDate)->days;
        $isPast = $today > $endDate;
        
        if ($result['SUB_ACTIVE'] == 0) {
            $result['STATUS'] = 'Inactive';
            $result['STATUS_CLASS'] = 'bg-red-100 text-red-800';
        } else if ($isPast) {
            $result['STATUS'] = 'Expired';
            $result['STATUS_CLASS'] = 'bg-red-100 text-red-800';
        } else if ($daysDiff <= 7) {
            $result['STATUS'] = 'Expiring Soon';
            $result['STATUS_CLASS'] = 'bg-yellow-100 text-yellow-800';
        } else {
            $result['STATUS'] = 'Active';
            $result['STATUS_CLASS'] = 'bg-green-100 text-green-800';
        }

        // Format dates for display
        $result['START_DATE'] = date('M d, Y', strtotime($result['START_DATE']));
        $result['END_DATE'] = date('M d, Y', strtotime($result['END_DATE']));
        $result['PAID_DATE'] = $result['PAID_DATE'] ? date('M d, Y', strtotime($result['PAID_DATE'])) : '-';
    }

    echo json_encode([
        'success' => true,
        'data' => $results
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
