<?php
include('../config/dbcon.php');
session_start();

if (isset($_SESSION['userAuth']) && $_SESSION['userAuth'] != "") {
 
    if(isset($_POST['submit'])){
        $u_id = $_POST['u_id'];
        $a_type = $_POST['a_type'];
        $ab_name = $_POST['ab_name'];
        $acc_ammo = $_POST['acc_ammo'];
        $acc_num = $_POST['acc_num'];
        $acc_type = $_POST['acc_type'];

        $query = "INSERT INTO account_tbl (u_id, a_type, ab_name, acc_ammo, acc_num, acc_type) VALUES (:u_id, :a_type, :ab_name, :acc_ammo, :acc_num, :acc_type)";

        try{
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':u_id', $u_id, PDO::PARAM_INT);
            $stmt->bindParam(':a_type', $a_type, PDO::PARAM_STR);
            $stmt->bindParam(':ab_name', $ab_name, PDO::PARAM_STR);
            $stmt->bindParam(':acc_ammo', $acc_ammo, PDO::PARAM_INT);
            $stmt->bindParam(':acc_num', $acc_num, PDO::PARAM_STR);
            $stmt->bindParam(':acc_type', $acc_type, PDO::PARAM_STR);

            $stmt->execute();

            $_SESSION['toastr']=[
                'type'=>'success',
                'message'=>'Account added Successfully!'
            ];
            header('Location: account.php');
            exit();
        }catch(PDOException $e){
            $_SESSION['toastr']=[
                'type'=>'error',
                'message'=>'Error adding account: '.$e->getMessage()
            ];
            header('Location: account.php');
            exit();
        }
    }
}
?>