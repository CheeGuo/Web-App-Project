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
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($username, $db_email, $full_name, $gender, $phone, $created_at, $profile_pic);
$stmt->fetch();
$stmt->close();

if ($profile_pic == NULL || $profile_pic == '') {
    $profile_pic = 'pic/default-avatar.png';
}

if ($phone == NULL || $phone == '-') {
    $phone = '';
}

if ($gender == NULL || $gender == '-') {
    $gender = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];

    if ($email !== $db_email) {
        $check = $conn->prepare("
            SELECT user_id FROM users 
            WHERE email = ? AND user_id != ?
        ");
        $check->bind_param("ss", $email, $user_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email already in use.";
        }

        $check->close();
    }

    if (!isset($error)) {
        if ($password !== '') {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            if ($email !== $db_email) {
                $stmt = $conn->prepare("
                    UPDATE users 
                    SET email = ?, phone = ?, gender = ?, password = ?
                    WHERE user_id = ?
                ");
                $stmt->bind_param("sssss", $email, $phone, $gender, $hashed, $user_id);
            } else {
                $stmt = $conn->prepare("
                    UPDATE users 
                    SET phone = ?, gender = ?, password = ?
                    WHERE user_id = ?
                ");
                $stmt->bind_param("ssss", $phone, $gender, $hashed, $user_id);
            }
        } else {
            if ($email !== $db_email) {
                $stmt = $conn->prepare("
                    UPDATE users 
                    SET email = ?, phone = ?, gender = ?
                    WHERE user_id = ?
                ");
                $stmt->bind_param("ssss", $email, $phone, $gender, $user_id);
            } else {
                $stmt = $conn->prepare("
                    UPDATE users 
                    SET phone = ?, gender = ?
                    WHERE user_id = ?
                ");
                $stmt->bind_param("sss", $phone, $gender, $user_id);
            }
        }

        $stmt->execute();
        $stmt->close();

        header("Location: index_profile.php");
        exit();
    }
}
?>

<link rel="stylesheet" href="style.css">

<main class="profile-page">

  <div class="profile-card">

    <div class="profile-left">
      <a href="javascript:history.back()" class="back-btn">←</a>
      <h2>My Profile</h2>

      <div class="avatar-box">
        <img src="<?= $profile_pic ?>">
      </div>
    </div>

    <form method="post" class="profile-right">

      <div class="field">
        <label>Username:</label>
        <input type="text" value="<?= $username ?>" readonly>
      </div>

      <div class="field">
        <label>E-mail:</label>
        <input type="email" name="email" value="<?= $db_email ?>" required>
      </div>

      <div class="field">
        <label>Full Name:</label>
        <input type="text" value="<?= $full_name ?>" readonly>
      </div>

      <div class="field">
        <label>Gender:</label>
        <select name="gender" required>
          <option value="">Select</option>
          <option value="male" <?= $gender==="male"?"selected":"" ?>>Male</option>
          <option value="female" <?= $gender==="female"?"selected":"" ?>>Female</option>
        </select>
      </div>

      <div class="field">
        <label>Phone No:</label>
        <input type="text" name="phone" value="<?= $phone ?>">
      </div>

      <div class="field">
        <label>New Password:</label>
        <input type="password" name="password" placeholder="Leave blank to keep current password">
      </div>

      <div class="field">
        <label>Date registered:</label>
        <input type="text" value="<?= date('d/m/Y', strtotime($created_at)) ?>" readonly>
      </div>

      <?php if (isset($error)): ?>
        <p style="color:red; margin-top:10px;"><?= $error ?></p>
      <?php endif; ?>

      <div style="margin-top:25px;">
        <button type="submit" class="login-btn">Save Changes</button>
      </div>

    </form>

  </div>

</main>

<?php include('include/footer.php'); ?>
