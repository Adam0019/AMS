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

        // $_SESSION['c_id'] = $customer['c_id'];           // from customer.php
        // $_SESSION['acc_id'] = $account['acc_id'];      // from account.php
        // //For trail balance 
        // $_SESSION['accAmount']="";
        $_SESSION['acc_num']="";
        $_SESSION['c_name']="";
        $_SESSION['c_amount']="";

        // $_SESSION['new_ammo']="";

        // $_SESSION['acc_ammo']="";

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