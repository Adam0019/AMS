 <?php

require_once('../config/dbcon.php');

header('Content-Type: application/json'); // Ensure the response is JSON

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    try {
        $query = "SELECT * FROM user_tbl WHERE u_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode([
                "success" => true,
                "id" => $user['u_id'],        
                "name" => $user['u_name'],    
                "email" => $user['u_email'],  
                "password" => $user['password'],
                "username" => $user['u_name'], 
                "phone" => $user['u_phone'],  
                "role" => $user['role'],
                "status" => ($user['u_status'] == 'active' ? 'Inactive' : 'Active')
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "User not found"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User ID not provided"]);
}
?>
