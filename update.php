<?php
require_once 'database.php';
require_once 'validation.php';

$pdo = getDatabaseConnection();

$number = $_GET['number'] ?? $_POST['number'] ?? null;

if (!$number) {
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $errors = validateInvoice($data);

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'UPDATE invoices
             SET client = :client,
                 email = :email,
                 amount = :amount,
                 status = :status
             WHERE number = :number'
        );

        $stmt->execute([
            'client' => trim($data['client']),
            'email' => trim($data['email']),
            'amount' => (int)$data['amount'],
            'status' => $data['status'],
            'number' => $number
        ]);

        header('Location: index.php');
        exit;
    }
} else {
    $stmt = $pdo->prepare('SELECT * FROM invoices WHERE number = :number');
    $stmt->execute(['number' => $number]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        header('Location: index.php');
        exit;
    }
}

include 'form.php';