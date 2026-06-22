<?php
require_once 'database.php';

$pdo = getDatabaseConnection();

$status = $_GET['status'] ?? 'all';

if (in_array($status, ['draft', 'pending', 'paid'], true)) {
    $stmt = $pdo->prepare('SELECT * FROM invoices WHERE status = :status ORDER BY number');
    $stmt->execute(['status' => $status]);
} else {
    $stmt = $pdo->query('SELECT * FROM invoices ORDER BY number');
}

$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="container">
    <h1>Invoice Manager</h1>

    <nav>
        <a href="index.php">All</a>
        <a href="index.php?status=draft">Draft</a>
        <a href="index.php?status=pending">Pending</a>
        <a href="index.php?status=paid">Paid</a>
        <a href="add.php">Add Invoice</a>
    </nav>

    <section class="invoice-grid">
        <?php foreach ($invoices as $invoice): ?>
            <article class="invoice-card">
                <h2>Invoice #<?= htmlspecialchars($invoice['number']) ?></h2>
                <p><strong>Client:</strong> <?= htmlspecialchars($invoice['client']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($invoice['email']) ?></p>
                <p><strong>Amount:</strong> $<?= htmlspecialchars($invoice['amount']) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($invoice['status']) ?></p>

                <div class="actions">
                    <a href="update.php?number=<?= urlencode($invoice['number']) ?>">Edit</a>

                    <form action="delete.php" method="post">
                        <input type="hidden" name="number" value="<?= htmlspecialchars($invoice['number']) ?>">
                        <button type="submit">Delete</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</main>
</body>
</html>