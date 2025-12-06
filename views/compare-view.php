<?php
require __DIR__ . '/components/header.php';
$cfg = require __DIR__ . '/../config/config.php';
$base = $cfg['base_path'] ?? '';
$assetPrefix = $base ? $base . '/assets' : 'assets';
$apiPrefix = $base ? $base . '/backend/api' : 'backend/api';
$ids = isset($_GET['ids']) ? $_GET['ids'] : '';
$items = [];
if($ids){
  $u = $apiPrefix . '/compareItems.php?ids=' . urlencode($ids);
  $json = @file_get_contents($u);
  $data = $json ? json_decode($json, true) : null;
  if($data && $data['ok']) $items = $data['items'];
}
?>
<div class="container">
  <h2>Compare</h2>
  <div id="compare-root">
    <p class="muted">Loading compare view…</p>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>
<link rel="stylesheet" href="<?= $assetPrefix ?>/css/main.css">
<script src="<?= $assetPrefix ?>/js/app.js"></script>
<script src="<?= $assetPrefix ?>/js/compare-ui.js"></script>
