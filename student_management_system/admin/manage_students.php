<?php
require '../config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin') { header('Location: ../index.php'); exit; }
$msg='';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action'])) {
    if ($_POST['action']==='add') {
        $username = $_POST['username']; $password = $_POST['password']; $name = $_POST['name'];
        // insert user then student
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, "student")');
        $stmt->bind_param('ss', $username, $hash);
        if ($stmt->execute()) {
            $uid = $stmt->insert_id;
            $stmt2 = $conn->prepare('INSERT INTO students (user_id, name, class, phone) VALUES (?, ?, ?, ?)');
            $stmt2->bind_param('isss', $uid, $name, $_POST['class'], $_POST['phone']);
            $stmt2->execute();
            $msg='Student added';
        } else { $msg='Error: '. $conn->error; }
    }
    if ($_POST['action']==='delete' && isset($_POST['student_id'])) {
        $sid = intval($_POST['student_id']);
        $conn->query('DELETE FROM students WHERE student_id='.$sid);
        $msg='Deleted';
    }
}
$res = $conn->query('SELECT s.student_id, s.name, s.class, s.phone, u.username FROM students s LEFT JOIN users u ON s.user_id=u.user_id');
?>
<!doctype html><html><head><meta charset="utf-8"><title>Manage Students</title><link rel="stylesheet" href="../assets/css/style.css"><script src="https://code.jquery.com/jquery-3.6.0.min.js"></script></head>
<body>
<div class="container">
  <h2>Students</h2>
  <p class="msg"><?=htmlspecialchars($msg)?></p>
  <form method="post">
    <input name="action" type="hidden" value="add">
    <input name="username" placeholder="username" required>
    <input name="password" placeholder="password" required>
    <input name="name" placeholder="Full name" required>
    <input name="class" placeholder="Class">
    <input name="phone" placeholder="Phone">
    <button type="submit">Add Student</button>
  </form>
  <h3>All Students</h3>
  <input id="student_search" placeholder="Search by name">
  <table><thead><tr><th>ID</th><th>Name</th><th>Class</th><th>Phone</th><th>Username</th><th>Action</th></tr></thead>
  <tbody id="student_table_body">
  <?php while($row=$res->fetch_assoc()){ ?>
    <tr>
      <td><?= $row['student_id'] ?></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['class']) ?></td>
      <td><?= htmlspecialchars($row['phone']) ?></td>
      <td><?= htmlspecialchars($row['username']) ?></td>
      <td>
        <form method="post" style="display:inline">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="student_id" value="<?= $row['student_id'] ?>">
          <button type="submit" onclick="return confirm('Delete?')">Delete</button>
        </form>
      </td>
    </tr>
  <?php } ?>
  </tbody></table>
</div>
<script src="../assets/js/main.js"></script>
</body></html>
