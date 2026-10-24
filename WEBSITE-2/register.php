<?php
require 'config.php';
$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $usertypename = trim($_POST['usertype'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    $allowed_types = ['Admin', 'Registrar', 'Program Head', 'Professor', 'Student'];
    
    if (!in_array($usertypename, $allowed_types)) {
        $error = "Invalid user type selected!";
    } elseif ($password !== $confirm_pass) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } elseif (empty($username) || empty($fullname) || empty($email)) {
        $error = "Please fill in all required fields!";
    } else {
        $check = $conn->prepare("SELECT `USERNAME` FROM `tbl_users` WHERE `USERNAME` = ? OR `EMAIL` = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            $error = "Username or Email already registered!";
        } else {
            $id_stmt = $conn->prepare("SELECT `ID` FROM `tbl_usertype` WHERE `USERTYPE` = ?");
            $id_stmt->bind_param("s", $usertypename);
            $id_stmt->execute();
            $id_result = $id_stmt->get_result();
            
            if ($id_result->num_rows === 0) {
                $error = "User type not found in database!";
            } else {
                $id_row = $id_result->fetch_assoc();
                $usertypeid = $id_row['ID'];
                $hashed_pw = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $conn->prepare("INSERT INTO `tbl_users` (`USERNAME`, `FULLNAME`, `EMAIL`, `USERTYPEID`, `PASSWORD`) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssis", $username, $fullname, $email, $usertypeid, $hashed_pw);
                
                if ($stmt->execute()) {
                    $_SESSION['user_id'] = $conn->insert_id;
                    $_SESSION['fullname'] = $fullname;
                    $_SESSION['username'] = $username;
                    $_SESSION['usertype'] = $usertypename;
                    $success = "Account created successfully! <a href='login.php'>Login here</a>";
                } else {
                    $error = "Error creating account: " . $conn->error;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register — Student Registration System</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .register-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
        }
        .register-card h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        .error-msg {
            background: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success-msg {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 12px 0 5px;
            color: #444;
            font-weight: 500;
        }
        input, select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background: #1d4ed8;
        }
        .auth-note {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        .auth-note a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <h2>📝 Create Account</h2>
        
        <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="success-msg"><?= $success ?></div>
        <?php endif; ?>
        
        <form method="post">
            <label>Username *</label>
            <input type="text" name="username" required placeholder="e.g. juan.dela.cruz">
            
            <label>Full Name *</label>
            <input type="text" name="fullname" required placeholder="e.g. Juan Dela Cruz">
            
            <label>Email *</label>
            <input type="email" name="email" required placeholder="your@email.com">
            
            <label>User Type *</label>
            <select name="usertype" required>
                <option value="">— Select User Type —</option>
                <option>Admin</option>
                <option>Registrar</option>
                <option>Program Head</option>
                <option>Professor</option>
                <option>Student</option>
            </select>
            
            <label>Password *</label>
            <input type="password" name="password" required placeholder="Minimum 6 characters">
            
            <label>Confirm Password *</label>
            <input type="password" name="confirm_password" required placeholder="Repeat password">
            
            <button type="submit">Register Account</button>
        </form>
        
        <p class="auth-note">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>