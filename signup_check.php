<?php

include('config/dbcon.php');
// header("Content-Type: application/json");

if(isset($_POST['submit'])){
    if (!isset($_POST['u_name']) || !isset($_POST['u_email']) || !isset($_POST['u_phone']) || !isset($_POST['password']) || !isset($_POST['role'])) {
        echo json_encode(["error" => "Missing required fields"]);
        exit;}
        $name = $_POST['u_name'];
        $email = $_POST['u_email'];
        $phone = $_POST['u_phone'];
        $username = $_POST['u_name'];
        $password = $_POST['password'];
        $role = $_POST['role'];

    $stmt = $pdo->prepare("SELECT * FROM user_tbl WHERE username =:username");
    $stmt->execute([$username]);
    if ($stmt-> fetch()){
        echo '<script>
        alert("Username already exists");
        window.location.href="signup.php";
        </script>';
        exit;
    }
else{
    $sql = "INSERT INTO user_tbl (u_name, u_email, u_phone, username, password, role) VALUES (:name, :email, :phone, :username, :password, :role)";

    try{
    $stmt = $pdo->prepare($sql);
    $stmt-> bindParam(':name', $name);
    $stmt-> bindParam(':email', $email);
    $stmt-> bindParam(':phone', $phone);
    $stmt-> bindParam(':username', $username);
    $stmt-> bindParam(':password', $password);
    $stmt-> bindParam(':role', $role);
    if($stmt-> execute()){
        $_SESSION['userAuth'] = "Authorised";}
     $_SESSION['toastr']=['
            type' => 'success', // success, error, info, warning
            'message' => 'User created successfully!'
    ];

    if(isset($_POST['fromUser'])){
        // header('Location: ../User/user.php');
        // exit();
        echo json_encode(["success" => "User created successfully"]);
        exit();
    }

    header('Location: index.php');
    exit();
    }catch (PDOException $e){
        $_SESSION['toastr']=[
            'type' => 'danger',
            'message' => 'User is not created! Error:' . $e->getMessage()
        ];
    }
    }}

    // if ($stmt->execute()) {
    //     echo '<script>
    //     alert("Registration successful!");
    //     window.location.href="index.php";
    //     </script>';
    // } else {
    //     echo '<script>
    //     alert("Registration failed!");
    //     window.location.href="signup.php";
    //     </script>';
    // }




?>