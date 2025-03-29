<?php
include('../config/dbcon.php');
session_start();

if (isset($_POST['u_id'])) {
    $u_id = $_POST['u_id'];

    $sql = "SELECT * FROM account_tbl WHERE u_id = :u_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':u_id', $u_id, PDO::PARAM_INT);
    $stmt->execute();

    $users = $stmt->fetch(PDO::FETCH_ASSOC);
    foreach($users as $user){
        echo '<option value="'.$user['u_id'].'">'.$user['u_name'].'</option>';
    }
}