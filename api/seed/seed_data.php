<?php
require_once '../../config/db_connect.php';

// Check if tables exist
$tableCheckQuery = "SHOW TABLES LIKE 'MEMBER'";
$result = $conn->query($tableCheckQuery);

if ($result->num_rows == 0) {
    echo "Error: Database tables not found. Please create the required tables first.";
    exit;
}

// Begin transaction
$conn->begin_transaction();

try {
    // Insert sample PROGRAM data if not already present
    $programCheckQuery = "SELECT COUNT(*) as count FROM PROGRAM";
    $result = $conn->query($programCheckQuery);
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        $programsQuery = "INSERT INTO PROGRAM (PROGRAM_NAME, IS_ACTIVE) VALUES
            ('Strength Training', 1),
            ('Cardio', 1),
            ('Yoga', 1),
            ('CrossFit', 1)";
            
        if (!$conn->query($programsQuery)) {
            throw new Exception("Error inserting programs: " . $conn->error);
        }
        
        echo "Programs added successfully<br>";
    }
    
    // Insert sample USER data if not already present
    $userCheckQuery = "SELECT COUNT(*) as count FROM `USER`";
    $result = $conn->query($userCheckQuery);
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        $userQuery = "INSERT INTO `USER` (USER_FNAME, USER_LNAME, USERNAME, PASSWORD, USER_TYPE, IS_ACTIVE) VALUES
            ('Admin', 'User', 'admin', 'admin123', 'ADMINISTRATOR', 1)";
            
        if (!$conn->query($userQuery)) {
            throw new Exception("Error inserting user: " . $conn->error);
        }
        
        echo "Admin user added successfully<br>";
    }
    
    // Insert sample SUBSCRIPTION data if not already present
    $subscriptionCheckQuery = "SELECT COUNT(*) as count FROM SUBSCRIPTION";
    $result = $conn->query($subscriptionCheckQuery);
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        $subscriptionsQuery = "INSERT INTO SUBSCRIPTION (SUB_NAME, DURATION, PRICE, IS_ACTIVE) VALUES
            ('Monthly Plan', '30', 1500.00, 1),
            ('Quarterly Plan', '90', 4000.00, 1),
            ('Annual Plan', '365', 15000.00, 1)";
            
        if (!$conn->query($subscriptionsQuery)) {
            throw new Exception("Error inserting subscriptions: " . $conn->error);
        }
        
        echo "Subscriptions added successfully<br>";
    }
    
    // Insert sample PAYMENT methods if not already present
    $paymentCheckQuery = "SELECT COUNT(*) as count FROM PAYMENT";
    $result = $conn->query($paymentCheckQuery);
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        $paymentsQuery = "INSERT INTO PAYMENT (PAY_METHOD, IS_ACTIVE) VALUES
            ('Cash', 1),
            ('Credit Card', 1),
            ('Debit Card', 1),
            ('Online Banking', 1),
            ('GCash', 1)";
            
        if (!$conn->query($paymentsQuery)) {
            throw new Exception("Error inserting payment methods: " . $conn->error);
        }
        
        echo "Payment methods added successfully<br>";
    }
    
    // Insert sample MEMBER data if not already present
    $memberCheckQuery = "SELECT COUNT(*) as count FROM `MEMBER`";
    $result = $conn->query($memberCheckQuery);
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        $membersQuery = "INSERT INTO `MEMBER` (MEMBER_FNAME, MEMBER_LNAME, EMAIL, PHONE_NUMBER, IS_ACTIVE, PROGRAM_ID, USER_ID, JOINED_DATE) VALUES
            ('Waning', 'Durano', 'waning.durano@example.com', '09123456789', 1, 1, 1, CURRENT_DATE()),
            ('Esteban', 'Real Jr', 'esteban.real@example.com', '09234567890', 1, 2, 1, CURRENT_DATE()),
            ('Francis', 'Ramas', 'francis.ramas@example.com', '09345678901', 1, 3, 1, CURRENT_DATE())";
            
        if (!$conn->query($membersQuery)) {
            throw new Exception("Error inserting members: " . $conn->error);
        }
        
        echo "Members added successfully<br>";
    }
    
    // Insert sample MEMBER_SUBSCRIPTION data if not already present
    $memberSubCheckQuery = "SELECT COUNT(*) as count FROM MEMBER_SUBSCRIPTION";
    $result = $conn->query($memberSubCheckQuery);
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        // Set some dates for subscriptions
        $today = date('Y-m-d');
        $oneMonthLater = date('Y-m-d', strtotime('+30 days'));
        $threeMonthsLater = date('Y-m-d', strtotime('+90 days'));
        $oneYearLater = date('Y-m-d', strtotime('+365 days'));
        
        $memberSubsQuery = "INSERT INTO MEMBER_SUBSCRIPTION (MEMBER_ID, SUB_ID, START_DATE, END_DATE, IS_ACTIVE) VALUES
            (1, 1, '$today', '$oneMonthLater', 1),
            (2, 3, '$today', '$oneYearLater', 0),
            (2, 2, '$today', '$threeMonthsLater', 0),
            (3, 2, '$today', '$threeMonthsLater', 1)";
            
        if (!$conn->query($memberSubsQuery)) {
            throw new Exception("Error inserting member subscriptions: " . $conn->error);
        }
        
        echo "Member subscriptions added successfully<br>";
    }
    
    // Insert sample TRANSACTION data if not already present
    $transactionCheckQuery = "SELECT COUNT(*) as count FROM `TRANSACTION`";
    $result = $conn->query($transactionCheckQuery);
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        $today = date('Y-m-d');
        
        $transactionsQuery = "INSERT INTO `TRANSACTION` (MEMBER_ID, SUB_ID, PAYMENT_ID, TRANSAC_DATE) VALUES
            (1, 1, 1, '$today'),
            (2, 3, 2, '$today'),
            (3, 2, 3, '$today')";
            
        if (!$conn->query($transactionsQuery)) {
            throw new Exception("Error inserting transactions: " . $conn->error);
        }
        
        echo "Transactions added successfully<br>";
    }
    
    // Commit the transaction
    $conn->commit();
    echo "Database seeded successfully!";
    
} catch (Exception $e) {
    // Rollback the transaction if error occurs
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

// Close the connection
$conn->close();
?> 