<?php
include('../includes/header.php');
if($_SESSION['userAuth']!="" && $_SESSION['userAuth']!=NULL){
    include('../includes/sidebar.php');
    if(!isset($_SESSION['csrf_token'])){
        $_SESSION['csrf_token']=bin2hex(random_bytes(32));
    //    $account_balance = $_SESSION['acc_ammo'] ?? 0;
    }

// if (isset($_POST['acc_num']) || isset($_POST['acc_id'])) {
//     $sql = "SELECT acc_ammo FROM account_tbl WHERE ";
//     if (isset($_POST['acc_id'])) {
//         $sql .= "acc_id = :acc_id";
//         $stmt = $pdo->prepare($sql);
//         $stmt->bindParam(':acc_id', $_POST['acc_id']);
//     } else {
//         $sql .= "acc_num = :acc_num";
//         $stmt = $pdo->prepare($sql);
//         $stmt->bindParam(':acc_num', $_POST['acc_num']);
//     }

//     $stmt->execute();
//     $result = $stmt->fetch(PDO::FETCH_ASSOC);

//     if ($result) {
//         $_SESSION['acc_ammo'] = $result['acc_ammo'];
//         echo json_encode(['status' => 'success', 'acc_ammo' => $result['acc_ammo']]);
//     } else {
//         echo json_encode(['status' => 'error', 'message' => 'Account not found']);
//     }
// }
    // $acc_ammo = isset($_SESSION['acc_ammo']) ? $_SESSION['acc_ammo'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=3, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<button class="btn btn-info btn-sm" id="fetchAmountBtn">btn</button>
    <?php 
    
    // session_start();
    $account_balance = $_SESSION['acc_ammo'] ?? 0;
    echo 'amount:' . $account_balance;
    // echo 'amount:' .$_SESSION['c_amount'];
    ?>
</body>
</html>
<!-- <script>
    $('#fetchAmountBtn').click(function() {
    let acc_num = $('#acc_num').val(); // or a_id

    $.ajax({
        url: 'store_account.php',
        type: 'POST',
        data: { acc_num: acc_num },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#acc_ammo').val(response.amount);
            } else {
                alert(response.message);
            }
        }
    });
});
</script> -->
<?php
include('../includes/footer.php');
}
else{
    echo '<script>
    alert("Not Authorised!");
    window.location.href = "../index.php";
    </script>';
}


















##########################################################################

// <?php
// include('../config/dbcon.php');
// session_start();

// if (!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "") {
//     $_SESSION['toastr'] = [
//         'type' => 'error',
//         'message' => 'Unauthorized Access'
//     ];
//     header('Location: ../index.php');
//     exit();
// }

// if (isset($_POST['submit'])) {
//     // CSRF Token Validation
//     if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
//         $_SESSION['toastr'] = [
//             'type' => 'error',
//             'message' => 'Invalid CSRF token'
//         ];
//         header('Location: credit.php');
//         exit();
//     }

//     // Input validation
//     $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
//     $credit_mode = trim($_POST['credit_mode']);
//     $c_date = $_POST['c_date'];
//     $c_id_input = $_POST['c_id'];
//     $acc_id = $_SESSION['acc_id'];
//     $gl_id_input = $_POST['gl_id'];
//     $c_name = trim($_POST['c_name'] ?? '');
//     $c_email = trim($_POST['c_email'] ?? '');
//     $c_phone = trim($_POST['c_phone'] ?? '');
//     $c_address = trim($_POST['c_address'] ?? '');
//     $c_role = trim($_POST['c_role'] ?? '');
//     $gl_name = trim($_POST['gl_name'] ?? '');
//     // $acc_num = trim($_POST['acc_num'] ?? '');
//     $acc_num = $POST['acc_num'];
//     $acc_ammo = $POST['acc_ammo'];

//     // $_SESSION['c_amount'] = $amount;
//     // $_SESSION['acc_ammo']=$acc_ammo;
//     // Validate required fields
//     if (!$amount || $amount <= 0) {
//         $_SESSION['toastr'] = [
//             'type' => 'error',
//             'message' => 'Invalid amount'
//         ];
//         header('Location: credit.php');
//         exit();
//     }

//     if (!in_array($credit_mode, ['Demand Draft', 'Cheque', 'NEFT/RTGS'])) {
//         $_SESSION['toastr'] = [
//             'type' => 'error',
//             'message' => 'Invalid credit mode'
//         ];
//         header('Location: credit.php');
//         exit();
//     }
   

//     // Date validation
//     if (!DateTime::createFromFormat('Y-m-d', $c_date)) {
//         $_SESSION['toastr'] = [
//             'type' => 'error',
//             'message' => 'Invalid date format'
//         ];
//         header('Location: credit.php');
//         exit();
//     }

//     // Handle cheque details
//     $cheque_number = ($credit_mode === 'Cheque') ? trim($_POST['cheque_number'] ?? '') : '';
//     $bank_name = ($credit_mode === 'Cheque') ? trim($_POST['bank_name'] ?? '') : '';
//     $cheque_date = ($credit_mode === 'Cheque') ? ($_POST['cheque_date'] ?? '') : '';

//     // Validate cheque details if credit mode is Cheque
//     if ($credit_mode === 'Cheque') {
//         if (empty($cheque_number)) {
//             $_SESSION['toastr'] = [
//                 'type' => 'error',
//                 'message' => 'Cheque number is required for cheque payments'
//             ];
//             header('Location: credit.php');
//             exit();
//         }
        
//         if (empty($bank_name)) {
//             $_SESSION['toastr'] = [
//                 'type' => 'error',
//                 'message' => 'Bank name is required for cheque payments'
//             ];
//             header('Location: credit.php');
//             exit();
//         }
        
//         if (!empty($cheque_date) && !DateTime::createFromFormat('Y-m-d', $cheque_date)) {
//             $_SESSION['toastr'] = [
//                 'type' => 'error',
//                 'message' => 'Invalid cheque date format'
//             ];
//             header('Location: credit.php');
//             exit();
//         }
//     }

//     try {
//         $pdo->beginTransaction();

//         // Handle customer logic
//         $actual_c_id = null;
        
//         if ($c_id_input === 'other') {
//             // Adding new customer
//             if (empty($c_name)) {
//                 throw new Exception('Customer name is required for new customer');
//             }

//             // Generate new customer ID
//            // Instead of using REGEXP, use a more secure approach
//             $stmt = $pdo->prepare(
                
//                 // "SELECT COALESCE(MAX(CAST(c_id AS UNSIGNED)), 0) + 1 as next_id FROM customer_tbl WHERE c_id REGEXP '^[0-9]+$'"
//                 "SELECT MAX(c_id) as max_id FROM customer_tbl WHERE c_id REGEXP '^[0-9]+$'"
//         );
//             $stmt->execute();
//             $result = $stmt->fetch(PDO::FETCH_ASSOC);
//             $actual_c_id = ($result['max_id'] ? intval($result['max_id']) + 1 : 1);

//             // Insert new customer
//             $stmt = $pdo->prepare("INSERT INTO customer_tbl (c_id, c_name, c_email, c_phone, c_address, c_role) VALUES (:c_id, :c_name, :c_email, :c_phone, :c_address, :c_role)");
//             $stmt->bindParam(':c_id', $actual_c_id);
//             $stmt->bindParam(':c_name', $c_name);
//             $stmt->bindParam(':c_email', $c_email);
//             $stmt->bindParam(':c_phone', $c_phone);
//             $stmt->bindParam(':c_address', $c_address);
//             $stmt->bindParam(':c_role', $c_role);
//             $stmt->execute();
//         } else {
//             // Using existing customer
//             $actual_c_id = filter_var($c_id_input, FILTER_VALIDATE_INT);
//             if (!$actual_c_id) {
//                 throw new Exception('Invalid customer ID');
//             }

//             // Verify customer exists
//             $stmt = $pdo->prepare("SELECT 1 FROM customer_tbl WHERE c_id = :c_id");
//             $stmt->bindParam(':c_id', $actual_c_id);
//             $stmt->execute();
            
//             if ($stmt->rowCount() == 0) {
//                 throw new Exception('Selected customer does not exist');
//             }
//         }

//         // Handle GL account logic
//         $actual_gl_id = null;

//         if ($gl_id_input === 'other') {
//             if (empty($gl_name)) {
//                 throw new Exception('GL name is required for new GL account');
//             }

//             // Fixed: REGEXP instead of REGXP
//             $stmt = $pdo->prepare("SELECT MAX(gl_id) as max_id_1 FROM gl_tbl WHERE gl_id REGEXP '^[0-9]+$'");
//             $stmt->execute();
//             $result2 = $stmt->fetch(PDO::FETCH_ASSOC);
//             $actual_gl_id = ($result2['max_id_1'] ? intval($result2['max_id_1']) + 1 : 1);
            
//             // Insert new GL account
//             $stmt = $pdo->prepare("INSERT INTO gl_tbl (gl_id, gl_name) VALUES (:gl_id, :gl_name)");
//             $stmt->bindParam(':gl_id', $actual_gl_id);
//             $stmt->bindParam(':gl_name', $gl_name);
//             $stmt->execute();
//         } else {
//             // Using existing GL account
//             $actual_gl_id = filter_var($gl_id_input, FILTER_VALIDATE_INT);
//             if (!$actual_gl_id) {
//                 throw new Exception('Invalid GL account ID');
//             }

//             // Verify GL account exists
//             $stmt = $pdo->prepare("SELECT 1 FROM gl_tbl WHERE gl_id = :gl_id");
//             $stmt->bindParam(':gl_id', $actual_gl_id);
//             $stmt->execute();
            
//             if ($stmt->rowCount() == 0) {
//                 throw new Exception('Selected GL account does not exist');
//             }
//         }

//         // $newAmount=[]



//         // Insert credit record - Fixed: Use $actual_gl_id instead of $gl_id
//         $query = "INSERT INTO credit_tbl (amount, credit_mode, c_date, c_id, gl_id, acc_id, cheque_number, bank_name, cheque_date, credit_status)
//                   VALUES (:amount, :credit_mode, :c_date, :c_id, :gl_id, :acc_id, :cheque_number, :bank_name, :cheque_date, 'active')";

//         $stmt = $pdo->prepare($query);
//         $stmt->bindParam(':amount', $amount);
//         $stmt->bindParam(':credit_mode', $credit_mode);
//         $stmt->bindParam(':c_date', $c_date);
//         $stmt->bindParam(':c_id', $actual_c_id);
//         $stmt->bindParam(':gl_id', $actual_gl_id); // Fixed: Use actual_gl_id
//         $stmt->bindParam(':acc_id', $acc_id); // Fixed: Use acc_id
//         $stmt->bindParam(':cheque_number', $cheque_number);
//         $stmt->bindParam(':bank_name', $bank_name);
//         $stmt->bindParam(':cheque_date', $cheque_date);
//         $stmt->execute();

//         $pdo->commit();

//         $_SESSION['toastr'] = [
//             'type' => 'success',
//             'message' => 'Credit added successfully!'
//         ];
        
//         // Don't echo here if this is meant to be a redirect
//         header('Location: credit.php');
//         exit();

//     } catch (Exception $e) {
//         $pdo->rollBack();
//         $_SESSION['toastr'] = [
//             'type' => 'error',
//             'message' => 'Transaction failed: ' . $e->getMessage()
//         ];
        
//         header('Location: credit.php');
//         exit();
//     }
// } else {
//     $_SESSION['toastr'] = [
//         'type' => 'error',
//         'message' => 'Invalid request method'
//     ];
//     header('Location: credit.php');
//     exit();
// }
// ?>



#########################################################################################

<?php
include('../config/dbcon.php');
session_start();

if (isset($_SESSION['userAuth']) && $_SESSION['userAuth'] != "") {
//  if (isset($_POST['submit'])) {
    $credit_id = $_POST['credit_id'];
    $amount = $_POST['amount'];
    $credit_mode = $_POST['credit_mode'];
    $c_date = $_POST['c_date'];
    $c_id = $_POST['c_id'];
    $gl_id = $_POST['gl_id'];
    $acc_id = $_POST['acc_id'];
    $cheque_number = $_POST['cheque_number'] ?? null;
    $bank_name = $_POST['bank_name'] ?? null;
    $cheque_date = $_POST['cheque_date'] ?? null;
    
     
    try {
     

        $query = "UPDATE credit_tbl SET amount=:amount, credit_mode=:credit_mode, c_date=:c_date, c_id=:c_id, gl_id=:gl_id, acc_id=:acc_id, cheque_number=:cheque_number, bank_name=:bank_name, cheque_date=:cheque_date  WHERE credit_id=:credit_id";

        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':credit_id', $credit_id, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':credit_mode', $credit_mode, PDO::PARAM_STR);
        $stmt->bindParam(':c_date', $c_date, PDO::PARAM_STR);
        $stmt->bindParam(':c_id', $c_id, PDO::PARAM_INT);
        $stmt->bindParam(':gl_id', $gl_id, PDO::PARAM_STR);
        $stmt->bindParam(':acc_id', $acc_id, PDO::PARAM_INT);
        $stmt->bindParam(':cheque_number', $cheque_number, PDO::PARAM_STR);
        $stmt->bindParam(':bank_name', $bank_name, PDO::PARAM_STR);
        $stmt->bindParam(':cheque_date', $cheque_date, PDO::PARAM_STR);
        $stmt->execute();
        // $pdo->commit();
       
       
        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => 'Credit updated successfully!'
        ];
        header('Location: credit.php');
        exit();

    } catch (PDOException $e) {
        // $pdo->rollBack();
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Error updating credit: ' . $e->getMessage()
        ];
        header('Location: credit.php');
        exit();
    }
    //  }

} else {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Unauthorized Access'
    ];
    header('Location: ../index.php');
    exit();
}
?>
