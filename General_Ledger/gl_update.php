<?php
require_once('../config/dbcon.php');


$gl_id = $_POST['gl_id'];
$gl_name = $_POST['gl_name'];
$gl_descript = $_POST['gl_descript'];
$gl_type = $_POST['gl_type'];

try {
    $query = "UPDATE gl_tbl 
              SET gl_name = :gl_name, gl_descript = :gl_descript,  gl_type = :gl_type 
              WHERE gl_id = :gl_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':gl_id', $gl_id, PDO::PARAM_INT);
    $stmt->bindParam(':gl_name', $gl_name, PDO::PARAM_STR);
    $stmt->bindParam(':gl_descript', $gl_descript, PDO::PARAM_STR);
    $stmt->bindParam(':gl_type', $gl_type, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(["success" => "GL updated successfully!"]);
           header("location: gl_title.php");
        // exit();
    } else {
        echo json_encode(["error" => "Failed to update GL."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
