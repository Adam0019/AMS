<?php
include('../includes/header.php');

if(isset($_SESSION['userAuth']) && $_SESSION['userAuth']!==""){
    try{
        $uid = $_REQUEST['id'];

        $querry = "SELECT u_status FROM user_tbl WHERE u_id = :uid";
        $stmt = $pdo->prepare($querry);
        $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        $currentStatus= $stmt->fetchColumn();

        $newStatus = ($currentStatus == 'active') ? 'inactive' : 'active';

        $querry = "UPDATE user_tbl SET u_status = :newStatus WHERE u_id = :uid";
        $stmt = $pdo->prepare($querry);
        $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
        $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['uid'] = $uid;

        $_SESSION['toastr']=[
            'type'=>'success',
            'message'=>'user status updated successfully' .$newStatus . '!'
        ];
        header('Location: user.php');
        exit();
    }catch(PDOException $e){
        $_SESSION['toastr']=[
            'type'=>'danger',
            'message'=>'user status is not updated! Error:' . $e->getMessage()
        ];
        header('Location: user.php');
        exit();}
        
    }?>