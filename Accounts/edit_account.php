<?php
require_once('../config/dbcon.php');
header('Content-Type: application/json'); // Ensure the response is JSON
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}
$accId = $_POST['id'];
$accName = $_POST['name'];
$accUserName = $_POST['user_name'];
$accNumber = $_POST['number'];
$accAmount = $_POST['amount'];
$accType = $_POST['type'];
$accStatus = $_POST['status'];

// Validate input data
if (!$accId || !$accName || !$accUserName || !$accNumber || !$accAmount || !$accType) {
    echo json_encode(["status" => "error", "message" => "Invalid input data."]);
    exit;
}
try {
    // Update account details in the database
    $query = "UPDATE account_tbl SET acc_num = :number, ab_name = :name, accUserName = :user_name, acc_type = :type, acc_ammo = :amount, acc_status = :status WHERE acc_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $accId, PDO::PARAM_INT);
    $stmt->bindParam(':name', $accName, PDO::PARAM_STR);
    $stmt->bindParam(':user_name', $accUserName, PDO::PARAM_STR);
    $stmt->bindParam(':number', $accNumber, PDO::PARAM_STR);
    $stmt->bindParam(':amount', $accAmount, PDO::PARAM_STR);
    $stmt->bindParam(':type', $accType, PDO::PARAM_STR);
    $stmt->bindParam(':status', $accStatus, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Account details updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update account details."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}