<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['number'])) {
    $pdo = getDatabaseConnection();

    $stmt = $pdo->prepare('DELETE FROM invoices WHERE number = :number');
    $stmt->execute([
        'number' => $_POST['number']
    ]);
}
$pdf = __DIR__ . '/documents/' . $_POST['number'] . '.pdf';

if (file_exists($pdf)) {
    unlink($pdf);
}

header('Location: index.php');
exit;