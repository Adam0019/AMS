<?php
include('../config/dbcon.php');
session_start();

if (!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "") {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Unauthorized Access'
    ];
    header('Location: ../index.php');
    exit();
}

if (isset($_POST['submit'])) {
    // CSRF Token Validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Invalid CSRF token'
        ];
        header('Location: credit.php');
        exit();
    }

    // Input validation
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    $credit_mode = trim($_POST['credit_mode']);
    $c_date = $_POST['c_date'];
    $c_id_input = $_POST['c_id'];
    $gl_id = filter_var($_POST['gl_id'], FILTER_VALIDATE_INT);
    $c_name = trim($_POST['c_name'] ?? '');

    // Validate required fields
    if (!$amount || $amount <= 0) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Invalid amount'
        ];
        header('Location: credit.php');
        exit();
    }

    if (!in_array($credit_mode, ['Demand Draft', 'Cheque', 'NEFT/RTGS'])) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Invalid credit mode'
        ];
        header('Location: credit.php');
        exit();
    }

    if (!$gl_id) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Invalid GL account'
        ];
        header('Location: credit.php');
        exit();
    }

    // Date validation
    if (!DateTime::createFromFormat('Y-m-d', $c_date)) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Invalid date format'
        ];
        header('Location: credit.php');
        exit();
    }

    // Handle cheque details
    $cheque_number = ($credit_mode === 'Cheque') ? trim($_POST['cheque_number'] ?? '') : '';
    $bank_name = ($credit_mode === 'Cheque') ? trim($_POST['bank_name'] ?? '') : '';
    $cheque_date = ($credit_mode === 'Cheque') ? ($_POST['cheque_date'] ?? '') : '';

    try {
        $pdo->beginTransaction();

        // Handle customer logic
        $actual_c_id = null;
        
        if ($c_id_input === 'other') {
            // Adding new customer
            if (empty($c_name)) {
                throw new Exception('Customer name is required for new customer');
            }

            // Generate new customer ID
            $stmt = $pdo->prepare("SELECT MAX(c_id) as max_id FROM customer_tbl WHERE c_id REGEXP '^[0-9]+$'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $actual_c_id = ($result['max_id'] ? intval($result['max_id']) + 1 : 1);

            // Insert new customer
            $stmt = $pdo->prepare("INSERT INTO customer_tbl (c_id, c_name) VALUES (:c_id, :c_name)");
            $stmt->bindParam(':c_id', $actual_c_id);
            $stmt->bindParam(':c_name', $c_name);
            $stmt->execute();
        } else {
            // Using existing customer
            $actual_c_id = filter_var($c_id_input, FILTER_VALIDATE_INT);
            if (!$actual_c_id) {
                throw new Exception('Invalid customer ID');
            }

            // Verify customer exists
            $stmt = $pdo->prepare("SELECT 1 FROM customer_tbl WHERE c_id = :c_id");
            $stmt->bindParam(':c_id', $actual_c_id);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                throw new Exception('Selected customer does not exist');
            }
        }

        // Insert credit record
        $query = "INSERT INTO credit_tbl (amount, credit_mode, c_date, c_id, gl_id, cheque_number, bank_name, cheque_date, credit_status)
                  VALUES (:amount, :credit_mode, :c_date, :c_id, :gl_id, :cheque_number, :bank_name, :cheque_date, 'active')";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':credit_mode', $credit_mode);
        $stmt->bindParam(':c_date', $c_date);
        $stmt->bindParam(':c_id', $actual_c_id);
        $stmt->bindParam(':gl_id', $gl_id);
        $stmt->bindParam(':cheque_number', $cheque_number);
        $stmt->bindParam(':bank_name', $bank_name);
        $stmt->bindParam(':cheque_date', $cheque_date);
        $stmt->execute();

        $pdo->commit();

        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => 'Credit added successfully!'
        ];
        
        echo 'Credit added successfully';

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Transaction failed: ' . $e->getMessage()
        ];
        
        echo 'Error: ' . $e->getMessage();
    }
} else {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Invalid request method'
    ];
    echo 'Invalid request';
}
?>