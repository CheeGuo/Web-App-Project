<?php
include('include/admin_header.php');
include('include/db.php');
$memberResult = $conn->query("SELECT COUNT(*) AS total_members FROM users WHERE role = 'customer'");
$memberRow = $memberResult->fetch_assoc();
$totalMembers = $memberRow['total_members'] -1 ;

$incomeResult = $conn->query("
    SELECT SUM(total_amount) AS total_income
    FROM payment
    WHERE payment_status = 1
    AND payment_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
");

$incomeRow = $incomeResult->fetch_assoc();
$totalIncome = $incomeRow['total_income'] ?? 0;

$salesResult = $conn->query("
    SELECT SUM(pi.quantity) AS total_sales
    FROM payment_item pi
    JOIN payment p ON pi.payment_id = p.payment_id
    WHERE p.payment_status = 1
    AND p.payment_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
");

$salesRow = $salesResult->fetch_assoc();
$totalSales = $salesRow['total_sales'] ?? 0;
?>

<div class="admin-wrapper">
 <link rel="stylesheet" href="admin.css">
  <aside class="admin-sidebar">
    <h3 class="sidebar-title">Dashboard</h3>
    <a href="admin_index.php" class="active">📊 Dashboards</a>
    <a href="admin_users.php">👤 Users</a>
    <a href="admin_products.php">📦 Products</a>
    <a href="admin_sales.php">💰 Sales History</a>
    <a href="ask_logout.php" class="logout">🚪 Log Out</a>
  </aside>

  <main class="admin-main">
    
    <div class="stat-cards">
      <div class="stat-card">
  <p>Total Income (Last 6 months)</p>
  <h2><?= number_format($totalIncome, 2) ?> kr</h2>
</div>

     <div class="stat-card">
  <p>Total Sales (Last 6 months)</p>
  <h2><?= $totalSales ?></h2>
</div>

      <div class="stat-card">
        <p>Registered Members</p>
        <h2><?= $totalMembers ?></h2>
      </div>
    </div>

    <div class="chart-section">
      <div class="chart-box">
        <h3>Income Chart</h3>
        <div class="chart-placeholder">[ Line Chart ]</div>
      </div>

      <div class="chart-box">
        <h3>Sales Summary (Last 6 months)</h3>
        <div class="chart-placeholder">[ Bar Chart ]</div>
      </div>
    </div>

    <div class="report-area">
      <button class="download-btn">Download Report</button>
    </div>

  </main>

</div>
