<?php
include('include/admin_header.php');
include('include/db.php');

$start = $_GET['start'] ?? '';
$end = $_GET['end'] ?? '';

$sql = "SELECT user_id, username, gender, email, date_registered, phone FROM users WHERE role = 'customer'";

if ($start && $end) {
    $sql .= " AND DATE(date_registered) BETWEEN '$start' AND '$end'";
}

$sql .= " ORDER BY date_registered DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registered Members</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div style="width:90%; margin:40px auto;">
    <h2 style="text-align:center;">Registered Members</h2>

    <form method="GET" style="text-align:center; margin-bottom:20px;">
        <input type="date" name="start" value="<?= $start ?>">
        —
        <input type="date" name="end" value="<?= $end ?>">
        <button type="submit">Filter</button>
    </form>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr>
            <th>Account ID</th>
            <th>Username</th>
            <th>Gender</th>
            <th>E-mail</th>
            <th>Date registered</th>
            <th>Phone No</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['user_id'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= ucfirst($row['gender']) ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= date("j/n/Y", strtotime($row['date_registered'])) ?></td>
            <td><?= $row['phone'] ?? '-' ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
