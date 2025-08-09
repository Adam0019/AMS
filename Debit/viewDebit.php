<?php
include('../config/dbcon.php');
session_start();
if (isset($_SESSION['userAuth']) && $_SESSION['userAuth'] != "") {
if(isset($_POST['debit_id'])){
    $debit_id=$_POST['debit_id'];
    try{
        $query= "SELECT * FROM debit_tbl 
                      INNER JOIN customer_tbl ON debit_tbl.dbt_c_id = customer_tbl.c_id
                      INNER JOIN gl_tbl ON debit_tbl.dbt_gl_id = gl_tbl.gl_id 
                      INNER JOIN account_tbl ON debit_tbl.dbt_acc_id = account_tbl.acc_id 
                      WHERE debit_id = :debit_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':debit_id', $debit_id);
        $stmt->execute();
        
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