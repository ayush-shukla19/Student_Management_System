<?php
require '../config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin') { header('Location: ../index.php'); exit; }
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin Dashboard</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="topbar">
  <div class="container">
    <span>Admin Dashboard</span>
    <span style="float:right"><a href="../logout.php" class="nav">Logout</a></span>
  </div>
</div>
<div class="container">
  <h3>Manage</h3>
  <ul>
    <li><a href="manage_students.php">Students</a></li>
    <li><a href="manage_teachers.php">Teachers</a></li>
    <li><a href="manage_subjects.php">Subjects</a></li>
  </ul>
</div>
</body></html>
