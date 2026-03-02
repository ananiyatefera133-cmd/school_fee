<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function currentUser(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    $pdo = getDatabaseConnection();
    $statement = $pdo->prepare('SELECT id, full_name, email, created_at FROM users WHERE id = :id');
    $statement->execute(['id' => $_SESSION['user_id']]);

    $user = $statement->fetch();

    return $user ?: null;
}

function requireAuth(): void
{
    if (currentUser() !== null) {
        return;
    }

    header('Location: login.php');
    exit;
}

function seedFeesForUser(int $userId): void
{
    $pdo = getDatabaseConnection();
    $check = $pdo->prepare('SELECT COUNT(*) FROM fees WHERE user_id = :user_id');
    $check->execute(['user_id' => $userId]);

    if ((int) $check->fetchColumn() > 0) {
        return;
    }

    $samples = [
        ['month' => 'January', 'amount' => 120.00, 'due_date' => '2026-01-10', 'status' => 'Paid'],
        ['month' => 'February', 'amount' => 120.00, 'due_date' => '2026-02-10', 'status' => 'Pending'],
        ['month' => 'March', 'amount' => 120.00, 'due_date' => '2026-03-10', 'status' => 'Pending'],
    ];

    $insert = $pdo->prepare(
        'INSERT INTO fees (user_id, month, amount, due_date, status)
         VALUES (:user_id, :month, :amount, :due_date, :status)'
    );

    foreach ($samples as $sample) {
        $insert->execute([
            'user_id' => $userId,
            'month' => $sample['month'],
            'amount' => $sample['amount'],
            'due_date' => $sample['due_date'],
            'status' => $sample['status'],
        ]);
    }
}
