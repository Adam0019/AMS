<?php
include('../config/dbcon.php');
session_start();
if (isset($_SESSION['userAuth']) && $_SESSION['userAuth'] != "") {

if(isset($_POST['acc_id'])) {
    $acc_id = $_POST['acc_id'];
    
try{


    // Fetch account details from the database
    $query = "SELECT * FROM account_tbl inner join user_tbl on account_tbl.u_id = user_tbl.u_id WHERE acc_id = :acc_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':acc_id', $acc_id );
    $stmt->execute();
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

     $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return data as JSON
        header('Content-Type: application/json');
        echo json_encode($result);
}catch(PDOException $e){
  header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);}
}else {
    // Unauthorized access
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'ID not provided'
    ];
    header('Location: ../index.php');
    exit();
}
} 

?>