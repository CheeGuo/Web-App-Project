<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$profilePic = $_SESSION['profile_pic'] ?? 'pic/default-avatar.png';
$username = $_SESSION['username'] ?? '';
?>
<link rel="stylesheet" href="style.css">

<header class="top-header">
  <div class="header-inner">

    <div class="logo-area">
      <a href="admin_index.php">
        <img src="pic/arngren logo.png" alt="Arngren Logo">
      </a>
    </div>

    <div class="action-area">
      <?php if ($username !== ''): ?>
        <div class="admin-info">
          <img src="<?= $profilePic ?>" class="profile-pic">
          <span class="username"><?= $username ?></span>
        </div>
      <?php endif; ?>
    </div>

  </div>
</header>
