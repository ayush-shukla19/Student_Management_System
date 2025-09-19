<?php
require '../config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='teacher') { header('Location: ../index.php'); exit; }
// show students and allow marking - simple interface
$students = $conn->query('SELECT student_id, name FROM students');
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['date'])) {
    $date = $_POST['date'];
    foreach($_POST['status'] as $sid => $st) {
        $sid = intval($sid);
        $st = $conn->real_escape_string($st);
        // assume subject_id = posted subject_id
        $sub = intval($_POST['subject_id']);
        // upsert: delete existing for simplicity then insert
        $conn->query("DELETE FROM attendance WHERE student_id=$sid AND subject_id=$sub AND date='$date'");
        $conn->query("INSERT INTO attendance (student_id, subject_id, date, status) VALUES ($sid, $sub, '$date', '$st')");
    }
    $msg='Attendance saved';
}
$subjects = $conn->query('SELECT subject_id, subject_name FROM subjects');
?>
<!doctype html><html><head><meta charset='utf-8'><title>Attendance</title><link rel='stylesheet' href='../assets/css/style.css'></head>
<body>
<div class="container">
  <h2>Mark Attendance</h2>
  <p><?= $msg ?? '' ?></p>
  <form method="post">
    <label>Date: <input type="date" name="date" required value="<?= date('Y-m-d') ?>"></label>
    <label>Subject:
      <select name="subject_id"><?php while($s=$subjects->fetch_assoc()){ echo '<option value="'.$s['subject_id'].'">'.htmlspecialchars($s['subject_name']).'</option>'; } ?></select>
    </label>
    <table><thead><tr><th>Student</th><th>Status</th></tr></thead><tbody>
    <?php while($st=$students->fetch_assoc()){ ?>
      <tr>
        <td><?=htmlspecialchars($st['name'])?></td>
        <td>
          <select name="status[<?= $st['student_id'] ?>]">
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
          </select>
        </td>
      </tr>
    <?php } ?>
    </tbody></table>
    <button type="submit">Save</button>
  </form>
</div>
</body></html>
