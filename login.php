<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Email and password are required.';
    }

    if ($errors === []) {
        $pdo = getDatabaseConnection();
        $statement = $pdo->prepare('SELECT id, password_hash FROM users WHERE email = ?');
        $statement->execute([$email]);
        $user = $statement->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = (int)$user['id'];
            header('Location: dashboard.php');
            exit;
        }

        $errors[] = 'Invalid credentials.';
    }
}

$title = 'Login';
require_once __DIR__ . '/includes/layout.php';
?>
<main class="container">
    <div class="form-wrap">
        <h1>Login</h1>
        <?php foreach ($errors as $error): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error)?>
        </div>
        <?php
endforeach; ?>

        <form method="post" action="login.php">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</main>
<script src="assets/js/app.js"></script>
</body>

</html>
