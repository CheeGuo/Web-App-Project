<?php
include('include/header.php');
include('include/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT username, email, name, gender, phone, date_registered, profile_pic
    FROM users
    WHERE user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $full_name, $gender, $phone, $created_at, $profile_pic);
$stmt->fetch();
$stmt->close();

if ($profile_pic == '') {
    $profile_pic = 'pic/default-avatar.png';
}
?>
  <link rel="stylesheet" href="style.css">

<main class="profile-page" style = "align-item : center";>

  <div class="profile-card">

    <div class="profile-left">
      <a href="javascript:history.back()" class="back-btn">←</a>
      <h2>My Profile</h2>

      <div class="avatar-box">
        <img src="<?= $profile_pic ?>">
        <a href="#" class="edit-photo">Edit Photo</a>
      </div>
    </div>

    <div class="profile-right">

      <div class="field">
        <label>Username:</label>
        <input type="text" value="<?= $username ?>" readonly>
      </div>

      <div class="field">
        <label>E-mail:</label>
        <input type="text" value="<?= $email ?>" readonly>
      </div>

      <div class="field">
        <label>Full Name:</label>
        <input type="text" value="<?= $full_name ?>" readonly>
      </div>

      <div class="field">
        <label>Gender:</label>
        <input type="text" value="<?= $gender ?>" readonly>
      </div>

      <div class="field edit">
        <label>Phone No:</label>
        <input type="text" value="<?= $phone ?>" readonly>
        <span class="edit-icon">✎</span>
      </div>

      <div class="field edit">
        <label>Password:</label>
        <input type="password" value="************" readonly>
        <span class="edit-icon">✎</span>
      </div>

      <div class="field">
        <label>Date registered:</label>
        <input type="text" value="<?= date('d/m/Y', strtotime($created_at)) ?>" readonly>
      </div>

    </div>

  </div>

</main>

<?php include('include/footer.php'); ?>
