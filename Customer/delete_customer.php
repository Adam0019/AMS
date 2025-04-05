<?php
include('../config/dbcon.php');
session_start();

if(isset($_SESSION['userAuth'])&& $_SESSION['userAuth']!=""){

    if (!isset($_POST['c_id'])) {
        echo "Customer ID missing.";
        exit;
    }

    $c_Id = $_POST['c_id'];

    try {
       
        $stmt = $pdo->prepare("DELETE FROM customer_tbl WHERE c_id = :c_id");
        $stmt->bindParam(':c_id', $c_Id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Customer deleted successfully.";
        } else {
            echo "Failed to delete Customer.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


?>

