<?php
session_start();
require_once('../config/dbcon.php');

// Check if user is authenticated
if (!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "" || $_SESSION['userAuth'] == NULL) {
    echo json_encode(['error' => 'Not authorized']);
    exit;
}

// Check if acc_id is provided
if (!isset($_POST['acc_id']) || empty($_POST['acc_id'])) {
    echo json_encode(['error' => 'Account ID is required']);
    exit;
}

try {
    $acc_id = $_POST['acc_id'];
    
    // Prepare and execute query to fetch account balance
    $query = "SELECT acc_ammo FROM account_tbl WHERE acc_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$acc_id]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'balance' => $result['acc_ammo']
        ]);
        // $_SESSION['accAmount']=$result['acc_ammo'];
    } else {
        echo json_encode(['error' => 'Account not found']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>