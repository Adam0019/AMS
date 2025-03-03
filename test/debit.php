
<?php
require_once('../config/dbcon.php');

if (isset($_GET['id'])) {
    $customerId = $_GET['id'];
    try {
        $query = "SELECT * FROM customer_tbl WHERE c_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $customerId, PDO::PARAM_INT);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            echo "<p><strong>ID:</strong> " . htmlspecialchars($customer['c_id']) . "</p>";
            echo "<p><strong>Name:</strong> " . htmlspecialchars($customer['c_name']) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($customer['c_email']) . "</p>";
            echo "<p><strong>Phone:</strong> " . htmlspecialchars($customer['c_phone']) . "</p>";
            echo "<p><strong>Address:</strong> " . htmlspecialchars($customer['c_address']) . "</p>";
            echo "<p><strong>Role:</strong> " . htmlspecialchars($customer['c_role']) . "</p>";
            echo "<p><strong>Status:</strong> " . ($customer['c_status'] == 'active' ? 'Active' : 'Deactivate') . "</p>";
        } else {
            echo "<p class='text-danger'>Customer not found.</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='text-danger'>Error fetching customer details: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='text-danger'>Invalid request.</p>";
}
?>
