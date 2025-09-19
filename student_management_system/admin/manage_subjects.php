<?php
require '../config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin') { header('Location: ../index.php'); exit; }
$msg='';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action'])) {
    if ($_POST['action']==='add') {
        $stmt = $conn->prepare('INSERT INTO subjects (subject_name, teacher_id) VALUES (?, ?)');
        $stmt->bind_param('si', $_POST['subject_name'], $_POST['teacher_id']);
        $stmt->execute();
        $msg='Subject added';
    }
}
$teachers = $conn->query('SELECT teacher_id, name FROM teachers');
$res = $conn->query('SELECT s.subject_id, s.subject_name, t.name as teacher FROM subjects s LEFT JOIN teachers t ON s.teacher_id=t.teacher_id');
?>
<!doctype html><html><head><meta charset="utf-8"><title>Manage Subjects</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="container">
  <h2>Subjects</h2>
  <p class="msg"><?=htmlspecialchars($msg)?></p>
  <form method="post">
    <input name="action" type="hidden" value="add">
    <input name="subject_name" placeholder="Subject name" required>
    <select name="teacher_id">
      <option value="">-- Select teacher --</option>
      <?php while($t=$teachers->fetch_assoc()){ echo '<option value="'.$t['teacher_id'].'">'.htmlspecialchars($t['name']).'</option>'; } ?>
    </select>
    <button type="submit">Add Subject</button>
  </form>
  <h3>All Subjects</h3>
  <table><thead><tr><th>ID</th><th>Name</th><th>Teacher</th></tr></thead>
  <tbody>
  <?php while($row=$res->fetch_assoc()){ ?>
    <tr>
      <td><?= $row['subject_id'] ?></td>
      <td><?= htmlspecialchars($row['subject_name']) ?></td>
      <td><?= htmlspecialchars($row['teacher']) ?></td>
    </tr>
  <?php } ?>
  </tbody></table>
</div>
</body>
</html>
