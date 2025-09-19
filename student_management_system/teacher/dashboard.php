<?php
require '../config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='teacher') { header('Location: ../index.php'); exit; }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Teacher Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-hover {
      text-decoration: none;
      color: inherit;
    }
    .card-hover:hover {
      transform: scale(1.05);
      transition: 0.3s;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Student Management System</a>
    <div class="d-flex">
      <span class="navbar-text me-3">
        Welcome, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>
      </span>
      <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h3 class="mb-4">Teacher Dashboard</h3>
  <div class="row g-3">
    <!-- Marks -->
    <div class="col-md-3">
      <div class="card card-hover text-center shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Enter marks</h5>
        </div>
        <a href="marks.php">Click here....</a>
  </div>
    </div>

    <!-- Attendance -->
    <div class="col-md-3">
      <div class="card card-hover text-center shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Mark Attendance</h5>
        </div>
        <a href="attendance.php">Click here...</a>
  </div>
    </div>

  </div>
</div>
</body>
</html>