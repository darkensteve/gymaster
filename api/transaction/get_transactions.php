<?php
header('Content-Type: application/json');
require_once '../../config/db_connect.php';

// Get transactions from the database
$sql = "SELECT t.TRANSACTION_ID, m.MEMBER_ID, m.MEMBER_FNAME, m.MEMBER_LNAME, m.EMAIL, 
        s.SUB_ID, s.SUB_NAME, s.DURATION, s.PRICE, 
        p.PAYMENT_ID, p.PAY_METHOD,
        t.TRANSAC_DATE
        FROM `TRANSACTION` t
        JOIN `MEMBER` m ON t.MEMBER_ID = m.MEMBER_ID
        JOIN SUBSCRIPTION s ON t.SUB_ID = s.SUB_ID
        JOIN PAYMENT p ON t.PAYMENT_ID = p.PAYMENT_ID
        ORDER BY t.TRANSAC_DATE DESC";

$result = $conn->query($sql);

$transactions = array();

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $transactions[] = array(
            "transaction_id" => $row["TRANSACTION_ID"],
            "member_id" => $row["MEMBER_ID"],
            "member_name" => $row["MEMBER_FNAME"] . " " . $row["MEMBER_LNAME"],
            "member_email" => $row["EMAIL"],
            "sub_id" => $row["SUB_ID"],
            "sub_name" => $row["SUB_NAME"],
            "duration" => $row["DURATION"],
            "price" => $row["PRICE"],
            "payment_id" => $row["PAYMENT_ID"],
            "payment_method" => $row["PAY_METHOD"],
            "transaction_date" => $row["TRANSAC_DATE"]
        );
    }
}

// Calculate total statistics
$totalCount = count($transactions);
$totalRevenue = 0;

foreach ($transactions as $transaction) {
    $totalRevenue += $transaction['price'];
}

// Get recent transactions (last 30 days)
$recentTransactions = array();
$currentDate = date('Y-m-d');
$thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));

foreach ($transactions as $transaction) {
    if ($transaction['transaction_date'] >= $thirtyDaysAgo && $transaction['transaction_date'] <= $currentDate) {
        $recentTransactions[] = $transaction;
    }
}

$recentCount = count($recentTransactions);

// Return JSON response
echo json_encode(array(
    "success" => true,
    "total_transactions" => $totalCount,
    "total_revenue" => $totalRevenue,
    "recent_transactions" => $recentCount,
    "transactions" => $transactions
));

$conn->close();
?> 