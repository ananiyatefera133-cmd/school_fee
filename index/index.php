<?php

declare(strict_types=1);

require_once __DIR__ . '/../auth.php';

$title = 'Home';
require_once __DIR__ . '/../includes/layout.php';
?>
<main class="container">
    <div class="welcome-box">
        <h1>Welcome to School Fee System</h1>
        <p>Manage your school fees easily and securely.</p>

        <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="actions">
            <a href="../login.php" class="btn">Login</a>
            <a href="../register.php" class="btn btn-outline">Register</a>
        </div>
        <?php
else: ?>
        <div class="actions">
            <a href="../dashboard.php" class="btn">Go to Dashboard</a>
        </div>
        <?php
endif; ?>
    </div>
</main>
</body>

</html>
