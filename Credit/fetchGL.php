<?php
include('../config/dbcon.php');
session_start();
if(isset($_POST['gl_id'])){
    $gl_id = $_POST['gl_id'];

    $sql = "SELECT * FROM credit_tbl WHERE gl_id = :gl_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':gl_id', $gl_id, PDO::PARAM_INT);
    $stmt->execute();

    $gls=$stmt->fetch(PDO::FETCH_ASSOC);
    foreach($gls as $gl){
        echo'<option value="'.$gl['gl_id'].'">'.$gl['gl_name'].'</option>';
    }
}?>