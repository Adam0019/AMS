<?php

require_once('../config/dbcon.php');
header('Content-Type: application/json'); // Ensure the response is JSON

if(!isset($_GET['id']) || empty($_GET['id'])){
    echo json_encode(["error"=>"Invalid request"]);
    exit;
}

$accId = filter_var($_GET['id'], FILTER_VALIDATE_INT);

if(!$accId){
    echo json_encode(["error"=>"Invalid account ID"]);
    exit;
}try{


    // Fetch account details from the database
    $query = "SELECT * FROM account_tbl inner join user_tbl on account_tbl.u_id = user_tbl.u_id WHERE acc_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $accId, PDO::PARAM_INT);
    $stmt->execute();
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    if($account){
        echo json_encode($account);
    }else{
        echo json_encode(["error"=>"Account not found"]);
    }
}catch(PDOException $e){
    echo json_encode(["error"=>"Error fetching account details"]);
}?>