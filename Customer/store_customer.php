<?php
include('../includes/header.php');

if(isset($_SESSION['userAuth']) && $_SESSION['userAuth']!==""){

    if(isset($_POST['submit'])){
        $c_name = $_SESSION['c_name'];
        $c_email = $_POST['c_email'];
        $c_phone = $_POST['c_phone'];
        $c_address = $_POST['c_address'];
        $c_role = $_POST['c_role'];


        $sql = "INSERT INTO customer_tbl (c_name, c_email, c_phone, c_address, c_role) VALUES (:c_name, :c_email, :c_phone, :c_address, :c_role)";
        
        try{
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':c_name', $c_name);
        $stmt->bindParam(':c_email', $c_email);
        $stmt->bindParam(':c_phone', $c_phone);
        $stmt->bindParam(':c_address', $c_address);
        $stmt->bindParam(':c_role', $c_role);
        $stmt->execute();

        $_SESSION['toastr']=[
            'type'=>'success',
            'message'=>'Customer added successfully!'
        ];
       
        header('Location: customer.php');
        exit();
    }
        catch (PDOException $e){
            $_SESSION['toastr']=[
            'type'=>'danger',
            'message'=>'Customer is not created! Error:' . $e->getMessage()

        ];
        
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