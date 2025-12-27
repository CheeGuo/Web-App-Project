<?php
include('include/header.php');
include('include/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$sql = "
    SELECT 
        p.order_id,
        p.total_amount,
        p.payment_date,
        p.payment_status
    FROM payment p
    JOIN cart c ON p.cart_id = c.cart_id
    WHERE c.user_id = ?
";

if ($from && $to) {
    $sql .= " AND DATE(p.payment_date) BETWEEN ? AND ?";
}

$sql .= " ORDER BY p.payment_date DESC";

$stmt = $conn->prepare($sql);

if ($from && $to) {
    $stmt->bind_param("sss", $user_id, $from, $to);
} else {
    $stmt->bind_param("s", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<link rel="stylesheet" href="style.css">
<main class="history-page">

  <h2 class="history-title">Purchase History</h2>
  <form method="get" class="date-filter">
    <span>Date :</span>
    <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">
    <span>—</span>
    <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">
    <button type="submit">Filter</button>
  </form>

  <table class="history-table">
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Total (kr)</th>
        <th>Date</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>

    <?php if ($result->num_rows === 0): ?>
      <tr>
        <td colspan="4" style="text-align:center;">No records found</td>
      </tr>
    <?php endif; ?>

    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['order_id']) ?></td>
        <td><?= number_format($row['total_amount'], 2) ?></td>
        <td><?= date('j/n/Y', strtotime($row['payment_date'])) ?></td>
        <td><?= $row['payment_status'] == 1 ? 'Successful' : 'Pending' ?></td>
      </tr>
    <?php endwhile; ?>

    </tbody>
  </table>

</main>

<?php include('include/footer.php'); ?>
