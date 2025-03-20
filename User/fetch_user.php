<?php


require_once('../config/dbcon.php');

header('Content-Type: application/json'); // Ensure the response is JSON

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(["error" => "Invalid request"]);
    exit;
}

$userId = filter_var($_GET['id'], FILTER_VALIDATE_INT);

if (!$userId) {
    echo json_encode(["error" => "Invalid user ID"]);
    exit;
}

try {
    $query = "SELECT * FROM user_tbl WHERE u_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(["error" => "User not found"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error fetching user details"]);
}

?>


