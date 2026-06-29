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
    if (!empty($_FILES['invoice_pdf']['name'])) {

        if ($_FILES['invoice_pdf']['type'] !== 'application/pdf') {
            $errors['invoice_pdf'] = 'Only PDF files are allowed.';
        }

    }

    if (trim($data['number'] ?? '') === '') {
        $errors['number'] = 'Invoice number is required.';
    }

    if (empty($errors)) {
        $pdo = getDatabaseConnection();

        $stmt = $pdo->prepare(
            'INSERT INTO invoices (number, client, email, amount, status_id)
            VALUES (:number, :client, :email, :amount, :status_id)'
        );

        $stmt->execute([
            'number' => trim($data['number']),
            'client' => trim($data['client']),
            'email' => trim($data['email']),
            'amount' => (int)$data['amount'],
            'status_id' => 1
        ]);
        $documentsDirectory = __DIR__ . '/documents';

        if (!is_dir($documentsDirectory)) {
                mkdir($documentsDirectory);
            }

        if (!empty($_FILES['invoice_pdf']['name'])) {

            move_uploaded_file(
                $_FILES['invoice_pdf']['tmp_name'],
                $documentsDirectory . '/' . trim($data['number']) . '.pdf'
            );

        }

        header('Location: index.php');
        exit;
    }
}

include 'form.php';