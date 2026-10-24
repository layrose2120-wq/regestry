<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

if (!isset($_SESSION['user_id'], $_SESSION['usertype'])) {
    header("Location: login.php");
    exit;
}

$allow = [
    'Admin'         => [
        'index.php',
        'course.php',
        'year_level.php',
        'term.php',
        'school_year.php',
        'section.php',
        'subjects.php',
        'instructors.php',
        'users.php',
        'user_types.php'
    ],
    'Registrar'     => [
        'index.php',
        'subjects.php',
        'term.php',
        'instructors.php',
        'school_year.php'
    ],
    'Program Head'  => [
        'index.php',
        'subjects.php',
        'section.php',
        'instructors.php',
        'year_level.php'
    ],
    'Professor'     => [
        'index.php',
        'subjects.php',
        'section.php'
    ],
    'Student'       => [
        'index.php',
        'subjects.php'
    ]
];

$cur_page = basename($_SERVER['PHP_SELF']);
$usertype = $_SESSION['usertype'];

if (!isset($allow[$usertype]) || !in_array($cur_page, $allow[$usertype])) {
    http_response_code(403);
    echo "<h3>Access Denied</h3>";
    echo "<p>You do not have permission to view this page.</p>";
    echo "<p><small>Page: " . htmlspecialchars($cur_page) . " | Role: " . htmlspecialchars($usertype) . "</small></p>";
    echo "<a href='index.php'>← Back to Dashboard</a>";
    exit;
}

if (!function_exists('can_view')) {
    function can_view($page) {
        global $allow, $usertype;
        return isset($allow[$usertype]) && in_array($page, $allow[$usertype]);
    }
}

if ($usertype === 'Student' && !defined('HIDE_STUDENT_LIST')) {
    define('HIDE_STUDENT_LIST', true);
}
?>