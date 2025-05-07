<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    // Get total transactions count
    $totalQuery = "SELECT COUNT(*) as total FROM `TRANSACTION`";
    $totalStmt = $conn->query($totalQuery);
    $totalTransactions = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get total revenue by joining with subscription table
    $revenueQuery = "SELECT SUM(s.PRICE) as total_revenue 
                     FROM `TRANSACTION` t 
                     JOIN SUBSCRIPTION s ON t.SUB_ID = s.SUB_ID";
    $revenueStmt = $conn->query($revenueQuery);
    $totalRevenue = $revenueStmt->fetch(PDO::FETCH_ASSOC)['total_revenue'] ?? 0;

    // Get recent transactions (last 30 days)
    $recentQuery = "SELECT COUNT(*) as recent 
                    FROM `TRANSACTION` 
                    WHERE TRANSAC_DATE >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)";
    $recentStmt = $conn->query($recentQuery);
    $recentTransactions = $recentStmt->fetch(PDO::FETCH_ASSOC)['recent'];

    // Get growth percentages
    $prevPeriodQuery = "SELECT 
        COUNT(*) as prev_count,
        COALESCE(SUM(s.PRICE), 0) as prev_revenue
        FROM `TRANSACTION` t 
        JOIN SUBSCRIPTION s ON t.SUB_ID = s.SUB_ID
        WHERE t.TRANSAC_DATE BETWEEN DATE_SUB(CURRENT_DATE, INTERVAL 60 DAY) 
        AND DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)";
    $prevStmt = $conn->query($prevPeriodQuery);
    $prevPeriod = $prevStmt->fetch(PDO::FETCH_ASSOC);

    // Calculate growth percentages
    $transactionGrowth = $prevPeriod['prev_count'] > 0 
        ? round((($recentTransactions - $prevPeriod['prev_count']) / $prevPeriod['prev_count']) * 100, 1)
        : 0;

    $revenueGrowth = $prevPeriod['prev_revenue'] > 0 
        ? round((($totalRevenue - $prevPeriod['prev_revenue']) / $prevPeriod['prev_revenue']) * 100, 1)
        : 0;

    echo json_encode([
        'success' => true,
        'data' => [
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
            'recentTransactions' => $recentTransactions,
            'transactionGrowth' => $transactionGrowth,
            'revenueGrowth' => $revenueGrowth
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
