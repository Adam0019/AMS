<?php
require_once('../config/dbcon.php');



     $u_id= $_POST['u_id'];
     $u_name = $_POST['u_name'];
     $u_email = $_POST['u_email'];
     $u_phone = $_POST['u_phone'];
     $password = $_POST['password'];
     $role = $_POST['role'];
try {
    $query = "UPDATE user_tbl SET u_name = :u_name, u_email = :u_email, u_phone = :u_phone,password=:password, role = :role WHERE u_id = :u_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':u_id', $u_id, PDO::PARAM_INT);
        $stmt->bindParam(':u_name', $u_name, PDO::PARAM_STR);
        $stmt->bindParam(':u_email', $u_email, PDO::PARAM_STR);
        $stmt->bindParam(':u_phone', $u_phone, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password,PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(["success" => "User updated successfully!"]);
    } else {
        echo json_encode(["error" => "Error updating user."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}

?>
