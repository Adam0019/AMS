<?php
include('../includes/header.php');
if($_SESSION['userAuth']!="" && $_SESSION['userAuth']!=NULL){
    include('../includes/sidebar.php');
    if(!isset($_SESSION['csrf_token'])){
        $_SESSION['csrf_token']=bin2hex(random_bytes(32));
    }
?>

<main class="mt-3 pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <h4>Balance sheet</h4>
            </div>
            <div class="col-md-4">
                <!-- Add Debit Modal Button -->
                <button type="button" class="btn btn-light float-end" style="margin-left: 15px;" data-bs-toggle="modal" data-bs-target="#debitModal">
                    <i class="bi bi-person-fill-add"></i>
                    Add Debit
                </button> 
                 
                <!-- Add credit Modal Button -->
               <button type="button" class="btn btn-light float-end" style="margin-right: 15px;"data-bs-toggle="modal" data-bs-target="#creditModal">
                   <i class="bi bi-person-fill-add"></i>
                   Add Credit
               </button>
            </div>
        </div>
          <?php
        // Database connection using PDO
        require_once('../config/dbcon.php');
        try{
            
        }
        catch(PDOException $e){
             echo "Error fetching records: " . htmlspecialchars($e->getMessage());
            $result = [];
        }
        ?>
    </div>

</main>

<?php
include('../includes/footer.php');
}
else{
    echo '<script>
    alert("Not Authorised!");
    window.location.href = "../index.php";
    </script>';
}