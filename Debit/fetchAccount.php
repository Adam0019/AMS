<?php
include('../config/dbcon.php');
session_start();
if(isset($_POST['dbt_acc_id'])){
    $dbt_acc_id = $_POST['dbt_acc_id'];

    $sql = "SELECT * FROM credit_tbl WHERE dbt_acc_id = :dbt_acc_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dbt_acc_id', $dbt_acc_id, PDO::PARAM_INT);
    $stmt->execute();

    $accs=$stmt->fetch(PDO::FETCH_ASSOC);
    foreach($accs as $acc){
        echo'<option value="'.$acc['dbt_acc_id'].'">'.$acc['acc_name'].'</option>';
    }
}?>