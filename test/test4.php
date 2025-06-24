<?php
include('../config/dbcon.php');
session_start();

if (isset($_SESSION['userAuth']) && $_SESSION['userAuth'] != "") {

    if (isset($_POST['submit'])) {
        $amount = $_POST['amount'];
        $credit_mode = $_POST['credit_mode'];
        $c_date = $_POST['c_date'];
        $c_id = $_POST['c_id'];
        $gl_id = $_POST['gl_id'];
        $cheque_number = $_POST['cheque_number'];
        $bank_name = $_POST['bank_name'];
        $cheque_date = $_POST['cheque_date'];
        $c_name = $_POST['c_name'] ?? ''; // Ensure it's coming from the form

        try {
            // Insert into credit_tbl
            $query = "INSERT INTO credit_tbl (amount, credit_mode, c_date, c_id, gl_id, cheque_number, bank_name, cheque_date)
                      VALUES (:amount, :credit_mode, :c_date, :c_id, :gl_id, :cheque_number, :bank_name, :cheque_date)";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':credit_mode', $credit_mode);
            $stmt->bindParam(':c_date', $c_date);
            $stmt->bindParam(':c_id', $c_id);
            $stmt->bindParam(':gl_id', $gl_id);
            $stmt->bindParam(':cheque_number', $cheque_number);
            $stmt->bindParam(':bank_name', $bank_name);
            $stmt->bindParam(':cheque_date', $cheque_date);

            if ($stmt->execute()) {
                // Optional: check if customer exists
                $check = $pdo->prepare("SELECT * FROM customer_tbl WHERE c_id = :c_id");
                $check->bindParam(':c_id', $c_id);
                $check->execute();

                if ($check->rowCount() == 0) {
                    $query1 = "INSERT INTO customer_tbl (c_id, c_name) VALUES (:c_id, :c_name)";
                    $stmt2 = $pdo->prepare($query1);
                    $stmt2->bindParam(':c_id', $c_id);
                    $stmt2->bindParam(':c_name', $c_name);

                    $stmt2->execute();
                }

                $_SESSION['toastr'] = [
                    'type' => 'success',
                    'message' => 'Credit added successfully!'
                ];
                header('Location: credit.php');
                exit();
            }

        } catch (PDOException $e) {
            $_SESSION['toastr'] = [
                'type' => 'error',
                'message' => 'Error adding credit: ' . $e->getMessage()
            ];
            header('Location: credit.php');
            exit();
        }
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
