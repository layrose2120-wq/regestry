<?php
require 'config.php';
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}
$error = '';
$username_val = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_val = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username_val) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        $stmt = $conn->prepare("SELECT `ID`, `FULLNAME`, `PASSWORD`, `USERTYPEID` FROM `tbl_users` WHERE `USERNAME` = ? LIMIT 1");
        $stmt->bind_param("s", $username_val);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            $password_ok = false;
            if (strlen($user['PASSWORD']) > 30 && str_starts_with($user['PASSWORD'], '$')) {
                $password_ok = password_verify($password, $user['PASSWORD']);
            } else {
                $password_ok = ($password === $user['PASSWORD']);
                if ($password_ok) {
                    $new_hash = password_hash($password, PASSWORD_DEFAULT);
                    $update_stmt = $conn->prepare("UPDATE `tbl_users` SET `PASSWORD` = ? WHERE `ID` = ?");
                    $update_stmt->bind_param("si", $new_hash, $user['ID']);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
            }

            if ($password_ok) {
                $_SESSION['user_id'] = $user['ID'];
                $_SESSION['fullname'] = $user['FULLNAME'] ?? '';
                $_SESSION['usertypeid'] = $user['USERTYPEID'] ?? '';

                $type_stmt = $conn->prepare("SELECT `USERTYPE` FROM `tbl_usertype` WHERE `ID` = ?");
                $type_stmt->bind_param("i", $_SESSION['usertypeid']);
                $type_stmt->execute();
                $type_result = $type_stmt->get_result();

                if ($type_result->num_rows === 1) {
                    $type_row = $type_result->fetch_assoc();
                    $_SESSION['usertype'] = $type_row['USERTYPE'];
                } else {
                    $_SESSION['usertype'] = 'Student';
                }
                $type_stmt->close();

                header("Location: index.php");
                exit;
            }
        }
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; display: flex; justify-content: center; padding-top: 50px; }
        .card { background: white; padding: 30px; border-radius: 10px; width: 350px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .error { background: #ffebee; color: #c62828; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        input { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #2563eb; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; margin-top: 10px; }
        .link { text-align: center; margin-top: 15px; }
        .link a { color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h2 style="text-align:center; margin-bottom:20px;">Login</h2>
    
    <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($username_val) ?>" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <div class="link">
        Don't have an account? <a href="register.php">Create Account</a>
    </div>
</div>
</body>
</html>