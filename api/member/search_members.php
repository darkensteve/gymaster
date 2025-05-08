<?php
header('Content-Type: application/json');
require_once '../../config/db_connect.php';

// Get the search term
$searchTerm = isset($_GET['term']) && !empty($_GET['term']) ? $_GET['term'] : '';

// Return empty results if search term is too short
if (strlen($searchTerm) < 2) {
    echo json_encode(array(
        "success" => true,
        "members" => array()
    ));
    exit;
}

// Build and execute the search query
$sql = "SELECT MEMBER_ID, MEMBER_FNAME, MEMBER_LNAME, EMAIL 
        FROM `MEMBER` 
        WHERE MEMBER_FNAME LIKE ? OR MEMBER_LNAME LIKE ? OR EMAIL LIKE ? 
        ORDER BY MEMBER_FNAME, MEMBER_LNAME
        LIMIT 10";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $searchParam = "%$searchTerm%";
    $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $members = array();
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Add each member to the results
            $members[] = array(
                "id" => $row["MEMBER_ID"],
                "name" => $row["MEMBER_FNAME"] . " " . $row["MEMBER_LNAME"],
                "email" => $row["EMAIL"],
                // Generate initials for the avatar
                "initials" => substr($row["MEMBER_FNAME"], 0, 1) . substr($row["MEMBER_LNAME"], 0, 1)
            );
        }
    }
    
    $stmt->close();
    
    // Return the search results
    echo json_encode(array(
        "success" => true,
        "members" => $members
    ));
} else {
    // Error preparing statement
    echo json_encode(array(
        "success" => false,
        "message" => "Database query error: " . $conn->error
    ));
}

$conn->close();
?>