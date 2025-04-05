<?php
include('../config/dbcon.php');
session_start();

if(isset($_SESSION['userAuth'])&& $_SESSION['userAuth']!=""){

    if (!isset($_POST['acc_id'])) {
        echo "Account ID missing.";
        exit;
    }

    $acc_Id = $_POST['acc_id'];

    try {
       
        $stmt = $pdo->prepare("DELETE FROM account_tbl WHERE acc_id = :acc_id");
        $stmt->bindParam(':acc_id', $acc_Id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Account deleted successfully.";
        } else {
            echo "Failed to delete Account.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


?>

