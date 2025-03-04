<?php
require_once('../config/dbcon.php');

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    try {
        $query = "SELECT * FROM user_tbl WHERE u_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "<p><strong>ID:</strong> " . htmlspecialchars($user['u_id']) . "</p>";
            echo "<p><strong>Name:</strong> " . htmlspecialchars($user['u_name']) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($user['u_email']) . "</p>";
            echo "<p><strong>Phone:</strong> " . htmlspecialchars($user['u_phone']) . "</p>";
            echo "<p><strong>Username:</strong> " . htmlspecialchars($user['username']) . "</p>";
            echo "<p><strong>Password:</strong> " . htmlspecialchars($user['password']) . "</p>";
            echo "<p><strong>Role:</strong> " . htmlspecialchars($user['role']) . "</p>";
            echo "<p><strong>Status:</strong> " . ($user['u_status'] == 'active' ? 'Inactive' : 'Active') . "</p>";
        } else {
            echo "<p class='text-danger'>user not found.</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='text-danger'>Error fetching user details: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='text-danger'>Invalid request.</p>";
}
?>
