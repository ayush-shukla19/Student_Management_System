<?php
require 'config.php';
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $conn->prepare('SELECT user_id, password, role FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        // For demo: accept plain password match OR password_verify if hashed
        if (password_verify($password, $row['password'])) {
    // Password already hashed and matched
    $_SESSION['user'] = ['user_id' => $row['user_id'], 'role' => $row['role'], 'username' => $username];
    header('Location: dashboard.php');
    exit;
} elseif ($password === $row['password']) {
    // Purana plain password match ho gaya
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE users SET password=? WHERE user_id=?");
    $update->bind_param("si", $newHash, $row['user_id']);
    $update->execute();

    $_SESSION['user'] = ['user_id' => $row['user_id'], 'role' => $row['role'], 'username' => $username];
    header('Location: dashboard.php');
    exit;
} else {
    $error = 'Invalid credentials';
}

    } else {
        $error = 'User not found';
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login - Student Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f5f6fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="card p-4" style="width: 350px;">
    <h3 class="text-center mb-3">Login</h3>
    <?php if($error) echo '<div class="alert alert-danger">'.$error.'</div>'; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input name="username" class="form-control" placeholder="Enter username" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input name="password" type="password" class="form-control" placeholder="Enter password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</body>
</html>
</body>
</html>
