<?php
require_once('../config/dbcon.php');
header('Content-Type: application/json'); // Ensure the response is JSON

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(["status"=>"error", "message"=>"Invalid request method."]);
    exit;
}

$required_fields = ['acc_id', 'ab_name', 'u_id', 'acc_num', 'acc_ammo', 'acc_type'];
foreach($required_fields as $field){
    if(!isset($_POST[$field]) || empty(trim($_POST[$field]))){
        echo json_encode(["status"=>"error", "message"=>"Missing or empty field: $field"]);
        exit;
    }
}
 
$acc_id = $_POST['acc_id'];
$ab_name = $_POST['ab_name'];
$u_id = $_POST['u_id'];
$acc_num = $_POST['acc_num'];
$acc_ammo = $_POST['acc_ammo'];
$acc_type = $_POST['acc_type'];
try{
    $query = "UPDATE account_tbl SET acc_num = :acc_num, ab_name = :name, u_id = :u_id, acc_type = :acc_type, acc_ammo = :acc_ammo WHERE acc_id = :acc_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':acc_id', $acc_id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $ab_name, PDO::PARAM_STR);
    $stmt->bindParam(':u_id', $u_id, PDO::PARAM_INT);
    $stmt->bindParam(':acc_num', $acc_num, PDO::PARAM_STR);
    $stmt->bindParam(':acc_ammo', $acc_ammo, PDO::PARAM_STR);
    $stmt->bindParam(':acc_type', $acc_type, PDO::PARAM_STR);
    if($stmt->execute()){
        echo json_encode(["status"=>"success", "message"=>"Account updated successfully!"]);
    }else{
        echo json_encode(["status"=>"error", "message"=>"Error updating account."]);
    }

}catch(PDOException $e){
    echo json_encode(["status"=>"error", "message"=>"Database error: ".$e->getMessage()]);
}
?>