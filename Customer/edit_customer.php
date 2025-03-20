<?php
require_once('../config/dbcon.php');

// Set the content type to JSON
header('Content-Type: application/json');

// Ensure request method is POST and required fields are provided
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}
$customerId = $_POST['id'];
$customerName = $_POST['name'];
$customerEmail = $_POST['email'];
$customerPhone = $_POST['phone'];
$customerAddress = $_POST['address'];
$customerRole = $_POST['role'];
$customerStatus = $_POST['status'];

if(!$customerId || !$customerName || !$customerEmail || !$customerPhone || !$customerAddress || !$customerRole || !$customerStatus) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit;
}

try {
    $query = "UPDATE customer_tbl  SET c_name = :name, c_email = :email, c_phone = :phone,  c_address = :address, c_role = :role, c_status = :status WHERE c_id = :id";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $customerId['id'], PDO::PARAM_INT);
    $stmt->bindParam(':name', $customerName['name'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $customerEmail['email'], PDO::PARAM_STR);
    $stmt->bindParam(':phone', $customerPhone['phone'], PDO::PARAM_STR);
    $stmt->bindParam(':address', $customerAddress['address'], PDO::PARAM_STR);
    $stmt->bindParam(':role', $customerRole['role'], PDO::PARAM_STR);
    $stmt->bindParam(':status', $customerStatus['status'], PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Customer details updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update customer details."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>


