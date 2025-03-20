
<?php
require_once('../config/dbcon.php');

// Set the content type to JSON
header('Content-Type: application/json');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(["error" => "Invalid request"]);
    exit;
}

$customerId = filter_var($_GET['id'], FILTER_VALIDATE_INT);

try {
    $query = "SELECT * FROM customer_tbl WHERE c_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $customerId, PDO::PARAM_INT);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($customer) {
        echo json_encode($customer);
    } else {
        echo json_encode(["error" => "Customer not found"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error fetching customer details: " . $e->getMessage()]);
}
?>
