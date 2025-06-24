<?php
include('../config/dbcon.php');
session_start();

if (isset($_SESSION['userAuth']) && $_SESSION['userAuth'] != "") {
    $credit_id = $_POST['credit_id'];
    $amount = $_POST['amount'];
    $credit_mode = $_POST['credit_mode'];
    $c_date = $_POST['c_date'];
    $c_id = $_POST['c_id'];
    $gl_id = $_POST['gl_id'];
    $ch_id = $_POST['ch_id']; 

    try {
        $pdo->beginTransaction();

        $query = "UPDATE credit_tbl SET amount=:amount, credit_mode=:credit_mode, c_date=:c_date, c_id=:c_id, gl_id=:gl_id WHERE credit_id=:credit_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':credit_id', $credit_id, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':credit_mode', $credit_mode, PDO::PARAM_STR);
        $stmt->bindParam(':c_date', $c_date, PDO::PARAM_STR);
        $stmt->bindParam(':c_id', $c_id, PDO::PARAM_INT);
        $stmt->bindParam(':gl_id', $gl_id, PDO::PARAM_STR);
        $stmt->execute();

        if ($credit_mode === 'Cheque') {
            $cheque_number = $_POST['cheque_number'] ?? null;
            $bank_name = $_POST['bank_name'] ?? null;
            $cheque_date = $_POST['cheque_date'] ?? null;

            if ($cheque_number && $bank_name && $cheque_date) {
                $chequeQuery = "UPDATE cheque_tbl SET credit_id=:credit_id, cheque_number=:cheque_number, bank_name=:bank_name, cheque_date=:cheque_date WHERE ch_id=:ch_id";
                $chequeStmt = $pdo->prepare($chequeQuery);
                $chequeStmt->bindParam(':credit_id', $credit_id, PDO::PARAM_INT);
                $chequeStmt->bindParam(':cheque_number', $cheque_number, PDO::PARAM_STR);
                $chequeStmt->bindParam(':bank_name', $bank_name, PDO::PARAM_STR);
                $chequeStmt->bindParam(':cheque_date', $cheque_date, PDO::PARAM_STR);
                $chequeStmt->bindParam(':ch_id', $ch_id, PDO::PARAM_INT);
                $chequeStmt->execute();
            }
        }

        $pdo->commit();
        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => 'Credit updated successfully!'
        ];
        header('Location: credit.php');
        exit();

    } catch (PDOException $e) {
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
document.querySelectorAll('.editCredit').forEach(button => {
  button.addEventListener('click', () => {
    // ... existing code ...
    
    // Set cheque details when editing
    document.getElementById('edit_cheque_number').value = button.getAttribute('data-cheque_number') || '';
    document.getElementById('edit_bank_name').value = button.getAttribute('data-bank_name') || '';
    document.getElementById('edit_cheque_date').value = button.getAttribute('data-cheque_date') || '';
  });
});