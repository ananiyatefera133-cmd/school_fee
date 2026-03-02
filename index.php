<?php

declare(strict_types=1);
$title = 'Home - School Fee Management';
require_once __DIR__ . '/includes/layout.php';
?>
<main class="container">
    <section class="hero">
        <div>
            <h1>School Fee Management Website</h1>
            <p>Track fee records, monitor pending payments, and manage student billing in one place.</p>
            <a class="button" href="register.php">Get Started</a>
        </div>
    </section>

    <section class="grid grid-2" style="margin-top: 1.5rem;">
        <article class="card">
            <h2>Campus Image</h2>
            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1000&q=80" alt="School campus">
        </article>
        <article class="card">
            <h2>School Intro Video</h2>
            <video controls>
                <source src="https://interactive-examples.mdn.mozilla.net/media/cc0-videos/flower.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </article>
    </section>
</main>
<footer>© <?= date('Y') ?> SchoolFee Manager</footer>
<script src="assets/js/app.js"></script>
</body>
</html>
