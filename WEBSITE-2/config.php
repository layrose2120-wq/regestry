<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

$host = "localhost";
$dbname = "student_users";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$current_usertype = $_SESSION['usertype'] ?? 'Student';
$current_user_id = $_SESSION['user_id'] ?? 0;
$current_fullname = $_SESSION['fullname'] ?? 'Guest';

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
    }
}

if (!function_exists('isStudent')) {
    function isStudent() {
        return isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'Student';
    }
}

if (!function_exists('canManage')) {
    function canManage() {
        return isset($_SESSION['usertype']) &&
            in_array($_SESSION['usertype'], ['Admin', 'Registrar', 'Program Head', 'Professor']);
    }
}

if (!function_exists('canSave')) {
    function canSave() {
        return true;
    }
}
?>