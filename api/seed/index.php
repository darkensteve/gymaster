<?php
// Check if a seed action was requested
$message = '';
$status = '';

if (isset($_GET['action']) && $_GET['action'] === 'seed') {
    ob_start();
    include 'seed_data.php';
    $message = ob_get_clean();
    $status = 'success';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gymaster Database Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gymaster Database Setup</h1>
            <p class="text-gray-600">Seed your database with sample data</p>
        </div>
        
        <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-md <?= $status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
            <h3 class="font-bold mb-2">
                <?= $status === 'success' ? 'Success' : 'Error' ?>
            </h3>
            <div class="message-box text-sm">
                <?= nl2br(htmlspecialchars($message)) ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="space-y-4">
            <a href="?action=seed" class="flex items-center justify-center gap-2 w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-md transition-colors duration-300">
                <i class="fas fa-seedling"></i>
                Seed Sample Data
            </a>
            
            <a href="../../user/admin/manage-transaction.php" class="flex items-center justify-center gap-2 w-full bg-gray-600 hover:bg-gray-700 text-white py-3 rounded-md transition-colors duration-300">
                <i class="fas fa-arrow-left"></i>
                Return to Transaction Management
            </a>
        </div>
    </div>
</body>
</html> 