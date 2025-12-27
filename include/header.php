<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$profilePic = $_SESSION['profile_pic'] ?? '';
if ($profilePic === '') {
    $profilePic = 'pic/default-avatar.png';
}
?>
  <link rel="stylesheet" href="style.css">
<header class="top-header">
  <div class="header-inner">

    <div class="logo-area">
      <a href="index.php">
        <img src="pic/arngren logo.png" alt="Arngren Logo">
      </a>
    </div>

    <div class="search-area">
      <input type="text" placeholder="Search">
      <span class="search-icon">🔍</span>
    </div>

    <div class="action-area">
      <button>🛒</button>

      <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="login.php" class="login-link">LOG IN / SIGN UP</a>
      <?php else: ?>
        <div class="profile-dropdown">
          <div class="profile-trigger">
            <img src="<?= $profilePic ?>" class="profile-pic">
            <span class="username"><?= $_SESSION['username'] ?></span>
            <span class="arrow">▼</span>
          </div>

          <div class="dropdown-menu">
            <a href="index_profile.php">My Profile</a>
            <a href="index_address.php">My Addresses</a>
            <a href="index_purchasehistory.php">Purchase History</a>
            <a href="logout.php" class="logout">Log Out</a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</header>
