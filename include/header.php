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
  <form action="search.php" method="get">
    <input type="text" name="keyword" placeholder="Search" required>
    <button type="submit" class="search-icon">🔍</button>
  </form>
</div>
    <div class="action-area">
      <a href="cart.php">
      <button style="  width: 42px;height: 42px;" >🛒</button>
      </a>
      <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="login.php" class="login-link">LOG IN / SIGN UP</a>
      <?php else: ?>
        <div class="profile-dropdown">
          <div class="profile-trigger">
  <img src="<?= $profilePic ?>" class="profile-pic">
  <span class="username"><?= $_SESSION['username'] ?></span>

  <div class="dropdown-menu">
    <select class="profile-select" onchange="location = this.value;">
      <option value="" selected disabled></option>
      <option value="index_profile.php">My Profile</option>
      <option value="index_address.php">My Addresses</option>
      <option value="index_purchasehistory.php">Purchase History</option>
      <option value="ask_logout.php">Log Out</option>
    </select>
  </div>
</div>
          
     
        </div>
      <?php endif; ?>
    </div>
  </div>
</header>
