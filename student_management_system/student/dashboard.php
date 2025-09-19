<?php
require '../config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='student') { header('Location: ../index.php'); exit; }
// get student profile
$uid = $_SESSION['user']['user_id'];
$res = $conn->query('SELECT * FROM students WHERE user_id='.$uid);
$student = $res->fetch_assoc();
$marks = $conn->query('SELECT m.*, s.subject_name FROM marks m LEFT JOIN subjects s ON m.subject_id=s.subject_id WHERE m.student_id='.$student['student_id']);
$attendance = $conn->query('SELECT date, status, s.subject_name FROM attendance a LEFT JOIN subjects s ON a.subject_id=s.subject_id WHERE a.student_id='.$student['student_id'].' ORDER BY date DESC LIMIT 50');
?>
<!doctype html><html><head><meta charset='utf-8'><title>Student Dashboard</title><link rel='stylesheet' href='../assets/css/style.css'></head>
<body>
<div class="topbar"><div class="container">Student Dashboard <span style="float:right"><a href="../logout.php">Logout</a></span></div></div>
<div class="container">
  <h2>Welcome, <?=htmlspecialchars($student['name'])?></h2>
  <h3>Marks</h3>
  <table><thead><tr><th>Subject</th><th>Exam</th><th>Marks</th></tr></thead><tbody>
  <?php while($m=$marks->fetch_assoc()){ echo '<tr><td>'.htmlspecialchars($m['subject_name']).'</td><td>'.htmlspecialchars($m['exam_name']).'</td><td>'.htmlspecialchars($m['marks_obtained']).'</td></tr>'; } ?>
  </tbody></table>
  <h3>Recent Attendance</h3>
  <table><thead><tr><th>Date</th><th>Subject</th><th>Status</th></tr></thead><tbody>
  <?php while($a=$attendance->fetch_assoc()){ echo '<tr><td>'.htmlspecialchars($a['date']).'</td><td>'.htmlspecialchars($a['subject_name']).'</td><td>'.htmlspecialchars($a['status']).'</td></tr>'; } ?>
  </tbody></table>
</div>
</body></html>
