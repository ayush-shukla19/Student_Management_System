<?php
// DB configuration - update if needed
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$port = 3307;

$DB_NAME = 'student_management';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $port);
if ($conn->connect_error) {
    die('Database connection error: ' . $conn->connect_error);
}
session_start();
?>