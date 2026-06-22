<?php

function validateInvoice(array $data): array
{
    $errors = [];

    $client = trim($data['client'] ?? '');
    $email = trim($data['email'] ?? '');
    $amount = trim($data['amount'] ?? '');
    $status = trim($data['status'] ?? '');

    if ($client === '' || !preg_match('/^[a-zA-Z ]+$/', $client) || strlen($client) > 255) {
        $errors['client'] = 'Client name must only contain letters and spaces and cannot be more than 255 characters.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if ($amount === '' || filter_var($amount, FILTER_VALIDATE_INT) === false) {
        $errors['amount'] = 'Invoice amount must be an integer.';
    }

    if (!in_array($status, ['draft', 'pending', 'paid'], true)) {
        $errors['status'] = 'Invoice status must be draft, pending, or paid.';
    }

    return $errors;
}