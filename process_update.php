<?php
session_start();
require 'data.php';

if (!isset($_SESSION['invoices'])) {
    $_SESSION['invoices'] = $invoices;
}

$number = $_POST['number'] ?? null;
$client = trim($_POST['client'] ?? '');
$email = trim($_POST['email'] ?? '');
$amount = trim($_POST['amount'] ?? '');
$status = trim($_POST['status'] ?? '');

$errors = [];

if ($client === '' || !preg_match('/^[a-zA-Z ]+$/', $client) || strlen($client) > 255) {
    $errors['client'] = 'Client name must contain only letters and spaces and be 255 characters or less.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Client email must be a valid email address.';
}

if (!filter_var($amount, FILTER_VALIDATE_INT)) {
    $errors['amount'] = 'Invoice amount must be an integer.';
}

if (!in_array($status, ['draft', 'pending', 'paid'])) {
    $errors['status'] = 'Invoice status must be draft, pending, or paid.';
}

if (!empty($errors)) {
    $_SESSION['update_errors'] = $errors;
    $_SESSION['update_old'] = [
        'number' => $number,
        'client' => $client,
        'email' => $email,
        'amount' => $amount,
        'status' => $status
    ];

    header('Location: update.php?number=' . urlencode($number));
    exit;
}

foreach ($_SESSION['invoices'] as &$invoice) {
    if ($invoice['number'] == $number) {
        $invoice['client'] = $client;
        $invoice['email'] = $email;
        $invoice['amount'] = (int)$amount;
        $invoice['status'] = $status;
        break;
    }
}

header('Location: index.php');
exit;