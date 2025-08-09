<?php
include('../config/dbcon.php');
session_start();
if(isset($_POST['acc_id'])){
    $acc_id = $_POST['acc_id'];

    $sql = "SELECT * FROM credit_tbl WHERE acc_id = :acc_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':acc_id', $acc_id, PDO::PARAM_INT);
    $stmt->execute();

    $accs=$stmt->fetch(PDO::FETCH_ASSOC);
    foreach($accs as $acc){
        echo'<option value="'.$acc['acc_id'].'">'.$acc['acc_name'].'</option>';
        // echo'<option value="'.$acc['acc_nun'].'">'.$acc['acc_name'].'</option>';
    }
}?>