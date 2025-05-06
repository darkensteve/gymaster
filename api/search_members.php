<?php
// Database connection
require_once(__DIR__ . '/../config/db_connect.php');

header('Content-Type: application/json');

// Check if search query is provided
if (!isset($_GET['query']) || empty($_GET['query'])) {
    echo json_encode([
        'success' => false,
        'message' => 'No search query provided',
        'members' => []
    ]);
    exit;
}

// Get search query and sanitize
$query = mysqli_real_escape_string($conn, $_GET['query']);

// Search for members by name or email
$sql = "SELECT 
            MEMBER_ID, 
            MEMBER_FNAME, 
            MEMBER_LNAME, 
            EMAIL,
            PHONE_NUMBER,
            SUBSTRING(MEMBER_FNAME, 1, 1) as FNAME_INITIAL,
            SUBSTRING(MEMBER_LNAME, 1, 1) as LNAME_INITIAL
        FROM `MEMBER`
        WHERE (MEMBER_FNAME LIKE '%$query%' 
            OR MEMBER_LNAME LIKE '%$query%'
            OR CONCAT(MEMBER_FNAME, ' ', MEMBER_LNAME) LIKE '%$query%'
            OR EMAIL LIKE '%$query%')
        AND IS_ACTIVE = 1
        ORDER BY MEMBER_FNAME, MEMBER_LNAME
        LIMIT 10";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . mysqli_error($conn),
        'members' => []
    ]);
    exit;
}

$members = [];
while ($row = mysqli_fetch_assoc($result)) {
    $members[] = [
        'id' => $row['MEMBER_ID'],
        'first_name' => $row['MEMBER_FNAME'],
        'last_name' => $row['MEMBER_LNAME'],
        'full_name' => $row['MEMBER_FNAME'] . ' ' . $row['MEMBER_LNAME'],
        'email' => $row['EMAIL'],
        'phone' => $row['PHONE_NUMBER'],
        'initials' => $row['FNAME_INITIAL'] . $row['LNAME_INITIAL']
    ];
}

echo json_encode([
    'success' => true,
    'members' => $members
]);

mysqli_close($conn);
?>
