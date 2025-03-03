<?php 
include('config/dbcon.php');

if(isset($_POST['submit'])){
    $name = $_POST['u_name'];
    $email = $_POST['u_email'];
    $username = $_POST['u_name'];
    $password = $_POST['password'];

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
    $sql = "INSERT INTO user_tbl (u_name, u_email, username, password) VALUES (:name, :email, :username, :password)";

    try{
    $stmt = $pdo->prepare($sql);
    $stmt-> bindParam(':name', $name);
    $stmt-> bindParam(':email', $email);
    $stmt-> bindParam(':username', $username);
    $stmt-> bindParam(':password', $password);
    if($stmt-> execute()){
        $_SESSION['userAuth'] = "Authorised";}
     $_SESSION['toastr']=['
            type' => 'success', // success, error, info, warning
            'message' => 'User created successfully!'
    ];
    header('Location: index.php');
    exit();
    }catch (PDOException $e){
        $_SESSION['toastr']=[
            'type' => 'danger',
            'message' => 'User is not created! Error:' . $e->getMessage()
        ];
    }
    }

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

}


?>