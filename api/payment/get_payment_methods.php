<?php
header('Content-Type: application/json');
require_once '../../config/db_connect.php';

// Get payment method options from the database
$sql = "SELECT PAYMENT_ID, PAY_METHOD, IS_ACTIVE 
        FROM PAYMENT 
        WHERE IS_ACTIVE = 1
        ORDER BY PAY_METHOD ASC";

// Check if the table exists
$tableExists = false;
$checkTableSql = "SHOW TABLES LIKE 'PAYMENT'";
$tableResult = $conn->query($checkTableSql);

if ($tableResult && $tableResult->num_rows > 0) {
    $tableExists = true;
}

$paymentMethods = array();

if ($tableExists) {
    // Table exists, fetch data
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $paymentMethods[] = array(
                "id" => $row["PAYMENT_ID"],
                "name" => $row["PAY_METHOD"]
            );
        }
    }
} else {
    // Table doesn't exist or no data found, provide default values
    $paymentMethods = array(
        array("id" => "1", "name" => "Cash"),
        array("id" => "2", "name" => "Credit Card"),
        array("id" => "3", "name" => "Debit Card"),
        array("id" => "4", "name" => "Online Banking"),
        array("id" => "5", "name" => "GCash")
    );
}

// Return JSON response
echo json_encode(array(
    "success" => true,
    "payment_methods" => $paymentMethods
));

$conn->close();
?> 