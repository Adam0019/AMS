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
    $cheque_number = $_POST['cheque_number'] ?? null;
    $bank_name = $_POST['bank_name'] ?? null;
    $cheque_date = $_POST['cheque_date'] ?? null;
    

    try {
     

        $query = "UPDATE credit_tbl SET amount=:amount, credit_mode=:credit_mode, c_date=:c_date, c_id=:c_id, gl_id=:gl_id, cheque_number=:cheque_number, bank_name=:bank_name, cheque_date=:cheque_date  WHERE credit_id=:credit_id";

        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':credit_id', $credit_id, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':credit_mode', $credit_mode, PDO::PARAM_STR);
        $stmt->bindParam(':c_date', $c_date, PDO::PARAM_STR);
        $stmt->bindParam(':c_id', $c_id, PDO::PARAM_INT);
        $stmt->bindParam(':gl_id', $gl_id, PDO::PARAM_STR);
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
