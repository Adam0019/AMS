<?php
include('../config/dbcon.php');
session_start();

// Authentication check
if (!isset($_SESSION['userAuth']) || $_SESSION['userAuth'] == "") {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Unauthorized Access'
    ];
    header('Location: ../index.php');
    exit();
}

// Function to get next ID safely
function getNextId($pdo, $table, $id_column) {
    $stmt = $pdo->prepare("SELECT MAX(CAST({$id_column} AS UNSIGNED)) as max_id FROM {$table} WHERE {$id_column} REGEXP '^[0-9]+$'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($result['max_id'] ? intval($result['max_id']) + 1 : 1);
}

// Function to validate email
function validateEmail($email) {
    return empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL);
}

if (isset($_POST['submit'])) {
    // CSRF Token Validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Invalid CSRF token'
        ];
        header('Location: debit.php');
        exit();
    }

    // Input validation and sanitization
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    $debit_mode = trim($_POST['debit_mode']);
    $d_date = $_POST['d_date'];
    $dbt_c_id_input = $_POST['dbt_c_id'];
    $dbt_gl_id_input = $_POST['dbt_gl_id'];
    $dbt_acc_id = $_POST['dbt_acc_id'];
    
    // Sanitize customer data
    $c_name = htmlspecialchars(trim($_POST['c_name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $c_email = trim($_POST['c_email'] ?? '');
    $c_phone = trim($_POST['c_phone'] ?? '');
    $c_address = htmlspecialchars(trim($_POST['c_address'] ?? ''), ENT_QUOTES, 'UTF-8');
    $c_role = trim($_POST['c_role'] ?? '');
    $gl_name = htmlspecialchars(trim($_POST['gl_name'] ?? ''), ENT_QUOTES, 'UTF-8');

    // Validate required fields
    if (!$amount || $amount <= 0) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Invalid amount'
        ];
        header('Location: debit.php');
        exit();
    }

    if (!in_array($debit_mode, ['Demand Draft', 'Cheque', 'NEFT/RTGS'])) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Invalid debit mode'
        ];
        header('Location: debit.php');
        exit();
    }

    // Date validation
    if (!DateTime::createFromFormat('Y-m-d', $d_date)) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Invalid date format'
        ];
        header('Location: debit.php');
        exit();
    }

    // Validate email format
    if (!validateEmail($c_email)) {
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Invalid email format'
        ];
        header('Location: debit.php');
        exit();
    }

    // Handle cheque details
    $cheque_number = ($debit_mode === 'Cheque') ? trim($_POST['cheque_number'] ?? '') : '';
    $bank_name = ($debit_mode === 'Cheque') ? trim($_POST['bank_name'] ?? '') : '';
    $cheque_date = ($debit_mode === 'Cheque') ? ($_POST['cheque_date'] ?? '') : '';

    // Validate cheque details if debit mode is Cheque
    if ($debit_mode === 'Cheque') {
        if (empty($cheque_number)) {
            $_SESSION['toastr'] = [
                'type' => 'error',
                'message' => 'Cheque number is required for cheque payments'
            ];
            header('Location: debit.php');
            exit();
        }
        
        if (empty($bank_name)) {
            $_SESSION['toastr'] = [
                'type' => 'error',
                'message' => 'Bank name is required for cheque payments'
            ];
            header('Location: debit.php');
            exit();
        }
        
        if (!empty($cheque_date) && !DateTime::createFromFormat('Y-m-d', $cheque_date)) {
            $_SESSION['toastr'] = [
                'type' => 'error',
                'message' => 'Invalid cheque date format'
            ];
            header('Location: debit.php');
            exit();
        }
    }

    try {
        $pdo->beginTransaction();

        // Handle customer logic
        $actual_c_id = null;
        
        if ($dbt_c_id_input === 'other') {
            // Adding new customer
            if (empty($c_name)) {
                throw new Exception('Customer name is required for new customer');
            }

            // Generate new customer ID safely
            $actual_c_id = getNextId($pdo, 'customer_tbl', 'c_id');

            // Insert new customer
            $stmt = $pdo->prepare("INSERT INTO customer_tbl (c_id, c_name, c_email, c_phone, c_address, c_role) VALUES (:c_id, :c_name, :c_email, :c_phone, :c_address, :c_role)");
            $stmt->bindParam(':c_id', $actual_c_id);
            $stmt->bindParam(':c_name', $c_name);
            $stmt->bindParam(':c_email', $c_email);
            $stmt->bindParam(':c_phone', $c_phone);
            $stmt->bindParam(':c_address', $c_address);
            $stmt->bindParam(':c_role', $c_role);
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to create new customer');
            }
        } else {
            // Using existing customer
            $actual_c_id = filter_var($dbt_c_id_input, FILTER_VALIDATE_INT);
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

        // Handle GL account logic
        $actual_gl_id = null;

        if ($dbt_gl_id_input === 'other') {
            if (empty($gl_name)) {
                throw new Exception('GL name is required for new GL account');
            }

            // Generate new GL ID safely
            $actual_gl_id = getNextId($pdo, 'gl_tbl', 'gl_id');
            
            // Insert new GL account
            $stmt = $pdo->prepare("INSERT INTO gl_tbl (gl_id, gl_name) VALUES (:gl_id, :gl_name)");
            $stmt->bindParam(':gl_id', $actual_gl_id);
            $stmt->bindParam(':gl_name', $gl_name);
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to create new GL account');
            }
        } else {
            // Using existing GL account
            $actual_gl_id = filter_var($dbt_gl_id_input, FILTER_VALIDATE_INT);
            if (!$actual_gl_id) {
                throw new Exception('Invalid GL account ID');
            }

            // Verify GL account exists
            $stmt = $pdo->prepare("SELECT 1 FROM gl_tbl WHERE gl_id = :gl_id");
            $stmt->bindParam(':gl_id', $actual_gl_id);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                throw new Exception('Selected GL account does not exist');
            }
        }

        // Insert debit record
        $query = "INSERT INTO debit_tbl (amount, debit_mode, d_date, dbt_c_id, dbt_gl_id, cheque_number, bank_name, cheque_date, debit_status)
                  VALUES (:amount, :debit_mode, :d_date, :dbt_c_id, :dbt_gl_id, :cheque_number, :bank_name, :cheque_date, 'active')";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':debit_mode', $debit_mode);
        $stmt->bindParam(':d_date', $d_date);
        $stmt->bindParam(':dbt_c_id', $actual_c_id);
        $stmt->bindParam(':dbt_gl_id', $actual_gl_id);
        $stmt->bindParam(':cheque_number', $cheque_number);
        $stmt->bindParam(':bank_name', $bank_name);
        $stmt->bindParam(':cheque_date', $cheque_date);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to create debit record');
        }

        $pdo->commit();

        $_SESSION['toastr'] = [
            'type' => 'success',
            'message' => 'Debit added successfully!'
        ];
        
        header('Location: debit.php');
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        
        // Log error for debugging
        error_log("Debit transaction failed: " . $e->getMessage());
        
        $_SESSION['toastr'] = [
            'type' => 'error',
            'message' => 'Transaction failed. Please try again.'
        ];
        
        header('Location: debit.php');
        exit();
    }
} else {
    $_SESSION['toastr'] = [
        'type' => 'error',
        'message' => 'Invalid request method'
    ];
    header('Location: debit.php');
    exit();
}
?>