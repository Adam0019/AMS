<?php
include('../includes/header.php');
if($_SESSION['userAuth']!="" && $_SESSION['userAuth']!=NULL){
// if($_SESSION['userRole']=="Admin"){
     
    include('../includes/sidebar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
<?php
include('../includes/footer.php');
}
else{
   echo '<script>
    alert("Not Authorised!");
    window.location.href = "../index.php";
    </script>';
}