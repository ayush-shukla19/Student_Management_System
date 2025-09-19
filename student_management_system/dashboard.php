<?php
require 'config.php';
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$role = $_SESSION['user']['role'];

if ($role === 'teacher') {
    header('Location: teacher/dashboard.php');
    exit;
} elseif ($role === 'student') {
    header('Location: student/dashboard.php');
    exit;
}

// Admin dashboard ke liye counts
$studentCount = $conn->query("SELECT COUNT(*) AS c FROM students")->fetch_assoc()['c'] ?? 0;
$teacherCount = $conn->query("SELECT COUNT(*) AS c FROM teachers")->fetch_assoc()['c'] ?? 0;
$subjectCount = $conn->query("SELECT COUNT(*) AS c FROM subjects")->fetch_assoc()['c'] ?? 0;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
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
    <a class="navbar-brand" href="#">Student Management</a>
    <div class="d-flex">
      <span class="navbar-text me-3">
        Welcome, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>
      </span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h3 class="mb-4">Admin Dashboard</h3>
  <div class="row g-3">
    
    <!-- Students -->
    <div class="col-md-3">
      <a href="admin/manage_students.php" class="card card-hover text-center shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Students</h5>
          <p class="display-6"><?php echo $studentCount; ?></p>
        </div>
      </a>
    </div>

    <!-- Teachers -->
    <div class="col-md-3">
      <a href="admin/manage_teachers.php" class="card card-hover text-center shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Teachers</h5>
          <p class="display-6"><?php echo $teacherCount; ?></p>
        </div>
      </a>
    </div>

    <!-- Subjects -->
    <div class="col-md-3">
      <a href="admin/manage_subjects.php" class="card card-hover text-center shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Subjects</h5>
          <p class="display-6"><?php echo $subjectCount; ?></p>
        </div>
      </a>
    </div>

  </div>
</div>
</body>
</html>
