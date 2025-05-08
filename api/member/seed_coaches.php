<?php
// This is a helper file to seed the database with initial coaches data
// In a production environment, you would have a proper admin interface to manage coaches

require_once '../../config/database.php';

try {
    // Check if we already have coaches
    $stmt = $conn->query("SELECT COUNT(*) as count FROM COACH");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['count'] > 0) {
        echo "Coaches already exist in the database. Skipping seeding.";
        exit;
    }
    
    // Insert some coaches
    $coaches = [
        // Strength Training Coaches
        ["Michael", "Johnson", "michael.j@example.com", "+1 (555) 123-4567", "MALE", "Weightlifting, Bodybuilding"],
        ["Jessica", "Parker", "jessica.p@example.com", "+1 (555) 234-5678", "FEMALE", "Powerlifting, Functional Training"],
        
        // Cardio Coaches
        ["Robert", "Brown", "robert.b@example.com", "+1 (555) 345-6789", "MALE", "Running, HIIT"],
        ["Sarah", "Williams", "sarah.w@example.com", "+1 (555) 456-7890", "FEMALE", "Aerobics, Zumba"],
        
        // Yoga Coaches
        ["Emma", "Davis", "emma.d@example.com", "+1 (555) 567-8901", "FEMALE", "Hatha Yoga, Meditation"],
        ["David", "Wilson", "david.w@example.com", "+1 (555) 678-9012", "MALE", "Vinyasa Flow, Power Yoga"],
        
        // CrossFit Coaches
        ["James", "Taylor", "james.t@example.com", "+1 (555) 789-0123", "MALE", "CrossFit, Olympic Lifting"],
        ["Lisa", "Martinez", "lisa.m@example.com", "+1 (555) 890-1234", "FEMALE", "CrossFit, Calisthenics"]
    ];
    
    // Insert coaches
    $stmt = $conn->prepare("
        INSERT INTO COACH (COACH_FNAME, COACH_LNAME, EMAIL, PHONE_NUMBER, GENDER, SPECIALIZATION, IS_ACTIVE) 
        VALUES (?, ?, ?, ?, ?, ?, 1)
    ");
    
    foreach ($coaches as $coach) {
        $stmt->execute($coach);
    }
    
    // Get all coach and program IDs
    $coachStmt = $conn->query("SELECT COACH_ID, COACH_FNAME FROM COACH");
    $coaches = $coachStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $programStmt = $conn->query("SELECT PROGRAM_ID, PROGRAM_NAME FROM PROGRAM");
    $programs = $programStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Create program-coach associations
    $associationStmt = $conn->prepare("
        INSERT INTO PROGRAM_COACH (PROGRAM_ID, COACH_ID) VALUES (?, ?)
    ");
    
    // Strength Training (ID: 1) - Michael (ID: 1), Jessica (ID: 2)
    $associationStmt->execute([1, 1]);
    $associationStmt->execute([1, 2]);
    
    // Cardio (ID: 2) - Robert (ID: 3), Sarah (ID: 4)
    $associationStmt->execute([2, 3]);
    $associationStmt->execute([2, 4]);
    
    // Yoga (ID: 3) - Emma (ID: 5), David (ID: 6)
    $associationStmt->execute([3, 5]);
    $associationStmt->execute([3, 6]);
    
    // CrossFit (ID: 4) - James (ID: 7), Lisa (ID: 8)
    $associationStmt->execute([4, 7]);
    $associationStmt->execute([4, 8]);
    
    echo "Coaches seeded successfully!";
} catch(PDOException $e) {
    die("Error seeding coaches: " . $e->getMessage());
}
?>