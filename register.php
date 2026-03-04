<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($fullName === '' || $email === '' || $password === '') {
        $errors[] = 'All fields are required.';
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($errors === []) {
        try {
            $pdo = getDatabaseConnection();
            $pdo->beginTransaction();

            // Table 1: users
            $stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)');
            $stmt->execute([$fullName, $email, password_hash($password, PASSWORD_DEFAULT)]);
            $userId = (int)$pdo->lastInsertId();

            // Table 2: user_profiles
            $stmt = $pdo->prepare('INSERT INTO user_profiles (user_id) VALUES (?)');
            $stmt->execute([$userId]);

            // Table 3: fees (example seed)
            $stmt = $pdo->prepare('INSERT INTO fees (user_id, month, amount, due_date, status) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$userId, date('F'), 1000.00, date('Y-m-d', strtotime('+30 days')), 'Unpaid']);

            $pdo->commit();
            $_SESSION['user_id'] = $userId;
            header('Location: dashboard.php');
            exit;
        }
        catch (PDOException $e) {
            $pdo->rollBack();
            $errors[] = 'Email already exists or error occurred.';
        }
    }
}

$title = 'Register';
require_once __DIR__ . '/includes/layout.php';
?>
<main class="container">
    <div class="form-wrap">
        <h1>Create Account</h1>
        <?php foreach ($errors as $error): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error)?>
        </div>
        <?php
endforeach; ?>

        <form method="post" action="register.php">
            <label for="full_name">Full Name</label>
            <input id="full_name" name="full_name" type="text" required>

            <label for="email">Email</label>
            <input id="email" name="email" type="email" required>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</main>
<script src="assets/js/app.js"></script>
</body>

</html>
