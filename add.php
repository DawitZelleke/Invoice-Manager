<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4">Add Invoice</h1>

        <form action="index.php" method="POST" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label class="form-label">Client Name</label>
                <input type="text" name="client" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Client Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Invoice Amount</label>
                <input type="number" name="amount" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Invoice Status</label>
                <select name="status" class="form-select" required>
                    <option value="draft">Draft</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Add Invoice</button>
            <a href="index.php" class="btn btn-secondary mt-2">Cancel</a>
        </form>
    </div>
</body>
</html>