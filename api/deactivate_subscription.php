<?php
require_once('../config/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array('success' => false, 'message' => '');
    
    // Get member ID from POST data
    $memberId = isset($_POST['memberId']) ? (int)$_POST['memberId'] : 0;
    
    if ($memberId > 0) {
        // Update the member_subscription table
        $query = "UPDATE MEMBER_SUBSCRIPTION 
                 SET IS_ACTIVE = 0 
                 WHERE MEMBER_ID = ? 
                 AND IS_ACTIVE = 1";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $memberId);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $response['success'] = true;
                $response['message'] = 'Subscription deactivated successfully';
            } else {
                $response['message'] = 'No active subscription found for this member';
            }
        } else {
            $response['message'] = 'Error updating subscription status';
        }
        
        $stmt->close();
    } else {
        $response['message'] = 'Invalid member ID';
    }
    
    echo json_encode($response);
    exit;
}

// Invalid request method
http_response_code(405);
echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
?>
