<?php
include('../config/dbcon.php');
session_start();

if(isset($_SESSION['userAuth'])&& $_SESSION['userAuth']!=""){

    if (!isset($_POST['u_id'])) {
        echo "User ID missing.";
        exit;
    }

    $u_Id = $_POST['u_id'];

    try {
       
        $stmt = $pdo->prepare("DELETE FROM user_tbl WHERE u_id = :u_id");
        $stmt->bindParam(':u_id', $u_Id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "User deleted successfully.";
        } else {
            echo "Failed to delete User.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


?>

