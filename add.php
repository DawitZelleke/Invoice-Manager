<?php
require_once 'database.php';
require_once 'validation.php';

$errors = [];

$data = [
    'number' => '',
    'client' => '',
    'email' => '',
    'amount' => '',
    'status' => 'draft'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $errors = validateInvoice($data);

    if (trim($data['number'] ?? '') === '') {
        $errors['number'] = 'Invoice number is required.';
    }

    if (empty($errors)) {
        $pdo = getDatabaseConnection();

        $stmt = $pdo->prepare(
            'INSERT INTO invoices (number, client, email, amount, status)
             VALUES (:number, :client, :email, :amount, :status)'
        );

        $stmt->execute([
            'number' => trim($data['number']),
            'client' => trim($data['client']),
            'email' => trim($data['email']),
            'amount' => (int)$data['amount'],
            'status' => $data['status']
        ]);

        header('Location: index.php');
        exit;
    }
}

include 'form.php';