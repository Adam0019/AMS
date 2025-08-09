<?php
include('../config/dbcon.php');
session_start();

if (isset($_SESSION['userAuth']) && $_SESSION['userAuth'] != "") {
     if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Invalid CSRF token'
        ];
        header('Location: debit.php');
        exit();
    }
//  if (isset($_POST['submit'])) {
    $debit_id = $_POST['debit_id'];
    $new_amount = $_POST['amount'];
    $debit_mode = $_POST['debit_mode'];
    $d_date = $_POST['d_date'];
    $dbt_c_id = $_POST['dbt_c_id'];
    $dbt_gl_id = $_POST['dbt_gl_id'];
    $dbt_acc_id = $_POST['dbt_acc_id'];
    $cheque_number = $_POST['cheque_number'] ?? null;
    $bank_name = $_POST['bank_name'] ?? null;
    $cheque_date = $_POST['cheque_date'] ?? null;
    
    $new_amount=filter_var($new_amount, FILTER_VALIDATE_FLOAT);
    if(!$new_amount||$new_amount<=0){
        $_SESSION['toastr']=[
            'type'=>'error',
            'message'=>'Invalid amount'
        ];
        header('Location: debit.php');
        exit();
    }
    if ($debit_mode === 'Cheque'){
        if(empty($cheque_number)){
            $_SESSION['toastr']=[
                'type'=>'error',
                'message'=>'Cheque number is required for cheque payments'
            ];
            header('Location: debit.php');
            exit();
        }

        if(empty($bank_name)){
            $_SESSION['toastr']=[
                'type' => 'error',
                'message' => 'Bank name is required for cheque payments'
            ];
            header('Location: debit.php');
            exit();
        }

    }

    try {
        $pdo->beginTransaction();
        $querry_old="SELECT amount,dbt_acc_id FROM debit_tbl WHERE debit_id=:debit_id";
        $stmt_old=$pdo->prepare($querry_old);
        $stmt_old->bindParam(':debit_id',$debit_id,PDO::PARAM_INT);
        $stmt_old->execute();
        $old_debit=$stmt_old->fetch(PDO::FETCH_ASSOC);

        if(!$old_debit){
            throw new Exception('Debit record not found');
        }
        $old_amount=$old_debit['amount'];
        $old_dbt_acc_id = $old_debit['dbt_acc_id'];
        
       $amount_difference= $new_amount-$old_amount;
     

        $query = "UPDATE debit_tbl SET amount=:amount, debit_mode=:debit_mode, d_date=:d_date, dbt_c_id=:dbt_c_id, dbt_gl_id=:dbt_gl_id, dbt_acc_id=:dbt_acc_id, cheque_number=:cheque_number, bank_name=:bank_name, cheque_date=:cheque_date  WHERE debit_id=:debit_id";

        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':debit_id', $debit_id, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $new_amount, PDO::PARAM_STR);
        $stmt->bindParam(':debit_mode', $debit_mode, PDO::PARAM_STR);
        $stmt->bindParam(':d_date', $d_date, PDO::PARAM_STR);
        $stmt->bindParam(':dbt_c_id', $dbt_c_id, PDO::PARAM_INT);
        $stmt->bindParam(':dbt_gl_id', $dbt_gl_id, PDO::PARAM_STR);
        $stmt->bindParam(':dbt_acc_id', $dbt_acc_id, PDO::PARAM_STR);
        $stmt->bindParam(':cheque_number', $cheque_number, PDO::PARAM_STR);
        $stmt->bindParam(':bank_name', $bank_name, PDO::PARAM_STR);
        $stmt->bindParam(':cheque_date', $cheque_date, PDO::PARAM_STR);
        $stmt->execute();
         // Update account balances based on account changes
        if ($old_dbt_acc_id != $dbt_acc_id) {
            // Account changed - remove old amount from old account and add new amount to new account
            
            // Remove old amount from old account
            $update_old_acc = "UPDATE account_tbl SET acc_ammo = acc_ammo - :old_amount WHERE acc_id = :old_dbt_acc_id";
            $stmt_old_acc = $pdo->prepare($update_old_acc);
            $stmt_old_acc->bindParam(':old_amount', $old_amount, PDO::PARAM_STR);
            $stmt_old_acc->bindParam(':old_dbt_acc_id', $old_dbt_acc_id, PDO::PARAM_INT);
            $stmt_old_acc->execute();

            // Add new amount to new account
            $update_new_acc = "UPDATE account_tbl SET acc_ammo = acc_ammo + :new_amount WHERE acc_id = :new_acc_id";
            $stmt_new_acc = $pdo->prepare($update_new_acc);
            $stmt_new_acc->bindParam(':new_amount', $new_amount, PDO::PARAM_STR);
            $stmt_new_acc->bindParam(':new_acc_id', $acc_id, PDO::PARAM_INT);
            $stmt_new_acc->execute();

            // Verify both updates were successful
            if ($stmt_old_acc->rowCount() === 0) {
                throw new Exception('Failed to update old account balance - account may not exist');
            }
            if ($stmt_new_acc->rowCount() === 0) {
                throw new Exception('Failed to update new account balance - account may not exist');
            }

        } else {
            // Same account - just apply the difference
            if ($amount_difference != 0) {
                $update_acc = "UPDATE account_tbl SET acc_ammo = acc_ammo + :amount_difference WHERE acc_id = :acc_id";
                $stmt_acc = $pdo->prepare($update_acc);
                $stmt_acc->bindParam(':amount_difference', $amount_difference, PDO::PARAM_STR);
                $stmt_acc->bindParam(':acc_id', $acc_id, PDO::PARAM_INT);
                $stmt_acc->execute();

                // Verify the update was successful
                if ($stmt_acc->rowCount() === 0) {
                    throw new Exception('Failed to update account balance - account may not exist');
                }
            }
        }

        // Check for negative balance (optional - you might want to allow this)
        $balance_check = "SELECT acc_ammo FROM account_tbl WHERE acc_id IN (:old_dbt__acc_id, :new_acc_id)";
        $stmt_balance = $pdo->prepare($balance_check);
        $stmt_balance->bindParam(':old_dbt__acc_id', $old_dbt__acc_id, PDO::PARAM_INT);
        $stmt_balance->bindParam(':new_acc_id', $acc_id, PDO::PARAM_INT);
        $stmt_balance->execute();
        
        while ($balance = $stmt_balance->fetch(PDO::FETCH_ASSOC)) {
            if ($balance['acc_ammo'] < 0) {
                // Uncomment the next line if you want to prevent negative balances
                // throw new Exception('Update would result in negative account balance');
                
                // Or just log a warning
                error_log("Warning: Account balance is negative after credit update");
            }
        }

        $pdo->commit();
       
       
        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => 'Debit updated successfully!'
        ];
        header('Location: debit.php');
        exit();

    } catch (PDOException $e) {
        // $pdo->rollBack();
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Error updating debit: ' . $e->getMessage()
        ];
        header('Location: debit.php');
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
