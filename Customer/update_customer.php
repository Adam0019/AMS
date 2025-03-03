<?php
require_once('../config/dbcon.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $c_id = $_POST['c_id'];
    $c_name = $_POST['c_name'];
    $c_email = $_POST['c_email'];
    $c_phone = $_POST['c_phone'];
    $c_role = $_POST['c_role'];

    try {
        $query = "UPDATE customer_tbl SET c_name = :c_name, c_email = :c_email, c_phone = :c_phone, c_role =:c_role WHERE c_id = :c_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':c_id', $c_id, PDO::PARAM_INT);
        $stmt->bindParam(':c_name', $c_name, PDO::PARAM_STR);
        $stmt->bindParam(':c_email', $c_email, PDO::PARAM_STR);
        $stmt->bindParam(':c_phone', $c_phone, PDO::PARAM_STR);
        $stmt->bindParam(':c_role', $c_role, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "Customer updated successfully!";
        } else {
            echo "Error updating customer.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
