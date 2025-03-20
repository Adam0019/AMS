<?php
require_once('../config/dbcon.php');
header('Content-Type: application/json'); // Ensure the response is JSON

if (isset($_GET['id'])) {
    $customerId = $_GET['id'];
    try {
        $query = "SELECT * FROM customer_tbl WHERE c_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $customerId, PDO::PARAM_INT);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
           echo json_encode([
                "success" => true,
                "id" => $customer['c_id'],        
                "name" => $customer['c_name'],    
                "email" => $customer['c_email'],  
                "phone" => $customer['c_phone'],  
                "role" => $customer['c_role'],
                 "status" => ($customer['c_status'] == 'active' ? 'Inactive' : 'Active')
            ]);   
        } else {
            echo json_encode(["success" => false, "message" => "Customer not found"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
     echo json_encode(["success" => false, "message" => "Customer ID not provided"]);
}
?>

