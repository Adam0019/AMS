<?php
include('../config/dbcon.php');
session_start();
if(isset($_POST['dbt_gl_id'])){
    $dbt_gl_id = $_POST['dbt_gl_id'];

    $sql = "SELECT * FROM debit_tbl WHERE dbt_gl_id = :dbt_gl_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dbt_gl_id', $dbt_gl_id, PDO::PARAM_INT);
    $stmt->execute();

    $gls=$stmt->fetch(PDO::FETCH_ASSOC);
    foreach($gls as $gl){
        echo'<option value="'.$gl['dbt_gl_id'].'">'.$gl['gl_name'].'</option>';
    }
}?>