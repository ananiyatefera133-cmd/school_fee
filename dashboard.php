<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
requireAuth();

$user = currentUser();
$pdo = getDatabaseConnection();
$statement = $pdo->prepare('SELECT month, amount, due_date, status FROM fees WHERE user_id = :user_id ORDER BY id ASC');
$statement->execute(['user_id' => $user['id']]);
$fees = $statement->fetchAll();

$total = array_sum(array_column($fees, 'amount'));
$paid = array_sum(array_map(static fn($fee) => $fee['status'] === 'Paid' ? (float) $fee['amount'] : 0, $fees));
$pending = $total - $paid;

$title = 'Dashboard';
require_once __DIR__ . '/includes/layout.php';
?>
<main class="container">
    <h1>Welcome, <?= htmlspecialchars($user['full_name']) ?></h1>
    <div class="grid grid-2">
        <div class="card">
            <h3>Total Fees</h3>
            <p>$<?= number_format($total, 2) ?></p>
        </div>
        <div class="card">
            <h3>Pending Amount</h3>
            <p>$<?= number_format($pending, 2) ?></p>
        </div>
    </div>

    <div class="card" style="margin-top:1rem;">
        <h2>Fee Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($fees as $fee): ?>
                <tr>
                    <td><?= htmlspecialchars($fee['month']) ?></td>
                    <td>$<?= number_format((float) $fee['amount'], 2) ?></td>
                    <td><?= htmlspecialchars($fee['due_date']) ?></td>
                    <td>
                        <span class="badge <?= $fee['status'] === 'Paid' ? 'badge-paid' : 'badge-pending' ?>">
                            <?= htmlspecialchars($fee['status']) ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
<script src="assets/js/app.js"></script>
</body>
</html>
