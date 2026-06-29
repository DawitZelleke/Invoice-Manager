<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="container">
    <h1><?= empty($data['number']) ? 'Add Invoice' : 'Update Invoice' ?></h1>

    <form method="post" enctype="multipart/form-data" class="invoice-form">
        <label>
            Invoice Number
            <input 
                type="text" 
                name="number" 
                value="<?= htmlspecialchars($data['number'] ?? '') ?>"
                <?= isset($_GET['number']) ? 'readonly' : '' ?>
            >
            <span><?= $errors['number'] ?? '' ?></span>
        </label>

        <label>
            Client Name
            <input type="text" name="client" value="<?= htmlspecialchars($data['client'] ?? '') ?>">
            <span><?= $errors['client'] ?? '' ?></span>
        </label>

        <label>
            Client Email
            <input type="email" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>">
            <span><?= $errors['email'] ?? '' ?></span>
        </label>

        <label>
            Invoice Amount
            <input type="number" name="amount" value="<?= htmlspecialchars($data['amount'] ?? '') ?>">
            <span><?= $errors['amount'] ?? '' ?></span>
        </label>

        <label>
            Invoice Status
            <select name="status">
                <?php foreach (['draft', 'pending', 'paid'] as $status): ?>
                    <option value="<?= $status ?>" <?= ($data['status'] ?? '') === $status ? 'selected' : '' ?>>
                        <?= ucfirst($status) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span><?= $errors['status'] ?? '' ?></span>
        </label>
        <label>
            Invoice PDF
            <input
                type="file"
                name="invoice_pdf"
                accept="application/pdf">
            <span><?= $errors['invoice_pdf'] ?? '' ?></span>
        </label>

        <button type="submit">Save Invoice</button>
        <a href="index.php" class="cancel">Cancel</a>
    </form>
</main>
</body>
</html>