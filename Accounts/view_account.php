<?php
include('../config/dbcon.php');
header('Content-Type: application/json'); // Ensure the response is JSON

if (isset($_GET['id'])){
    $accId = $_GET['id'];
    try{
        $query = "SELECT * FROM account_tbl inner join user_tbl on account_tbl.u_id = user_tbl.u_id WHERE account_tbl.acc_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $accId, PDO::PARAM_INT);
        $stmt->execute();
        $account =$stmt->fetch(PDO::FETCH_ASSOC);

        if ($account){
            echo json_encode([
                "success"=>true,
                "id"=>$account['acc_id'],
                "name"=>$account['u_name'],
                "account_number"=>$account['acc_num'],
                "account_name"=>$account['ab_name'],
                "account_type"=>$account['acc_type'],
                "account_ammo"=>$account['acc_ammo'],
                "account_status"=>($account['acc_status']=='active'? 'Inactive':'Active')
            ]);
        }else{
            echo json_encode(["success"=>false,"message"=>"Account not found"]);
        }
    }catch(PDOException $e){
        echo json_encode(["success"=>false,"message"=>"Database error: ".$e->getMessage()]);
    }
}else{
    echo json_encode(["success"=>false,"message"=>"Account ID not provided"]);
}