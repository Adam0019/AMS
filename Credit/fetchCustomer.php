<?php
include('../config/dbcon.php');
session_start();
if(isset($_POST['c_id'])){
    $c_id = $_POST['c_id'];

    $sql = "SELECT * FROM credit_tbl WHERE c_id = :c_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':c_id', $c_id, PDO::PARAM_INT);
    $stmt->execute();

    $customers=$stmt->fetch(PDO::FETCH_ASSOC);
    foreach($customers as $customer){
        echo'<option value="'.$customer['c_id'].'">'.$customer['c_name'].'</option>';
    }
}?>