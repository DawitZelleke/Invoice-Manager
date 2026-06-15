<?php
session_start();
require 'data.php';

if (!isset($_SESSION['invoices'])) {
    $_SESSION['invoices'] = $invoices;
}

$invoices = $_SESSION['invoices'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_invoice'])) {
    $invoiceNumber = $_POST['invoice_number'];

    $_SESSION['invoices'] = array_filter($_SESSION['invoices'], function ($invoice) use ($invoiceNumber) {
        return $invoice['number'] != $invoiceNumber;
    });

    header('Location: index.php');
    exit;
}

$status = $_GET['status'] ?? 'all';

$filteredInvoices = $invoices;

if ($status !== 'all') {
    $filteredInvoices = array_filter($invoices, function ($invoice) use ($status) {
        return $invoice['status'] === $status;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Invoice Manager</h1>

    <nav>
        <a href="index.php">All</a>
        <a href="index.php?status=draft">Draft</a>
        <a href="index.php?status=pending">Pending</a>
        <a href="index.php?status=paid">Paid</a>
        <a href="add.php" class="button">Add Invoice</a>
    </nav>
</header>

<main>
    <h2><?= ucfirst($status) ?> Invoices</h2>

    <div class="invoice-list">
        <?php if (empty($filteredInvoices)): ?>
            <p>No invoices found.</p>
        <?php else: ?>
            <?php foreach ($filteredInvoices as $invoice): ?>
                <div class="invoice-card">
                    <h3>Invoice #<?= htmlspecialchars($invoice['number']) ?></h3>
                    <p><strong>Client:</strong> <?= htmlspecialchars($invoice['client']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($invoice['email']) ?></p>
                    <p><strong>Amount:</strong> $<?= htmlspecialchars($invoice['amount']) ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($invoice['status']) ?></p>

                    <div class="actions">
                        <a class="edit-link" href="update.php?number=<?= urlencode($invoice['number']) ?>">Edit</a>

                        <form method="POST" action="index.php" onsubmit="return confirm('Delete this invoice?');">
                            <input type="hidden" name="invoice_number" value="<?= htmlspecialchars($invoice['number']) ?>">
                            <button type="submit" name="delete_invoice" class="delete-button">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

</body>
</html>