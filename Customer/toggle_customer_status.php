<?php
include('../includes/header.php');

if(isset($_SESSION['userAuth']) && $_SESSION['userAuth']!==""){
    try{
        $cid = $_REQUEST['id'];

        $querry = "SELECT c_status FROM customer_tbl WHERE c_id = :cid";
        $stmt = $pdo->prepare($querry);
        $stmt->bindParam(':cid', $cid, PDO::PARAM_INT);
        $stmt->execute();
        $currentStatus= $stmt->fetchColumn();

        $newStatus = ($currentStatus == 'active') ? 'inactive' : 'active';

        $querry = "UPDATE customer_tbl SET c_status = :newStatus WHERE c_id = :cid";
        $stmt = $pdo->prepare($querry);
        $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
        $stmt->bindParam(':cid', $cid, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['cid'] = $cid;

        $_SESSION['toastr']=[
            'type'=>'success',
            'message'=>'Customer status updated successfully' .$newStatus . '!'
        ];
        header('Location: customer.php');
        exit();
    }catch(PDOException $e){
        $_SESSION['toastr']=[
            'type'=>'danger',
            'message'=>'Customer status is not updated! Error:' . $e->getMessage()
        ];
        header('Location: customer.php');
        exit();}
        
    }?>