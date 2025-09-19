<?php
require '../config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin') { header('Location: ../index.php'); exit; }
$msg='';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action'])) {
    if ($_POST['action']==='add') {
        $username = $_POST['username']; $password = $_POST['password']; $name = $_POST['name'];
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, "teacher")');
        $stmt->bind_param('ss', $username, $hash);
        if ($stmt->execute()) {
            $uid = $stmt->insert_id;
            $stmt2 = $conn->prepare('INSERT INTO teachers (user_id, name, subject, phone, email) VALUES (?, ?, ?, ?, ?)');
            $stmt2->bind_param('issss', $uid, $name, $_POST['subject'], $_POST['phone'], $_POST['email']);
            $stmt2->execute();
            $msg='Teacher added';
        } else { $msg='Error: '. $conn->error; }
    }
}
$res = $conn->query('SELECT t.teacher_id, t.name, t.subject, t.phone, u.username FROM teachers t LEFT JOIN users u ON t.user_id=u.user_id');
?>
<!doctype html><html><head><meta charset="utf-8"><title>Manage Teachers</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="container">
  <h2>Teachers</h2>
  <p class="msg"><?=htmlspecialchars($msg)?></p>
  <form method="post">
    <input name="action" type="hidden" value="add">
    <input name="username" placeholder="username" required>
    <input name="password" placeholder="password" required>
    <input name="name" placeholder="Full name" required>
    <input name="subject" placeholder="Subject">
    <input name="phone" placeholder="Phone">
    <input name="email" placeholder="Email">
    <button type="submit">Add Teacher</button>
  </form>
  <h3>All Teachers</h3>
  <table><thead><tr><th>ID</th><th>Name</th><th>Subject</th><th>Phone</th><th>Username</th></tr></thead>
  <tbody>
  <?php while($row=$res->fetch_assoc()){ ?>
    <tr>
      <td><?= $row['teacher_id'] ?></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['subject']) ?></td>
      <td><?= htmlspecialchars($row['phone']) ?></td>
      <td><?= htmlspecialchars($row['username']) ?></td>
    </tr>
  <?php } ?>
  </tbody></table>
</div>
</body></html>
