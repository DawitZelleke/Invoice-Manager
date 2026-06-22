<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['number'])) {
    $pdo = getDatabaseConnection();

    $stmt = $pdo->prepare('DELETE FROM invoices WHERE number = :number');
    $stmt->execute([
        'number' => $_POST['number']
    ]);
}

header('Location: index.php');
exit;