<?php
include('../includes/header.php');

if(isset($_SESSION['userAuth'])&& $_SESSION['userAuth']!==""){
    try{
        $aid=$_REQUEST['id'];

        $querry = "SELECT acc_status FROM account_tbl WHERE acc_id = :aid";
        $stmt = $pdo->prepare($querry);
        $stmt->bindParam(':aid', $aid, PDO::PARAM_INT);
        $stmt->execute();
        $currentStatus = $stmt->fetchColumn();
        $newStatus = ($currentStatus == 'active')? 'inactive' : 'active';

        $querry = "UPDATE account_tbl SET acc_status = :newStatus WHERE acc_id = :aid";
        $stmt = $pdo->prepare($querry);
        $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
        $stmt->bindParam(':aid', $aid, PDO::PARAM_INT);
        $stmt->execute();
        
        $_SESSION['aid'] = $aid;

        $_SESSION['toastr']=[
            'type'=>'success',
            'message'=>'Account status updated successfully' .$newStatus . '!'
        ];
        header('Location: account.php');
        exit();
    }catch(PDOException $e){
        $_SESSION['toastr']=[
            'type'=>'danger',
            'message'=>'Account status is not updated! Error:' . $e->getMessage()
        ];
        header('Location: account.php');
        exit();
    }
}?>