<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($fullName === '' || $email === '' || $password === '') {
        $errors[] = 'All fields are required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($errors === []) {
        try {
            $pdo = getDatabaseConnection();
            $statement = $pdo->prepare(
                'INSERT INTO users (full_name, email, password_hash, created_at)
                 VALUES (:full_name, :email, :password_hash, :created_at)'
            );
            $statement->execute([
                'full_name' => $fullName,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'created_at' => date('c'),
            ]);

            $userId = (int) $pdo->lastInsertId();
            seedFeesForUser($userId);
            $_SESSION['user_id'] = $userId;
            header('Location: dashboard.php');
            exit;
        } catch (PDOException $exception) {
            $errors[] = 'Email already exists. Please login.';
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
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post" action="register.php">
            <label for="full_name">Full Name</label>
            <input id="full_name" name="full_name" type="text" required>

            <label for="email">Email</label>
            <input id="email" name="email" type="email" required>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>

            <button type="submit">Register</button>
        </form>
    </div>
</main>
<script src="assets/js/app.js"></script>
</body>
</html>
