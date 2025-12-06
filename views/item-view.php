<?php
require __DIR__ . '/components/header.php';
$cfg = require __DIR__ . '/../config/config.php';
$base = $cfg['base_path'] ?? '';
$assetPrefix = $base ? $base . '/assets' : 'assets';
$apiPrefix = $base ? $base . '/backend/api' : 'backend/api';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$details = null;
if($id){
  $u = $apiPrefix . '/getItemDetails.php?id=' . $id;
  $json = @file_get_contents($u);
  $details = $json ? json_decode($json, true) : null;
}
?>
<div class="container">
  <h2>Item Details</h2>
  <div id="item-root">
    <p class="muted">Loading item details…</p>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>
<link rel="stylesheet" href="<?= $assetPrefix ?>/css/main.css">
<script src="<?= $assetPrefix ?>/js/app.js"></script>
<script src="<?= $assetPrefix ?>/js/item.js"></script>
