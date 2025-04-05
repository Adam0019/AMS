<?php
include('../includes/header.php');

if(isset($_SESSION['userAuth']) && $_SESSION['userAuth']!==""){
    try{
        $glid = $_REQUEST['id'];

        $querry = "SELECT gl_status FROM gl_tbl WHERE gl_id = :glid";
        $stmt = $pdo->prepare($querry);
        $stmt->bindParam(':glid', $glid, PDO::PARAM_INT);
        $stmt->execute();
        $currentStatus= $stmt->fetchColumn();

        $newStatus = ($currentStatus == 'active') ? 'inactive' : 'active';

        $querry = "UPDATE gl_tbl SET gl_status = :newStatus WHERE gl_id = :glid";
        $stmt = $pdo->prepare($querry);
        $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
        $stmt->bindParam(':glid', $glid, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['glid'] = $glid;

        $_SESSION['toastr']=[
            'type'=>'success',
            'message'=>'GL status updated successfully' .$newStatus . '!'
        ];
        header('Location: gl_title.php');
        exit();
    }catch(PDOException $e){
        $_SESSION['toastr']=[
            'type'=>'danger',
            'message'=>'GL status is not updated! Error:' . $e->getMessage()
        ];
        header('Location: gl_title.php');
        exit();
    }
} ?>