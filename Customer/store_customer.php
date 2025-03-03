<?php
include('../includes/header.php');

if(isset($_SESSION['userAuth']) && $_SESSION['userAuth']!==""){

    if(isset($_POST['submit'])){
        $name = $_POST['c_name'];
        $email = $_POST['c_email'];
        $phone = $_POST['c_phone'];
        $address = $_POST['c_address'];
        $role = $_POST['c_role'];



        // // check if the email is already exists
        // $stmt = $pdo->prepare("SELECT * FROM customer_tbl WHERE c_email = :email");
        // $stmt->execute([ $email]);
        // if ($stmt->fetch()){
        //     echo'<script>
        //     alert("Email already exists")
        //     window.location.href = "customer.php";
        //     </script>';
        //     exit();


        //  }else{
        $sql = "INSERT INTO customer_tbl (c_name, c_email, c_phone, c_address, c_role) VALUES (:name, :email, :phone, :address, :role)";
        
        try{
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        $_SESSION['toastr']=[
            'type'=>'success',
            'message'=>'Customer added successfully!'
        ];
        // if(isset($_POST['fromCreateCredit'])){
        //     header('Location: ../Credit/credit.php');
        //     exit();
        // }
        // elseif(isset($_POST['fromCreateDebit'])){
        //     header('Location: ../Debit/debit.php');
        //     exit();
        // }
        // else{
        //     header('Location: customer.php');
        //     exit();
        // } 
        header('Location: customer.php');
        exit();
    }
        catch (PDOException $e){
            $_SESSION['toastr']=[
            'type'=>'danger',
            'message'=>'Customer is not created! Error:' . $e->getMessage()

        ];
        // header('Location: customer.php');
        // exit();
        echo '<script>
        alert("Customer is not created!");
        </script>';
        }
    }

}

if (isset($_POST['click_view_btn'])){
    $id = $_POST['cuid'];
    
}

?>