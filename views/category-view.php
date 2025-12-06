<?php
require __DIR__ . '/components/header.php';
$cfg = require __DIR__ . '/../config/config.php';
$base = $cfg['base_path'] ?? '';
$assetPrefix = $base ? $base . '/assets' : 'assets';
$apiPrefix = $base ? $base . '/backend/api' : 'backend/api';
$cid = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>
<link rel="stylesheet" href="<?= $assetPrefix ?>/css/main.css">
<div class="container">
  <h2>Category</h2>
  <?php include __DIR__ . '/components/filter-bar.php'; ?>
  <div id="items" class="cards">
    <p class="muted">Loading items for this category…</p>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>
<script src="<?= $assetPrefix ?>/js/app.js"></script>
<script src="<?= $assetPrefix ?>/js/category.js"></script>
