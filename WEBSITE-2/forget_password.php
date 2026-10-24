<?php
require 'config.php';

$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    
    $stmt = $conn->prepare("SELECT username, fullname FROM TBL_USERS WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", time() + 3600);
        
        $upsert = $conn->prepare("INSERT INTO TBL_PASSWORD_RESETS (email, token, expiry) VALUES (?, ?, ?) 
                                   ON DUPLICATE KEY UPDATE token=?, expiry=?");
        $upsert->bind_param("ssss", $email, $token, $expiry, $token, $expiry);
        $upsert->execute();
        
        $reset_link = "http://localhost/student_users/reset_confirm.php?token=$token";
        $success = "Reset link generated! (In production, this would be emailed)<br>
                   <a href='$reset_link'>👉 Click here to reset your password</a>";
    } else {
        $success = "If this email exists, a reset link has been sent. Check your inbox.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password — Student Registration System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
    <div class="login-card">
        <h2>🔑 Forgot Password</h2>
        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-msg"><?= $success ?></div>
        <?php endif; ?>
        <?php if (!$success): ?>
            <form method="post">
                <label>Email Address *</label>
                <input type="email" name="email" required placeholder="Enter your registered email">
                <button type="submit" class="btn-primary">Send Reset Link</button>
            </form>
        <?php endif; ?>
        <p class="auth-note"><a href="login.php">← Back to Login</a></p>
    </div>
</body>
</html>