<?php
session_start();
$cfg = require __DIR__ . '/config/config.php';
$assetPrefix = ($cfg['base_path'] ?? '') ? ($cfg['base_path'] . '/assets') : 'assets';

// handle login
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_password'])){
    $pw = $_POST['admin_password'];
    if($pw === ($cfg['admin_password'] ?? '')){
        $_SESSION['is_admin'] = true;
        header('Location: admin.php'); exit;
    } else {
        $error = 'Invalid password';
    }
}

// handle logout
if(isset($_GET['logout'])){ unset($_SESSION['is_admin']); header('Location: admin.php'); exit; }

if(!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']){
    // show login form
    ?>
    <!doctype html><html><head><meta charset="utf-8"><title>Admin Login</title>
    <link rel="stylesheet" href="<?= $assetPrefix ?>/css/main.css">
    <style>body{background:#f3f6fb;padding:40px} .login-card{max-width:420px;margin:40px auto;padding:20px;background:#fff;border-radius:8px;box-shadow:0 4px 20px rgba(0,0,0,0.06)}</style>
    </head><body>
    <div class="login-card">
      <h2>Admin Login</h2>
      <?php if(!empty($error)) echo '<div style="color:red;margin-bottom:8px">'.htmlspecialchars($error).'</div>'; ?>
      <form method="POST">
        <div style="margin-bottom:10px"><label>Password</label><input type="password" name="admin_password" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px" /></div>
        <div><button class="btn" type="submit">Sign In</button></div>
      </form>
      <p style="margin-top:12px;color:#666;font-size:13px">Note: This is simple local auth. Change the password in <code>config/config.php</code>.</p>
    </div>
    </body></html>
    <?php
    exit;
}

// include the admin UI
include __DIR__ . '/views/admin/add-item.php';
