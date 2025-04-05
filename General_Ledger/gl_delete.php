<?php
include('../config/dbcon.php');
session_start();

if(isset($_SESSION['userAuth'])&& $_SESSION['userAuth']!=""){

    if (!isset($_POST['gl_id'])) {
        echo "GL ID missing.";
        exit;
    }

    $gl_Id = $_POST['gl_id'];

    try {
       
        $stmt = $pdo->prepare("DELETE FROM gl_tbl WHERE gl_id = :gl_id");
        $stmt->bindParam(':gl_id', $gl_Id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "GL deleted successfully.";
        } else {
            echo "Failed to delete GL.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


?>


