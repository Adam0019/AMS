<?php
require_once('../config/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userId = $_POST['id'];
    $userName = $_POST['name'];
    $userEmail = $_POST['email'];
    $userPhone = $_POST['phone'];
    $userPassword = $_POST['password'];
    $userAddress = $_POST['address'];
    $userRole = $_POST['role'];
    $userStatus = $_POST['status'];

    try {
        $query = "UPDATE user_tbl SET u_name = :name, u_email = :email, u_phone = :phone, u_address = :address,  password=:password, role = :role, u_status = :status WHERE u_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $userName, PDO::PARAM_STR);
        $stmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $userPhone, PDO::PARAM_STR);
        $stmt->bindParam(':password', $userPassword, PDO::PARAM_STR);
        $stmt->bindParam(':address', $userAddress, PDO::PARAM_STR);
        $stmt->bindParam(':role', $userRole, PDO::PARAM_STR);
        
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "user details updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update user details."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error updating user: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
