<?php
include('../config/dbcon.php');
session_start();

if (isset($_SESSION['userAuth']) && $_SESSION['userAuth'] != "") {
    if(isset($_POST['submit'])){
        $gl_id = $_POST['gl_id'];
        $gl_name = $_POST['gl_name'];
        $gl_descript = $_POST['gl_descript'];
        $gl_type = $_POST['gl_type'];

        $query = "INSERT INTO gl_tbl (gl_id, gl_name, gl_descript, gl_type) VALUES (:gl_id, :gl_name, :gl_descript, :gl_type)";

        try{
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':gl_id', $gl_id, PDO::PARAM_INT);
            $stmt->bindParam(':gl_name', $gl_name, PDO::PARAM_STR);
            $stmt->bindParam(':gl_descript', $gl_descript,PDO::PARAM_STR);
            $stmt->bindParam(':gl_type', $gl_type, PDO::PARAM_STR);

            $stmt->execute();
            
            
            // echo('GL Title created successfully!'),
            
            $_SESSION['toastr']=[
                'type'=>'success',
                'message'=>'GL Title added Successfully!'
                
            ];
            header('Location: gl_title.php');
            exit();
        }catch(PDOException $e){
            $_SESSION['toastr']=[
                'type'=>'error',
                'message'=>'Error adding GL Title: '.$e->getMessage()
            ];
            header('Location: gl_title.php');
            exit();
        }
    }
}?>