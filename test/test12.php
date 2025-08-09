<?php
include('../config/dbcon.php');
session_start();

if (isset($_SESSION['userAuth']) && $_SESSION['userAuth'] != "") {
    // CSRF Token Validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Invalid CSRF token'
        ];
        header('Location: credit.php');
        exit();
    }

    $credit_id = $_POST['credit_id'];
    $new_amount = floatval($_POST['amount']);
    $credit_mode = $_POST['credit_mode'];
    $c_date = $_POST['c_date'];
    $c_id = $_POST['c_id'];
    $gl_id = $_POST['gl_id'];
    $acc_id = $_POST['acc_id'];
    $cheque_number = $_POST['cheque_number'] ?? null;
    $bank_name = $_POST['bank_name'] ?? null;
    $cheque_date = $_POST['cheque_date'] ?? null;

    // Input validation
    if ($new_amount <= 0) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Amount must be greater than 0'
        ];
        header('Location: credit.php');
        exit();
    }

    try {
        $pdo->beginTransaction();

        // First, get the original credit record to find the old amount and account
        $selectStmt = $pdo->prepare("SELECT amount, acc_id FROM credit_tbl WHERE credit_id = :credit_id");
        $selectStmt->bindParam(':credit_id', $credit_id, PDO::PARAM_INT);
        $selectStmt->execute();
        $originalCredit = $selectStmt->fetch(PDO::FETCH_ASSOC);

        if (!$originalCredit) {
            throw new Exception('Credit record not found');
        }

        $old_amount = floatval($originalCredit['amount']);
        $old_acc_id = $originalCredit['acc_id'];

        // Calculate the difference in amounts
        $amount_difference = $new_amount - $old_amount;

        // If account ID changed, we need to reverse the old amount and add the new amount
        if ($old_acc_id != $acc_id) {
            // Remove old amount from old account
            $revertStmt = $pdo->prepare("UPDATE account_tbl SET acc_ammo = acc_ammo - :old_amount WHERE acc_id = :old_acc_id");
            $revertStmt->bindParam(':old_amount', $old_amount);
            $revertStmt->bindParam(':old_acc_id', $old_acc_id, PDO::PARAM_INT);
            $revertStmt->execute();

            // Add new amount to new account
            $addStmt = $pdo->prepare("UPDATE account_tbl SET acc_ammo = acc_ammo + :new_amount WHERE acc_id = :new_acc_id");
            $addStmt->bindParam(':new_amount', $new_amount);
            $addStmt->bindParam(':new_acc_id', $acc_id, PDO::PARAM_INT);
            $addStmt->execute();

            // Verify both accounts exist and were updated
            if ($revertStmt->rowCount() === 0) {
                throw new Exception('Failed to update old account - account may not exist');
            }
            if ($addStmt->rowCount() === 0) {
                throw new Exception('Failed to update new account - account may not exist');
            }
        } else {
            // Same account, just adjust by the difference
            if ($amount_difference != 0) {
                $adjustStmt = $pdo->prepare("UPDATE account_tbl SET acc_ammo = acc_ammo + :amount_diff WHERE acc_id = :acc_id");
                $adjustStmt->bindParam(':amount_diff', $amount_difference);
                $adjustStmt->bindParam(':acc_id', $acc_id, PDO::PARAM_INT);
                $adjustStmt->execute();

                // Verify the account was updated
                if ($adjustStmt->rowCount() === 0) {
                    throw new Exception('Failed to update account balance - account may not exist');
                }
            }
        }

        // Update the credit record
        $query = "UPDATE credit_tbl SET amount=:amount, credit_mode=:credit_mode, c_date=:c_date, c_id=:c_id, gl_id=:gl_id, acc_id=:acc_id, cheque_number=:cheque_number, bank_name=:bank_name, cheque_date=:cheque_date WHERE credit_id=:credit_id";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':credit_id', $credit_id, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $new_amount, PDO::PARAM_STR);
        $stmt->bindParam(':credit_mode', $credit_mode, PDO::PARAM_STR);
        $stmt->bindParam(':c_date', $c_date, PDO::PARAM_STR);
        $stmt->bindParam(':c_id', $c_id, PDO::PARAM_INT);
        $stmt->bindParam(':gl_id', $gl_id, PDO::PARAM_STR);
        $stmt->bindParam(':acc_id', $acc_id, PDO::PARAM_INT);
        $stmt->bindParam(':cheque_number', $cheque_number, PDO::PARAM_STR);
        $stmt->bindParam(':bank_name', $bank_name, PDO::PARAM_STR);
        $stmt->bindParam(':cheque_date', $cheque_date, PDO::PARAM_STR);
        $stmt->execute();

        // Verify the credit record was updated
        if ($stmt->rowCount() === 0) {
            throw new Exception('No credit record was updated - record may not exist');
        }

        // Store updated information in session for reference
        $_SESSION['updated_credit_amount'] = $new_amount;
        $_SESSION['old_credit_amount'] = $old_amount;
        $_SESSION['amount_difference'] = $amount_difference;
        $_SESSION['updated_acc_id'] = $acc_id;

        // Get the updated account balance for session
        $balanceStmt = $pdo->prepare("SELECT acc_ammo FROM account_tbl WHERE acc_id = :acc_id");
        $balanceStmt->bindParam(':acc_id', $acc_id, PDO::PARAM_INT);
        $balanceStmt->execute();
        $updatedAccount = $balanceStmt->fetch(PDO::FETCH_ASSOC);

        if ($updatedAccount) {
            $_SESSION['updated_acc_balance'] = $updatedAccount['acc_ammo'];
        }

        $pdo->commit();

        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => 'Credit updated successfully! Account balance adjusted by ' . number_format($amount_difference, 2)
        ];
        header('Location: credit.php');
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Error updating credit: ' . $e->getMessage()
        ];
        header('Location: credit.php');
        exit();
    }

} else {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Unauthorized Access'
    ];
    header('Location: ../index.php');
    exit();
}
?>
















#########################################################################################



<?php
include('../config/dbcon.php');
session_start();

if (isset($_SESSION['userAuth']) && $_SESSION['userAuth'] != "") {
 
$acc_id = $_POST['acc_id'];
$a_type=$_POST['a_type'];
$u_id = $_POST['u_id'];
$acc_ammo = $_POST['acc_ammo'];
$acc_type = $_POST['acc_type'];
// $acc_num = $_SESSION['acc_num'];
// $ab_name = $_POST['ab_name'];
 // In store_account.php and update_account.php
if($_POST['a_type'] === 'Cash') {
    $acc_num = 'Cash';
    $ab_name = 'Cash';
} else {
    $acc_num = $_POST['acc_num'];
    $ab_name = $_POST['ab_name'];
}
try{
    $query = "UPDATE account_tbl SET acc_num = :acc_num, ab_name = :name, a_type=:a_type, u_id = :u_id, acc_type = :acc_type, acc_ammo = :acc_ammo WHERE acc_id = :acc_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':acc_id', $acc_id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $ab_name, PDO::PARAM_STR);
    $stmt->bindParam(':a_type', $a_type, PDO::PARAM_STR);
    $stmt->bindParam(':u_id', $u_id, PDO::PARAM_INT);
    $stmt->bindParam(':acc_num', $acc_num, PDO::PARAM_STR);
    $stmt->bindParam(':acc_ammo', $acc_ammo, PDO::PARAM_STR);
    $stmt->bindParam(':acc_type', $acc_type, PDO::PARAM_STR);
    if($stmt->execute()){
        echo json_encode(["status"=>"success", "message"=>"Account updated successfully!"]);
        header("location: account.php");
        // exit();
    }else{
        echo json_encode(["status"=>"error", "message"=>"Error updating account."]);
    }

}catch(PDOException $e){
    echo json_encode(["status"=>"error", "message"=>"Database error: ".$e->getMessage()]);
}
} else {
    // Unauthorized access
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Unauthorized Access'
    ];
    header('Location: ../index.php');
    exit();
}
?>


