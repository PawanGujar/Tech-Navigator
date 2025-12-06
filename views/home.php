<?php
require __DIR__ . '/components/header.php';
?>
<?php $cfg = require __DIR__ . '/../config/config.php'; $base = $cfg['base_path'] ?? ''; $assetPrefix = $base ? $base . '/assets' : 'assets'; ?>
<link rel="stylesheet" href="<?= $assetPrefix ?>/css/main.css">
<div class="container">
  <section class="hero">
    <h2>Welcome to Tech Navigator</h2>
    <p>Choose a domain to explore languages, frameworks and tools curated for your needs.</p>
  </section>
  <section>
    <h2>Select a Domain</h2>
    <div id="category-list" class="categories-grid"></div>
  </section>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>
<script src="<?= $assetPrefix ?>/js/app.js"></script>
