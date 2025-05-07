<?php
header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    // Get total transactions count
    $totalQuery = "SELECT COUNT(*) as total FROM `TRANSACTION`";
    $totalStmt = $conn->query($totalQuery);
    $totalTransactions = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Get total revenue
    $revenueQuery = "SELECT SUM(s.PRICE) as total_revenue 
                     FROM `TRANSACTION` t 
                     JOIN SUBSCRIPTION s ON t.SUB_ID = s.SUB_ID";
    $revenueStmt = $conn->query($revenueQuery);
    $totalRevenue = $revenueStmt->fetch(PDO::FETCH_ASSOC)['total_revenue'] ?? 0;
    
    // Get recent transactions (last 30 days)
    $recentQuery = "SELECT COUNT(*) as recent 
                    FROM `TRANSACTION` 
                    WHERE TRANSAC_DATE >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)";
    $recentStmt = $conn->query($recentQuery);
    $recentTransactions = $recentStmt->fetch(PDO::FETCH_ASSOC)['recent'];
    
    // Get transaction growth percentage
    $prevPeriodQuery = "SELECT COUNT(*) as prev_total 
                        FROM `TRANSACTION` 
                        WHERE TRANSAC_DATE BETWEEN DATE_SUB(CURRENT_DATE(), INTERVAL 60 DAY) 
                        AND DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)";
    $prevPeriodStmt = $conn->query($prevPeriodQuery);
    $prevPeriodTotal = $prevPeriodStmt->fetch(PDO::FETCH_ASSOC)['prev_total'];
    
    // Calculate growth percentage
    $transactionGrowth = 0;
    if ($prevPeriodTotal > 0) {
        $transactionGrowth = round((($recentTransactions - $prevPeriodTotal) / $prevPeriodTotal) * 100);
    }
    
    // Calculate revenue growth
    $prevRevenueQuery = "SELECT SUM(s.PRICE) as prev_revenue 
                         FROM `TRANSACTION` t 
                         JOIN SUBSCRIPTION s ON t.SUB_ID = s.SUB_ID 
                         WHERE t.TRANSAC_DATE BETWEEN DATE_SUB(CURRENT_DATE(), INTERVAL 60 DAY) 
                         AND DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)";
    $prevRevenueStmt = $conn->query($prevRevenueQuery);
    $prevRevenue = $prevRevenueStmt->fetch(PDO::FETCH_ASSOC)['prev_revenue'] ?? 0;
    
    // Calculate revenue growth percentage
    $revenueGrowth = 0;
    $currentMonthRevenue = 0;
    
    $currentMonthRevenueQuery = "SELECT SUM(s.PRICE) as current_revenue 
                                FROM `TRANSACTION` t 
                                JOIN SUBSCRIPTION s ON t.SUB_ID = s.SUB_ID 
                                WHERE t.TRANSAC_DATE >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)";
    $currentMonthRevenueStmt = $conn->query($currentMonthRevenueQuery);
    $currentMonthRevenue = $currentMonthRevenueStmt->fetch(PDO::FETCH_ASSOC)['current_revenue'] ?? 0;
    
    if ($prevRevenue > 0) {
        $revenueGrowth = round((($currentMonthRevenue - $prevRevenue) / $prevRevenue) * 100);
    }
    
    // Get expiring subscriptions (next 7 days)
    $expiringQuery = "SELECT COUNT(*) as expiring 
                      FROM MEMBER_SUBSCRIPTION 
                      WHERE END_DATE BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)
                      AND IS_ACTIVE = 1";
    $expiringStmt = $conn->query($expiringQuery);
    $expiringSubscriptions = $expiringStmt->fetch(PDO::FETCH_ASSOC)['expiring'];
    
    echo json_encode([
        'success' => true,
        'total_transactions' => $totalTransactions,
        'total_revenue' => $totalRevenue,
        'recent_transactions' => $recentTransactions,
        'expiring_subscriptions' => $expiringSubscriptions,
        'transaction_growth' => $transactionGrowth,
        'revenue_growth' => $revenueGrowth
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
