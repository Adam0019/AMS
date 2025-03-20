<?php
require_once('../config/dbcon.php');

// Set the content type to JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request method."]);
    exit;
}

// Validate input data
$required_fields = ['c_id', 'c_name', 'c_email', 'c_phone', 'c_role'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        echo json_encode(["error" => "Missing or empty field: $field"]);
        exit;
    }
}

$c_id = $_POST['c_id'];
$c_name = $_POST['c_name'];
$c_email = $_POST['c_email'];
$c_phone = $_POST['c_phone'];
$c_role = $_POST['c_role'];

try {
    $query = "UPDATE customer_tbl 
              SET c_name = :c_name, c_email = :c_email, c_phone = :c_phone, c_role = :c_role 
              WHERE c_id = :c_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':c_id', $c_id, PDO::PARAM_INT);
    $stmt->bindParam(':c_name', $c_name, PDO::PARAM_STR);
    $stmt->bindParam(':c_email', $c_email, PDO::PARAM_STR);
    $stmt->bindParam(':c_phone', $c_phone, PDO::PARAM_STR);
    $stmt->bindParam(':c_role', $c_role, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Customer updated successfully!"]);
    } else {
        echo json_encode(["error" => "Failed to update customer."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
