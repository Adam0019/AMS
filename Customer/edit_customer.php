<?php
require_once('../config/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $customerId = $_POST['id'];
    $customerName = $_POST['name'];
    $customerEmail = $_POST['email'];
    $customerPhone = $_POST['phone'];
    $customerAddress = $_POST['address'];
    $customerRole = $_POST['role'];
    $customerStatus = $_POST['status'];

    try {
        $query = "UPDATE customer_tbl SET c_name = :name, c_email = :email, c_phone = :phone, c_address = :address, c_role = :role, c_status = :status WHERE c_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $customerId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $customerName, PDO::PARAM_STR);
        $stmt->bindParam(':email', $customerEmail, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $customerPhone, PDO::PARAM_STR);
        $stmt->bindParam(':address', $customerAddress, PDO::PARAM_STR);
        $stmt->bindParam(':role', $customerRole, PDO::PARAM_STR);
        
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Customer details updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update customer details."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error updating customer: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
