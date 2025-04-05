<?php
require_once('../config/dbcon.php');

// Set the content type to JSON
header('Content-Type: application/json');

// Ensure request method is POST and required fields are provided
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}
$glID = $_POST['id'];
$glName = $_POST['name'];
$glDescript = $_POST['descripton'];
$glType = $_POST['type'];
$glStatus = $_POST['status'];

if(!$glID || !$glName || !$glDescript || !$glType || !$glStatus) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit;
}

try {
    $query = "UPDATE gl_tbl  SET gl_name = :name, gl_descript = :descripton, gl_type = :type, gl_status = :status WHERE gl_id = :id";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $glID['id'], PDO::PARAM_INT);
    $stmt->bindParam(':name', $glName['name'], PDO::PARAM_STR);
    $stmt->bindParam(':descripton', $glDescript['descripton'], PDO::PARAM_STR);
    $stmt->bindParam(':type', $glType['type'], PDO::PARAM_STR);
    $stmt->bindParam(':status', $glStatus['status'], PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "GL details updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update GL details."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>


