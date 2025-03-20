<?php
/*require_once('../config/dbcon.php');

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
}*/


require_once('../config/dbcon.php');

header('Content-Type: application/json'); // Ensure the response is JSON

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

// Validate and sanitize input
/*$userId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$userName = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$userEmail = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$userPhone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
$userPassword = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$userAddress = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
$userRole = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
$userStatus = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);*/

// $u_id = $_POST['u_id'];
//     $u_name = $_POST['u_name'];
//     $u_email = $_POST['u_email'];
//     $u_phone = $_POST['u_phone'];
//     $password = $_POST['password'];
//     $role = $_POST['role'];

 $userId = $_POST['id'];
    $userName = $_POST['name'];
    $userEmail = $_POST['email'];
    $userPhone = $_POST['phone'];
    $userPassword = $_POST['password'];
    $userRole = $_POST['role'];
    $userStatus = $_POST['status'];

if (!$userId || !$userName || !$userEmail || !$userPhone || !$userPassword  || !$userRole) {
    echo json_encode(["status" => "error", "message" => "Invalid input data."]);
    exit;
}

try {
    
    $query = "UPDATE user_tbl SET u_name = :name, u_email = :email, u_phone = :phone,   password=:password, role = :role, u_status = :status WHERE u_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId['id'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $userName['name'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $userEmail['email'], PDO::PARAM_STR);
        $stmt->bindParam(':phone', $userPhone['phone'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $userPassword['password'], PDO::PARAM_STR);
        $stmt->bindParam(':role', $userRole['role'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $userStatus['status'], PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User details updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update user details."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}


?>
