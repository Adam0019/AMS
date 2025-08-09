<?php
include('../config/dbcon.php');
session_start();
if (isset($_SESSION['userAuth']) && $_SESSION['userAuth'] != "") {
if(isset($_POST['credit_id'])){
    $credit_id=$_POST['credit_id'];
    try{
        $query= "SELECT * FROM credit_tbl 
                      INNER JOIN customer_tbl ON credit_tbl.c_id = customer_tbl.c_id
                      INNER JOIN gl_tbl ON credit_tbl.gl_id = gl_tbl.gl_id 
                       INNER JOIN account_tbl ON credit_tbl.acc_id = account_tbl.acc_id WHERE credit_id = :credit_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':credit_id', $credit_id);
        $stmt->execute();
        $credit=$stmt->fetch(PDO::FETCH_ASSOC);
         $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return data as JSON
        header('Content-Type: application/json');
        echo json_encode($result);
    }catch(PDOException $e){
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);}
}
} else {
    // Unauthorized access
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'ID not provided'
    ];
    header('Location: ../index.php');
    exit();
}
?>