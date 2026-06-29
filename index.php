<?php
require_once 'database.php';

$pdo = getDatabaseConnection();

$status = $_GET['status'] ?? 'all';
$search = trim($_GET['search'] ?? '');

if ($status === 'all') {

    if ($search === '') {

        $stmt = $pdo->query("
            SELECT *
            FROM invoices
            ORDER BY number
        ");

    } else {

        $stmt = $pdo->prepare("
            SELECT *
            FROM invoices
            WHERE number LIKE :search
               OR client LIKE :search
               OR email LIKE :search
            ORDER BY number
        ");

        $stmt->execute([
            'search' => "%$search%"
        ]);

    }

} else {

    if ($search === '') {

        $stmt = $pdo->prepare("
            SELECT *
            FROM invoices
            WHERE status=:status
            ORDER BY number
        ");

        $stmt->execute([
            'status'=>$status
        ]);

    } else {

        $stmt = $pdo->prepare("
            SELECT *
            FROM invoices
            WHERE status=:status
            AND (
                number LIKE :search
                OR client LIKE :search
                OR email LIKE :search
            )
            ORDER BY number
        ");

        $stmt->execute([
            'status'=>$status,
            'search'=>"%$search%"
        ]);

    }

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

    <form method="get">
        <input
            type="text"
            name="search"
            placeholder="Search invoices..."
            value="<?= htmlspecialchars($search) ?>">

        <input
            type="hidden"
            name="status"
            value="<?= htmlspecialchars($status) ?>">

        <button type="submit">
            Search
        </button>
    </form>

    <section class="invoice-grid">
        <?php foreach ($invoices as $invoice): ?>
            <article class="invoice-card">
                <h2>Invoice #<?= htmlspecialchars($invoice['number']) ?></h2>
                <p><strong>Client:</strong> <?= htmlspecialchars($invoice['client']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($invoice['email']) ?></p>
                <p><strong>Amount:</strong> $<?= htmlspecialchars($invoice['amount']) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($invoice['status']) ?></p>
                <?php
                $pdf = 'documents/' . $invoice['number'] . '.pdf';

                if (file_exists($pdf)):
                ?>
                <a href="<?= $pdf ?>" target="_blank">
                    View PDF
                </a>
                <?php endif; ?>
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