<?php
require '../config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='teacher') { header('Location: ../index.php'); exit; }
$students = $conn->query('SELECT student_id, name FROM students');
$subjects = $conn->query('SELECT subject_id, subject_name FROM subjects');
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $exam = $conn->real_escape_string($_POST['exam_name']);
    $sub = intval($_POST['subject_id']);
    foreach($_POST['marks'] as $sid => $m) {
        $sid = intval($sid);
        $marks = floatval($m);
        $stmt = $conn->prepare('INSERT INTO marks (student_id, subject_id, exam_name, marks_obtained) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('iisd', $sid, $sub, $exam, $marks);
        $stmt->execute();
    }
    $msg='Marks saved';
}
?>
<!doctype html><html><head><meta charset='utf-8'><title>Marks</title><link rel='stylesheet' href='../assets/css/style.css'></head>
<body>
<div class="container">
  <h2>Enter Marks</h2>
  <p><?= $msg ?? '' ?></p>
  <form method="post">
    <input name="exam_name" placeholder="Exam name" required>
    <select name="subject_id"><?php while($s=$subjects->fetch_assoc()){ echo '<option value="'.$s['subject_id'].'">'.htmlspecialchars($s['subject_name']).'</option>'; } ?></select>
    <table><thead><tr><th>Student</th><th>Marks</th></tr></thead><tbody>
    <?php while($st=$students->fetch_assoc()){ ?>
      <tr><td><?=htmlspecialchars($st['name'])?></td><td><input name="marks[<?= $st['student_id'] ?>]" placeholder="e.g. 78"></td></tr>
    <?php } ?>
    </tbody></table>
    <button type="submit">Save Marks</button>
  </form>
</div>
</body></html>
