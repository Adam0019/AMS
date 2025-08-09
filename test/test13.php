<?php
include('../config/dbcon.php'); // Make sure this defines $pdo as a PDO instance
session_start();

// date_default_timezone_set('Asia/Kolkata'); // Set proper timezone

if (!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "") {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Unauthorized Access'
    ];
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Token Validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Invalid CSRF token'
        ];
        header('Location: debit.php');
        exit();
    }

    // Input sanitization
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    $debit_mode = trim($_POST['debit_mode'] );
    $d_date = $_POST['d_date'] ;
    $dbt_c_id_input = $_POST['dbt_c_id'] ;
    $dbt_gl_id_input = $_POST['dbt_gl_id'] ;
    $dbt_acc_id = $_POST['dbt_acc_id'];
    $c_name = trim($_POST['c_name'] ?? '');
    $c_email = trim($_POST['c_email'] ?? '');
    $c_phone = trim($_POST['c_phone'] ?? '');
    $c_address = trim($_POST['c_address'] ?? '');
    $c_role = trim($_POST['c_role'] ?? '');
    $gl_name = trim($_POST['gl_name'] ?? '');
    $acc_num = $_POST['acc_num'];

    // Validate required fields
    if (!$amount || $amount <= 0) {
        $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Invalid amount'];
        header('Location: debit.php');
        exit();
    }

    if (!in_array($debit_mode, ['Demand Draft', 'Cheque', 'NEFT/RTGS'])) {
        $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Invalid debit mode'];
        header('Location: debit.php');
        exit();
    }

    if (!DateTime::createFromFormat('Y-m-d', $d_date)) {
        $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Invalid date format'];
        header('Location: debit.php');
        exit();
    }

    // Cheque-related details
    $cheque_number = ($debit_mode === 'Cheque') ? trim($_POST['cheque_number'] ?? '') : '';
    $bank_name = ($debit_mode === 'Cheque') ? trim($_POST['bank_name'] ?? '') : '';
    $cheque_date = ($debit_mode === 'Cheque') ? ($_POST['cheque_date'] ?? '') : '';

    if ($debit_mode === 'Cheque') {
        if (empty($cheque_number) || empty($bank_name)) {
            $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Cheque details are required for cheque payments'];
            header('Location: debit.php');
            exit();
        }
        if (!empty($cheque_date) && !DateTime::createFromFormat('Y-m-d', $cheque_date)) {
            $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Invalid cheque date format'];
            header('Location: debit.php');
            exit();
        }
    }

    try {
        $pdo->beginTransaction();

        // Handle customer
        if ($dbt_c_id_input === 'other') {
            if (empty($c_name)) throw new Exception('Customer name is required');

            $stmt = $pdo->prepare("SELECT MAX(CAST(c_id AS UNSIGNED)) as max_id FROM customer_tbl WHERE c_id REGEXP '^[0-9]+$'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $actual_c_id = ($result['max_id'] ?? 0) + 1;

            $stmt = $pdo->prepare("INSERT INTO customer_tbl (c_id, c_name, c_email, c_phone, c_address, c_role)
                                   VALUES (:c_id, :c_name, :c_email, :c_phone, :c_address, :c_role)");
            $stmt->execute([
                ':c_id' => $actual_c_id,
                ':c_name' => $c_name,
                ':c_email' => $c_email,
                ':c_phone' => $c_phone,
                ':c_address' => $c_address,
                ':c_role' => $c_role
            ]);
        } else {
            $actual_c_id = filter_var($dbt_c_id_input, FILTER_VALIDATE_INT);
            if (!$actual_c_id) throw new Exception('Invalid customer ID');

            $stmt = $pdo->prepare("SELECT 1 FROM customer_tbl WHERE c_id = :c_id");
            $stmt->execute([':c_id' => $actual_c_id]);
            if ($stmt->rowCount() === 0) throw new Exception('Selected customer does not exist');
        }

        // Handle GL account
        if ($dbt_gl_id_input === 'other') {
            if (empty($gl_name)) throw new Exception('GL name is required');

            $stmt = $pdo->prepare("SELECT MAX(CAST(gl_id AS UNSIGNED)) as max_id FROM gl_tbl WHERE gl_id REGEXP '^[0-9]+$'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $actual_gl_id = ($result['max_id'] ?? 0) + 1;

            $stmt = $pdo->prepare("INSERT INTO gl_tbl (gl_id, gl_name) VALUES (:gl_id, :gl_name)");
            $stmt->execute([':gl_id' => $actual_gl_id, ':gl_name' => $gl_name]);
        } else {
            $actual_gl_id = filter_var($dbt_gl_id_input, FILTER_VALIDATE_INT);
            if (!$actual_gl_id) throw new Exception('Invalid GL ID');

            $stmt = $pdo->prepare("SELECT 1 FROM gl_tbl WHERE gl_id = :gl_id");
            $stmt->execute([':gl_id' => $actual_gl_id]);
            if ($stmt->rowCount() === 0) throw new Exception('Selected GL account does not exist');
        }

        // Insert debit record
        $stmt = $pdo->prepare("INSERT INTO debit_tbl (amount, debit_mode, d_date, dbt_c_id, dbt_gl_id, dbt_acc_id, cheque_number, bank_name, cheque_date, debit_status)
                               VALUES (:amount, :debit_mode, :d_date, :dbt_c_id, :dbt_gl_id, :dbt_acc_id, :cheque_number, :bank_name, :cheque_date, 'active')");
        $stmt->execute([
            ':amount' => $amount,
            ':debit_mode' => $debit_mode,
            ':d_date' => $d_date,
            ':dbt_c_id' => $actual_c_id,
            ':dbt_gl_id' => $actual_gl_id,
            ':dbt_acc_id'=>$dbt_acc_id,
            ':cheque_number' => $cheque_number,
            ':bank_name' => $bank_name,
            ':cheque_date' => $cheque_date
        ]);

        $pdo->commit();
        $_SESSION['toastr'] = ['type' => 'success', 'message' => 'Debit added successfully!'];
        header('Location: debit.php');
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        error_log('Error storing debit: ' . $e->getMessage());
        $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Transaction failed. Please try again.'];
        header('Location: debit.php');
        exit();
    }
} else {
    $_SESSION['toastr'] = ['type' => 'error', 'message' => 'Invalid request method'];
    header('Location: debit.php');
    exit();
}
?>
