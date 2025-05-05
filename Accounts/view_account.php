<?php
include('../config/dbcon.php');
if(isset($_POST['acc_id'])) {
    $acc_id = $_POST['acc_id'];
    
    try{
        $query = "SELECT * FROM account_tbl inner join user_tbl on account_tbl.u_id = user_tbl.u_id WHERE account_tbl.acc_id = :acc_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':acc_id', $acc_id );
        $stmt->execute();
       $result = $stmt->fetch(PDO::FETCH_ASSOC);

         // Return data as JSON
        header('Content-Type: application/json');
        echo json_encode($result);
    }catch(PDOException $e){
          // Return error as JSON
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}else{
     header('Content-Type: application/json');
    echo json_encode(['error' => 'Account ID not provided']);
}
?>