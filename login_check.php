<?php
include('config/dbcon.php');

if (isset($_POST['submit'])){
    $username=$_POST['username'];
    $password=$_POST['password'];

    $sql="SELECT * FROM user_tbl WHERE username=:username";
    $smt=$pdo -> prepare($sql);
    $smt-> execute(['username'=>$username]);
    $user = $smt->fetch(PDO::FETCH_ASSOC);
   // echo $user['username'];

    if ($user && $password == $user['password']){
        session_start();
        $_SESSION['userAuth'] ="Authorised";
        $_SESSION['userRole'] = $user['role'];
                header("Location:Dashboard/dashboard.php");
               // echo $user['username'];
        
    }
    else{
        echo '<script>
        alert("Invalid username or password");
        window.location.href="index.php";
        </script>';
    }
}

?>