<?php
session_start();
require "data.php";

function getInvoiceNumber($length = 5) {
    $letters = range('A', 'Z');
    $number = [];

    for ($i = 0; $i < $length; $i++) {
        array_push($number, $letters[rand(0, count($letters) - 1)]);
    }

    return implode($number);
}

if (!isset($_SESSION["invoices"])) {
    $_SESSION["invoices"] = $invoices;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newInvoice = [
        "number" => getInvoiceNumber(),
        "client" => $_POST["client"],
        "email" => $_POST["email"],
        "amount" => $_POST["amount"],
        "status" => $_POST["status"]
    ];

    $_SESSION["invoices"][] = $newInvoice;

    header("Location: index.php");
    exit;
}

$status = $_GET["status"] ?? "all";

if ($status === "all") {
    $filteredInvoices = $_SESSION["invoices"];
} else {
    $filteredInvoices = array_filter($_SESSION["invoices"], function ($invoice) use ($status) {
        return $invoice["status"] === $status;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Invoice Manager</h1>
            <a href="add.php" class="btn btn-primary">Add Invoice</a>
        </div>

        <nav class="mb-4">
            <a href="index.php?status=all" class="btn btn-outline-dark">All</a>
            <a href="index.php?status=draft" class="btn btn-outline-secondary">Draft</a>
            <a href="index.php?status=pending" class="btn btn-outline-warning">Pending</a>
            <a href="index.php?status=paid" class="btn btn-outline-success">Paid</a>
        </nav>

        <div class="row">
            <?php if (empty($filteredInvoices)): ?>
                <p>No invoices found.</p>
            <?php endif; ?>

            <?php foreach ($filteredInvoices as $invoice): ?>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                Invoice #<?= htmlspecialchars($invoice["number"]) ?>
                            </h5>

                            <p><strong>Client:</strong> <?= htmlspecialchars($invoice["client"]) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($invoice["email"]) ?></p>
                            <p><strong>Amount:</strong> $<?= htmlspecialchars($invoice["amount"]) ?></p>
                            <p><strong>Status:</strong> <?= htmlspecialchars($invoice["status"]) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>