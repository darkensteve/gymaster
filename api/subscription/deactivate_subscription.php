<?php
header('Content-Type: application/json');
require_once '../../config/db_connect.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method. Only POST requests are allowed.'
    ]);
    exit;
}

// Get parameters from the request
$data = json_decode(file_get_contents('php://input'), true);

// Check if member_id and sub_id are provided
if (!isset($data['member_id']) || !isset($data['sub_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required parameters: member_id and sub_id are required.'
    ]);
    exit;
}

$memberId = intval($data['member_id']);
$subId = intval($data['sub_id']);

// Validate the parameters
if ($memberId <= 0 || $subId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid parameters: member_id and sub_id must be positive integers.'
    ]);
    exit;
}

// Prepare the SQL statement to update the subscription status
$sql = "UPDATE MEMBER_SUBSCRIPTION 
        SET IS_ACTIVE = 0 
        WHERE MEMBER_ID = ? AND SUB_ID = ? AND IS_ACTIVE = 1";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $conn->error
    ]);
    exit;
}

// Bind parameters and execute
$stmt->bind_param('ii', $memberId, $subId);
$result = $stmt->execute();

if ($result) {
    // Check if any rows were affected
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Subscription deactivated successfully.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No active subscription found with the provided member_id and sub_id.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to deactivate subscription: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?> 