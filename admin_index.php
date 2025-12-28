<?php
include('include/admin_header.php');
include('include/db.php');

$memberResult = $conn->query("SELECT COUNT(*) AS total_members FROM users WHERE role = 'customer'");
$totalMembers = $memberResult->fetch_assoc()['total_members'] ?? 0;

$incomeResult = $conn->query("
    SELECT SUM(total_amount) AS total_income
    FROM payment
    WHERE payment_status = 1
    AND payment_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
");
$totalIncome = $incomeResult->fetch_assoc()['total_income'] ?? 0;

$salesResult = $conn->query("
    SELECT SUM(pi.quantity) AS total_sales
    FROM payment_item pi
    JOIN payment p ON pi.payment_id = p.payment_id
    WHERE p.payment_status = 1
    AND p.payment_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
");
$totalSales = $salesResult->fetch_assoc()['total_sales'] ?? 0;

$trendResult = $conn->query("
    SELECT 
        DATE_FORMAT(p.payment_date, '%Y-%m') AS ym,
        SUM(p.total_amount) AS income,
        SUM(pi.quantity) AS sales
    FROM payment p
    JOIN payment_item pi ON p.payment_id = pi.payment_id
    WHERE p.payment_status = 1
    AND p.payment_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY ym
    ORDER BY ym ASC
");

$labels = [];
$incomeData = [];
$salesData = [];

while ($r = $trendResult->fetch_assoc()) {
    $labels[] = $r['ym'];
    $incomeData[] = (float)$r['income'];
    $salesData[] = (int)$r['sales'];
}
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
    <h2><?= number_format($totalIncome,2) ?> kr</h2>
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
    <canvas id="incomeChart"></canvas>
  </div>

  <div class="chart-box">
    <h3>Sales Summary (Last 6 months)</h3>
    <canvas id="salesChart"></canvas>
  </div>
</div>

<div class="report-area">
  <button class="download-btn">Download Report</button>
</div>

</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?= json_encode($labels) ?>;
const incomeData = <?= json_encode($incomeData) ?>;
const salesData = <?= json_encode($salesData) ?>;

new Chart(document.getElementById('incomeChart'), {
  type: 'line',
  data: {
    labels: labels,
    datasets: [{
      label: 'Income (kr)',
      data: incomeData,
      tension: 0.35
    }]
  },
  options: {
    responsive: true,
    scales: { y: { beginAtZero: true } }
  }
});

new Chart(document.getElementById('salesChart'), {
  type: 'bar',
  data: {
    labels: labels,
    datasets: [{
      label: 'Sales Quantity',
      data: salesData
    }]
  },
  options: {
    responsive: true,
    scales: { y: { beginAtZero: true } }
  }
});
</script>

<?php include('include/footer.php'); ?>
