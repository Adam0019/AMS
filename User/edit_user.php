<?php



require_once('../config/dbcon.php');

header('Content-Type: application/json'); // Ensure the response is JSON

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

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
