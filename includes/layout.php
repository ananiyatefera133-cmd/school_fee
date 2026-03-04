<?php

declare(strict_types=1);

require_once __DIR__ . '/../auth.php';
$user = currentUser();

// Calculate base path for assets and links
$currentDir = dirname($_SERVER['PHP_SELF']);
$base = (str_contains($currentDir, '/index')) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($title ?? 'School Fee Management')?>
    </title>
    <link rel="stylesheet" href="<?= $base?>assets/css/style.css">
</head>

<body>
    <header class="navbar">
        <div class="nav-inner">
            <a class="brand" href="<?= $base?>index/index.php">SchoolFee Manager</a>
            <button id="nav-toggle" aria-label="toggle navigation">☰</button>
            <ul class="nav-links" id="nav-links">
                <li><a href="<?= $base?>index/index.php">Home</a></li>
                <li><a href="<?= $base?>dashboard.php">Dashboard</a></li>
                <?php if ($user): ?>
                <li><a href="<?= $base?>logout.php">Logout</a></li>
                <?php
else: ?>
                <li><a href="<?= $base?>login.php">Login</a></li>
                <li><a href="<?= $base?>register.php">Register</a></li>
                <?php
endif; ?>
            </ul>
        </div>
    </header>
