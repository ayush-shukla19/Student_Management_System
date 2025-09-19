<?php
require '../config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin') { exit; }
$q = '%'.($conn->real_escape_string($_GET['q'] ?? '')).'%';
$res = $conn->query("SELECT s.student_id, s.name, s.class, s.phone, u.username FROM students s LEFT JOIN users u ON s.user_id=u.user_id WHERE s.name LIKE '$q' LIMIT 50");
while($row=$res->fetch_assoc()){
  echo '<tr><td>'.$row['student_id'].'</td><td>'.htmlspecialchars($row['name']).'</td><td>'.htmlspecialchars($row['class']).'</td><td>'.htmlspecialchars($row['phone']).'</td><td>'.htmlspecialchars($row['username']).'</td><td></td></tr>';
}
