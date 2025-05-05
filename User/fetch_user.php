<?php
require_once('../config/dbcon.php');

if(isset($_POST['u_id'])) {
    $u_id = $_POST['u_id'];

try {
    $query = "SELECT * FROM user_tbl WHERE u_id = :u_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':u_id', $u_id);
    $stmt->execute();
    

     $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return data as JSON
        header('Content-Type: application/json');
        echo json_encode($result);
} catch (PDOException $e) {
      header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
} else {
    // Return error if ID is not provided
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User ID not provided']);
}

?>


