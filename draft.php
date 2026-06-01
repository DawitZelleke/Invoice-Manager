<?php
require 'data.php';

$pageTitle = 'Draft Invoices';

$filteredInvoices = array_filter($invoices, function ($invoice) {
  return $invoice['status'] === 'draft';
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <h1><?= $pageTitle ?></h1>

  <nav>
    <a href="index.php">All</a>
    <a href="draft.php">Draft</a>
    <a href="pending.php">Pending</a>
    <a href="paid.php">Paid</a>
  </nav>
</header>

<main>
  <section class="invoice-list">
    <?php foreach ($filteredInvoices as $invoice): ?>
      <article class="invoice-card">
        <h2>Invoice #<?= $invoice['number'] ?></h2>
        <p><strong>Client:</strong> <?= $invoice['client'] ?></p>
        <p><strong>Email:</strong> <?= $invoice['email'] ?></p>
        <p><strong>Amount:</strong> <span class="amount">$<?= number_format($invoice['amount']) ?></span></p>
        <p><span class="status <?= $invoice['status'] ?>"><?= $invoice['status'] ?></span></p>
      </article>
    <?php endforeach; ?>
  </section>
</main>

</body>
</html>