<?php
require_once('../config/dbcon.php');


if (isset($_POST['c_id'])) {
    $c_id = $_POST['c_id'];
    try {
        $query = "SELECT * FROM customer_tbl WHERE c_id = :c_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':c_id', $c_id);
        $stmt->execute();
       
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

         // Return data as JSON
        header('Content-Type: application/json');
        echo json_encode($result);
    } catch (PDOException $e) {
         // Return error as JSON
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Customer ID not provided']);
}
?>

