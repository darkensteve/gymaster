<?php
require_once(__DIR__ . '/../config/db_connect.php');

header('Content-Type: application/json');

try {
    // Get filter parameters
    $startDate = isset($_GET['startDate']) ? mysqli_real_escape_string($conn, $_GET['startDate']) : null;
    $endDate = isset($_GET['endDate']) ? mysqli_real_escape_string($conn, $_GET['endDate']) : null;
    $subId = isset($_GET['subscription']) ? mysqli_real_escape_string($conn, $_GET['subscription']) : null;
    $programId = isset($_GET['program']) ? mysqli_real_escape_string($conn, $_GET['program']) : null;
    $memberSearch = isset($_GET['member']) ? mysqli_real_escape_string($conn, $_GET['member']) : null;

    // Build base query
    $query = "SELECT 
                ms.*,
                m.MEMBER_ID,
                CONCAT(m.MEMBER_FNAME, ' ', m.MEMBER_LNAME) as MEMBER_NAME,
                m.MEMBER_FNAME,
                m.MEMBER_LNAME,
                s.SUB_NAME,
                t.TRANSAC_DATE as PAID_DATE,
                ms.IS_ACTIVE,
                p.PROGRAM_NAME
              FROM MEMBER_SUBSCRIPTION ms
              JOIN MEMBER m ON ms.MEMBER_ID = m.MEMBER_ID
              JOIN SUBSCRIPTION s ON ms.SUB_ID = s.SUB_ID
              LEFT JOIN `TRANSACTION` t ON ms.MEMBER_ID = t.MEMBER_ID AND ms.SUB_ID = t.SUB_ID
              JOIN PROGRAM p ON m.PROGRAM_ID = p.PROGRAM_ID
              WHERE 1=1";

    // Add filter conditions
    if ($startDate) {
        $query .= " AND ms.START_DATE >= '$startDate'";
    }
    if ($endDate) {
        $query .= " AND ms.START_DATE <= '$endDate'";
    }
    if ($subId && $subId !== 'all') {
        $query .= " AND ms.SUB_ID = '$subId'";
    }
    if ($programId && $programId !== 'all') {
        $query .= " AND m.PROGRAM_ID = '$programId'";
    }
    if ($memberSearch) {
        $query .= " AND (CONCAT(m.MEMBER_FNAME, ' ', m.MEMBER_LNAME) LIKE '%$memberSearch%'
                        OR m.MEMBER_FNAME LIKE '%$memberSearch%'
                        OR m.MEMBER_LNAME LIKE '%$memberSearch%')";
    }

    $query .= " ORDER BY ms.START_DATE DESC";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        throw new Exception(mysqli_error($conn));
    }

    $subscriptions = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Calculate status
        $today = new DateTime();
        $endDate = new DateTime($row['END_DATE']);
        $dateDiff = $today->diff($endDate)->days;
        
        if (!$row['IS_ACTIVE']) {
            $statusClass = 'bg-red-100 text-red-800';
            $statusText = 'Inactive';
        } elseif ($endDate < $today) {
            $statusClass = 'bg-red-100 text-red-800';
            $statusText = 'Expired';
        } elseif ($dateDiff <= 7) {
            $statusClass = 'bg-yellow-100 text-yellow-800';
            $statusText = 'Expiring Soon';
        } else {
            $statusClass = 'bg-green-100 text-green-800';
            $statusText = 'Active';
        }

        $subscriptions[] = [
            'memberId' => $row['MEMBER_ID'],
            'memberName' => $row['MEMBER_NAME'],
            'memberInitials' => substr($row['MEMBER_FNAME'], 0, 1) . substr($row['MEMBER_LNAME'], 0, 1),
            'subscriptionName' => $row['SUB_NAME'],
            'startDate' => date('M j, Y', strtotime($row['START_DATE'])),
            'endDate' => date('M j, Y', strtotime($row['END_DATE'])),
            'paidDate' => $row['PAID_DATE'] ? date('M j, Y', strtotime($row['PAID_DATE'])) : '-',
            'status' => $statusText,
            'statusClass' => $statusClass,
            'isActive' => $row['IS_ACTIVE'],
            'program' => $row['PROGRAM_NAME']
        ];
    }

    echo json_encode([
        'success' => true,
        'data' => $subscriptions
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

mysqli_close($conn);
