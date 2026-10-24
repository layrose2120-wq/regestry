<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
require 'check_access.php';

$current_page = basename($_SERVER['PHP_SELF']);
$usertype = $_SESSION['usertype'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-container">
    <aside class="sidebar">
        <div class="sidebar-header">
            <h3>📚 System</h3>
            <p class="user-info">
                <?= htmlspecialchars($_SESSION['fullname'] ?? 'Guest') ?><br>
                <small><?= htmlspecialchars($usertype) ?></small>
            </p>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php" class="nav-link <?= $current_page === 'index.php' ? 'active' : '' ?>">Dashboard</a>

            <?php if (can_view('course.php')): ?>
            <a href="course.php" class="nav-link <?= $current_page === 'course.php' ? 'active' : '' ?>">Course</a>
            <?php endif; ?>

            <?php if (can_view('year_level.php')): ?>
            <a href="year_level.php" class="nav-link <?= $current_page === 'year_level.php' ? 'active' : '' ?>">Year Level</a>
            <?php endif; ?>

            <?php if (can_view('term.php')): ?>
            <a href="term.php" class="nav-link <?= $current_page === 'term.php' ? 'active' : '' ?>">Term</a>
            <?php endif; ?>

            <?php if (can_view('school_year.php')): ?>
            <a href="school_year.php" class="nav-link <?= $current_page === 'school_year.php' ? 'active' : '' ?>">School Year</a>
            <?php endif; ?>

            <?php if (can_view('section.php')): ?>
            <a href="section.php" class="nav-link <?= $current_page === 'section.php' ? 'active' : '' ?>">Section</a>
            <?php endif; ?>

            <?php if (can_view('subjects.php')): ?>
            <a href="subjects.php" class="nav-link <?= $current_page === 'subjects.php' ? 'active' : '' ?>">Subjects</a>
            <?php endif; ?>

            <?php if (can_view('instructors.php')): ?>
            <a href="instructors.php" class="nav-link <?= $current_page === 'instructors.php' ? 'active' : '' ?>">Instructors</a>
            <?php endif; ?>

            <?php if (can_view('users.php')): ?>
            <a href="users.php" class="nav-link <?= $current_page === 'users.php' ? 'active' : '' ?>">Users</a>
            <?php endif; ?>

            <?php if (can_view('user_types.php')): ?>
            <a href="user_types.php" class="nav-link <?= $current_page === 'user_types.php' ? 'active' : '' ?>">User Types</a>
            <?php endif; ?>

            <a href="logout.php" class="nav-link logout-link">Logout</a>
        </nav>
    </aside>
    <main class="main-content">