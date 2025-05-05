<?php
// Database connection
require_once('../config/dbcon.php');

// Check if ID is provided
if(isset($_POST['gl_id'])) {
    $gl_id = $_POST['gl_id'];
    
    try {
        // Prepare and execute the query
        $query = "SELECT * FROM gl_tbl WHERE gl_id = :gl_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':gl_id', $gl_id);
        $stmt->execute();
        
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return data as JSON
        header('Content-Type: application/json');
        echo json_encode($result);
    } catch(PDOException $e) {
        // Return error as JSON
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // Return error if ID is not provided
    header('Content-Type: application/json');
    echo json_encode(['error' => 'GL ID not provided']);
}
?>