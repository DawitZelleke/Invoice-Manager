<?php
session_start();
require 'data.php';

if (!isset($_SESSION['invoices'])) {
    $_SESSION['invoices'] = $invoices;
}

$number = $_GET['number'] ?? $_POST['number'] ?? null;
$invoice = null;

foreach ($_SESSION['invoices'] as $item) {
    if ($item['number'] == $number) {
        $invoice = $item;
        break;
    }
}

if (!$invoice) {
    header('Location: index.php');
    exit;
}

$errors = $_SESSION['update_errors'] ?? [];
$old = $_SESSION['update_old'] ?? $invoice;

unset($_SESSION['update_errors'], $_SESSION['update_old']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Invoice</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Update Invoice #<?= htmlspecialchars($number) ?></h1>
    <nav>
        <a href="index.php">Home</a>
    </nav>
</header>

<main>
    <form class="invoice-form" method="POST" action="process_update.php">
        <input type="hidden" name="number" value="<?= htmlspecialchars($number) ?>">

        <label>Client Name</label>
        <input type="text" name="client" value="<?= htmlspecialchars($old['client']) ?>">
        <?php if (isset($errors['client'])): ?>
            <p class="error"><?= htmlspecialchars($errors['client']) ?></p>
        <?php endif; ?>

        <label>Client Email</label>
        <input type="text" name="email" value="<?= htmlspecialchars($old['email']) ?>">
        <?php if (isset($errors['email'])): ?>
            <p class="error"><?= htmlspecialchars($errors['email']) ?></p>
        <?php endif; ?>

        <label>Invoice Amount</label>
        <input type="text" name="amount" value="<?= htmlspecialchars($old['amount']) ?>">
        <?php if (isset($errors['amount'])): ?>
            <p class="error"><?= htmlspecialchars($errors['amount']) ?></p>
        <?php endif; ?>

        <label>Invoice Status</label>
        <select name="status">
            <option value="draft" <?= $old['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="pending" <?= $old['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="paid" <?= $old['status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
        </select>
        <?php if (isset($errors['status'])): ?>
            <p class="error"><?= htmlspecialchars($errors['status']) ?></p>
        <?php endif; ?>

        <button type="submit">Update Invoice</button>
    </form>
</main>

</body>
</html>