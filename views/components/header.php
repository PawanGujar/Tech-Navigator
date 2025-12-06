<?php
$cfg = require __DIR__ . '/../../config/config.php';
$basePath = $cfg['base_path'] ?? '';
$assetPrefix = $basePath ? $basePath . '/assets' : 'assets';
$homeUrl = $basePath ? $basePath . '/' : '/';
if (session_status() === PHP_SESSION_NONE) session_start();
$isAdmin = !empty($_SESSION['is_admin']);
?>
<header>
  <div class="container header-flex">
    <div class="logo-section">
      <h1>Tech Navigator</h1>
    </div>
    <nav class="nav">
      <a href="<?= $homeUrl ?>" class="nav-link">Home</a>
      <a href="<?= $basePath ? $basePath . '/categories.php' : 'categories.php' ?>" class="nav-link">Categories</a>
      <a href="<?= $basePath ? $basePath . '/admin.php' : 'admin.php' ?>" class="nav-link">Admin</a>
      <?php // logout link intentionally not in navbar; handled in admin UI ?>
      <a href="<?= $basePath ? $basePath . '/compare.php' : 'compare.php' ?>" class="nav-link compare-link">Compare <span class="compare-count">0</span></a>
    </nav>
  </div>
</header>

<script>
  // Prefix used by frontend JS. When prefix is empty we use relative paths.
  (function(){
    var bp = <?= json_encode($basePath) ?>;
    window.__BASE__ = bp;
    window.__PREFIX__ = bp ? bp + '/' : '';
    window.__ASSET_PREFIX__ = bp ? (bp + '/assets/') : 'assets/';
    // update compare count from localStorage
    document.addEventListener('DOMContentLoaded', function(){
      try{
        var c = JSON.parse(localStorage.getItem('tn_compare')||'[]');
        document.querySelectorAll('.compare-count').forEach(function(el){ el.textContent = c.length; });
      }catch(e){}
      
      // highlight active nav link based on current page (exact pathname match)
      var currentPath = window.location.pathname.replace(/\/+$/, '');
      var links = document.querySelectorAll('.nav-link');
      (function(){
        var bpNorm = (bp || '').replace(/\/+$/, '');
        links.forEach(function(link){
          link.classList.remove('active');
          try{
            var href = link.getAttribute('href');
            if(!href) return;
            var resolved = new URL(href, window.location.origin);
            var linkPath = resolved.pathname.replace(/\/+$/, '');
            // exact match
            if(linkPath === currentPath){ link.classList.add('active'); return; }
            // home link detection: if link points to site root or base path
            var isHomeLink = (linkPath === '' || linkPath === '/' || linkPath === bpNorm || linkPath === (bpNorm ? '/' + bpNorm : ''));
            if(isHomeLink){
              if(currentPath === '' || currentPath === '/' || currentPath.endsWith('index.php') || currentPath === linkPath || currentPath === bpNorm || currentPath === ('/' + bpNorm).replace(/\/+$/, '')){
                link.classList.add('active');
                return;
              }
            }
          }catch(e){}
        });
      })();
    });
  })();
</script>
