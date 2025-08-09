<?php
include('../config/dbcon.php');
session_start();

if (isset($_SESSION['userAuth']) && $_SESSION['userAuth'] != "") {
 
$acc_id = $_POST['acc_id'];
$a_type = $_POST['a_type']; // Changed from edit_a_type to a_type
$u_id = $_POST['u_id'];
$acc_ammo = $_POST['acc_ammo'];
$acc_type = $_POST['acc_type'];

// Handle Cash vs Bank logic
if($_POST['a_type'] === 'Cash') {
    $acc_num = 'Cash';
    $ab_name = 'Cash';
} else {
    $acc_num = $_POST['acc_num'];
    $ab_name = $_POST['ab_name'];
}

try{
    $query = "UPDATE account_tbl SET acc_num = :acc_num, ab_name = :name, a_type = :a_type, u_id = :u_id, acc_type = :acc_type, acc_ammo = :acc_ammo WHERE acc_id = :acc_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':acc_id', $acc_id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $ab_name, PDO::PARAM_STR);
    $stmt->bindParam(':a_type', $a_type, PDO::PARAM_STR);
    $stmt->bindParam(':u_id', $u_id, PDO::PARAM_INT);
    $stmt->bindParam(':acc_num', $acc_num, PDO::PARAM_STR);
    $stmt->bindParam(':acc_ammo', $acc_ammo, PDO::PARAM_STR);
    $stmt->bindParam(':acc_type', $acc_type, PDO::PARAM_STR);
    
    if($stmt->execute()){
        echo json_encode(["status"=>"success", "message"=>"Account updated successfully!"]);
        // Remove header redirect when using AJAX
        // header("location: account.php");
    }else{
        echo json_encode(["status"=>"error", "message"=>"Error updating account."]);
    }

}catch(PDOException $e){
    echo json_encode(["status"=>"error", "message"=>"Database error: ".$e->getMessage()]);
}
} else {
    // Unauthorized access
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Unauthorized Access'
    ];
    header('Location: ../index.php');
    exit();
}
?>