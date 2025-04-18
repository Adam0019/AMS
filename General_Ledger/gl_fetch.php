
<?php
require_once('../config/dbcon.php');

// Set the content type to JSON
header('Content-Type: application/json');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(["error" => "Invalid request"]);
    exit;
}

$glId = filter_var($_GET['id'], FILTER_VALIDATE_INT);

try {
    $query = "SELECT * FROM gl_tbl WHERE gl_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $glId, PDO::PARAM_INT);
    $stmt->execute();
    $gl = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gl) {
        echo json_encode($gl);
    } else {
        echo json_encode(["error" => "Customer not found"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error fetching customer details: " . $e->getMessage()]);
}
?>
