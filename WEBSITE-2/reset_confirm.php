<?php
require 'config.php';

$error = $success = "";
$token = $_GET['token'] ?? '';

$stmt = $conn->prepare("SELECT email FROM TBL_PASSWORD_RESETS WHERE token = ? AND expiry > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $error = "Invalid or expired token. Request a new reset link.";
} else {
    $reset_data = $result->fetch_assoc();
    $email = $reset_data['email'];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_pass = $_POST['new_password'];
        $confirm_pass = $_POST['confirm_password'];
        
        if ($new_pass !== $confirm_pass) {
            $error = "Passwords do not match!";
        } elseif (strlen($new_pass) < 6) {
            $error = "Password must be at least 6 characters!";
        } else {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE TBL_USERS SET password = ? WHERE email = ?");
            $update->bind_param("ss", $hashed, $email);
            $update->execute();
            
            $del = $conn->prepare("DELETE FROM TBL_PASSWORD_RESETS WHERE token = ?");
            $del->bind_param("s", $token);
            $del->execute();
            
            $success = "Password updated successfully! <a href='login.php'>Login now</a>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password — Student Registration System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
    <div class="login-card">
        <h2>🔄 Reset Password</h2>
        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-msg"><?= $success ?></div>
        <?php endif; ?>
        <?php if (!$success && !$error): ?>
            <form method="post">
                <label>New Password *</label>
                <input type="password" name="new_password" required placeholder="Min. 6 characters">
                <label>Confirm New Password *</label>
                <input type="password" name="confirm_password" required placeholder="Repeat password">
                <button type="submit" class="btn-primary">Update Password</button>
            </form>
        <?php endif; ?>
        <p class="auth-note"><a href="login.php">← Back to Login</a></p>
    </div>
</body>
</html>