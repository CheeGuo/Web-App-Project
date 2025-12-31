<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$profilePic = $_SESSION['profile_pic'] ?? '';
$username = $_SESSION['username'] ?? '';
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
    <button type="submit" class="search-icon">&#128269;</button>
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
          <img src="<?= htmlspecialchars($profilePic) ?>" class="profile-pic" alt="Profile Picture">
          <span class="username"><?= htmlspecialchars($username) ?></span>
          <button class="dropdown-toggle" type="button"onclick="toggleDropdown(event)">▼</button>
          <script>
          function toggleDropdown(event) {
              event.stopPropagation(); // Prevents the event from propagating to other elements
              
              const dropdownMenu = document.querySelector('.dropdown-menu');
              const isVisible = dropdownMenu.style.display === 'block';
              dropdownMenu.style.display = isVisible ? 'none' : 'block';
          }

          // Close dropdown when clicking outside
          window.onclick = function(event) {
              const dropdownMenu = document.querySelector('.dropdown-menu');
              
              if (!event.target.matches('.profile-dropdown') && !event.target.matches('.profile-trigger')) {
                  dropdownMenu.style.display = 'none'; // Close dropdown
              }
          };
          </script>

    <div class="dropdown-menu">
            <ul class="profile-dropdown-list">
              <li><a href="index_profile.php">My Profile</a></li>
              <li><a href="index_address.php">My Addresses</a></li>
              <li><a href="index_purchasehistory.php">Purchase History</a></li>
              <li><a href="ask_logout.php">Log Out</a></li>
            </ul>
    </div>
</div>
          
     
        </div>
      <?php endif; ?>
    </div>
  </div>
</header>
