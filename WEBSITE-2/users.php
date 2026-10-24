<?php
require 'header.php';
$message = ""; 
$id = "";
$username = $fullname = $email = $usertype = "";
$allowed_types = ['Admin','Registrar','Program Head','Professor','Student'];

// ✅ Image upload configuration
$targetDir = "images/users/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true); // Create folder if missing
}
$allowTypes = ['jpg', 'png', 'jpeg', 'gif'];

// --- Search ---
if(isset($_POST['btn_search'])) {
    $s = trim($_POST['search_username']);
    $stmt = $conn->prepare("SELECT u.`ID`,u.`USERNAME`,u.`FULLNAME`,u.`EMAIL`,ut.`USERTYPE` 
                            FROM `tbl_users` u LEFT JOIN `tbl_usertype` ut ON u.`USERTYPEID`=ut.`ID` 
                            WHERE u.`USERNAME`=?");
    $stmt->bind_param("s", $s); 
    $stmt->execute(); 
    $r = $stmt->get_result();
    if($r->num_rows) { 
        $row = $r->fetch_assoc();
        $id = $row['ID']; 
        $username = $row['USERNAME']; 
        $fullname = $row['FULLNAME'];
        $email = $row['EMAIL']; 
        $usertype = $row['USERTYPE'];
        $message = "Record loaded.";
    } else {
        $message = "Not found.";
    }
}

// --- Save (Create New User) ---
if(canManage() && isset($_POST['btn_save'])) {
    $username = trim($_POST['username']); 
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']); 
    $usertype = trim($_POST['usertype']); 
    $pw = trim($_POST['password']);
    
    if(!in_array($usertype, $allowed_types)) { 
        $message = "Invalid user type."; 
    } else {
        $sid = $conn->prepare("SELECT `ID` FROM `tbl_usertype` WHERE `USERTYPE`=?");
        $sid->bind_param("s", $usertype); 
        $sid->execute(); 
        $utid = $sid->get_result()->fetch_assoc()['ID'];
        
        $hash = password_hash($pw, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO `tbl_users` (`USERNAME`,`FULLNAME`,`EMAIL`,`USERTYPEID`,`PASSWORD`) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssis", $username, $fullname, $email, $utid, $hash);
        
        if($stmt->execute()) {
            // ✅ Handle image upload
            if (!empty($_FILES['imageUpload']['name'])) {
                $targetFilePath = $targetDir . $username . ".JPG";
                $fileType = strtolower(pathinfo($_FILES["imageUpload"]["name"], PATHINFO_EXTENSION));
                
                if (in_array($fileType, $allowTypes)) {
                    if (!move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $targetFilePath)) {
                        $message .= " Warning: Failed to upload image.";
                    }
                } else {
                    $message .= " Warning: Invalid image type. Use JPG, PNG, or GIF.";
                }
            }
            
            $message = "Saved."; 
            $id = $username = $fullname = $email = $usertype = "";
        } else {
            $message = "Save failed.";
        }
    }
}

// --- Update User ---
if(canManage() && isset($_POST['btn_update'])) {
    $id = $_POST['txt_id'];
    $username = trim($_POST['username']); 
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']); 
    $usertype = trim($_POST['usertype']); 
    $pw = trim($_POST['password']);
    
    if(!in_array($usertype, $allowed_types)) { 
        $message = "Invalid user type."; 
    } else {
        $sid = $conn->prepare("SELECT `ID` FROM `tbl_usertype` WHERE `USERTYPE`=?");
        $sid->bind_param("s", $usertype); 
        $sid->execute(); 
        $utid = $sid->get_result()->fetch_assoc()['ID'];
        
        if($pw) {
            $hash = password_hash($pw, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE `tbl_users` SET `USERNAME`=?,`FULLNAME`=?,`EMAIL`=?,`USERTYPEID`=?,`PASSWORD`=? WHERE `ID`=?");
            $stmt->bind_param("sssisi", $username, $fullname, $email, $utid, $hash, $id);
        } else {
            $stmt = $conn->prepare("UPDATE `tbl_users` SET `USERNAME`=?,`FULLNAME`=?,`EMAIL`=?,`USERTYPEID`=? WHERE `ID`=?");
            $stmt->bind_param("sssii", $username, $fullname, $email, $utid, $id);
        }
        
        if($stmt->execute()) {
            // ✅ Handle image upload on update
            if (!empty($_FILES['imageUpload']['name'])) {
                $targetFilePath = $targetDir . $username . ".JPG";
                $fileType = strtolower(pathinfo($_FILES["imageUpload"]["name"], PATHINFO_EXTENSION));
                
                if (in_array($fileType, $allowTypes)) {
                    if (!move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $targetFilePath)) {
                        $message .= " Warning: Failed to upload image.";
                    }
                } else {
                    $message .= " Warning: Invalid image type. Use JPG, PNG, or GIF.";
                }
            }
            
            $message = "Updated.";
        } else {
            $message = "Update failed.";
        }
    }
}

// --- Delete User ---
if(canManage() && isset($_POST['btn_delete'])) {
    $id = $_POST['txt_id'];
    // Get username before delete to remove image
    $stmt = $conn->prepare("SELECT `USERNAME` FROM `tbl_users` WHERE `ID` = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $r = $stmt->get_result();
    $row = $r->fetch_assoc();
    $uname = $row['USERNAME'] ?? '';
    
    $stmt = $conn->prepare("DELETE FROM `tbl_users` WHERE `ID` = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) {
        // ✅ Delete user image
        $imgPath = $targetDir . $uname . ".JPG";
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }
        $message = "Deleted."; 
        $id = $username = $fullname = $email = $usertype = "";
    } else {
        $message = "Delete failed.";
    }
}
?>
<div class="page-card">
    <h1>👤 Users Management</h1>
    <?php if(!canManage()): ?><p class="note">📝 Only Administrators can manage users.</p><?php endif; ?>
    <?php if($message): ?><div class="message-box"><?= $message ?></div><?php endif; ?>
    
    <!-- Search Form -->
    <form method="POST">
        <label>Search Username</label>
        <input type="text" name="search_username" class="form-input" placeholder="Username">
        <button type="submit" name="btn_search" class="btn-orange" <?= canManage() ? '' : 'disabled' ?>>Search</button>
        <hr>
    </form>
    
    <!-- Main Form with Image Upload ✅ enctype is REQUIRED -->
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="txt_id" value="<?= htmlspecialchars($id) ?>">
        
        <!-- ✅ Profile Preview -->
        <?php if ($username): ?>
        <div style="margin: 10px 0;">
            <img src="<?= $targetDir . htmlspecialchars($username) ?>.JPG?<?= time() ?>" 
                 alt="Profile Photo" 
                 style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 2px solid #ddd;"
                 onerror="this.src='images/default-avatar.jpg'">
        </div>
        <?php endif; ?>
        
        <label>Username *</label>
        <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" class="form-input" required <?= canManage() ? '' : 'readonly' ?>>
        
        <label>Full Name *</label>
        <input type="text" name="fullname" value="<?= htmlspecialchars($fullname) ?>" class="form-input" required <?= canManage() ? '' : 'readonly' ?>>
        
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" class="form-input" <?= canManage() ? '' : 'readonly' ?>>
        
        <label>User Type *</label>
        <select name="usertype" class="form-input" required <?= canManage() ? '' : 'disabled' ?>>
            <option value="">— Select —</option>
            <?php foreach($allowed_types as $t): ?>
            <option value="<?= $t ?>" <?= $usertype === $t ? 'selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
        </select>
        
        <label>Password <?= $id ? '(leave blank to keep)' : '*' ?></label>
        <input type="text" name="password" class="form-input" <?= canManage() ? ($id ? '' : 'required') : 'readonly' ?>>
        
        <!-- ✅ Image Upload Field -->
        <label>Profile Image</label>
        <input type="file" name="imageUpload" accept=".jpg,.jpeg,.png,.gif" class="form-input" <?= canManage() ? '' : 'disabled' ?>>
        <small>Allowed: JPG, PNG, GIF — Saved as: <code>Username.JPG</code></small>
        
        <div class="btn-row" style="margin-top: 15px;">
            <button type="submit" name="btn_save" class="btn-green" <?= canManage() ? '' : 'disabled' ?>>Save</button>
            <button type="submit" name="btn_update" class="btn-blue" <?= $id && canManage() ? '' : 'disabled' ?>>Update</button>
            <button type="submit" name="btn_delete" class="btn-red" <?= $id && canManage() ? '' : 'disabled' ?> onclick="return confirm('Delete user and their image?')">Delete</button>
        </div>
    </form>
    
    <!-- User List Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>User Type</th>
            </tr>
        </thead>
        <tbody>
            <?php $r = $conn->query("SELECT u.`USERNAME`,u.`FULLNAME`,u.`EMAIL`,ut.`USERTYPE` 
                                    FROM `tbl_users` u LEFT JOIN `tbl_usertype` ut ON u.`USERTYPEID`=ut.`ID` 
                                    ORDER BY u.`ID`");
            while($row = $r->fetch_assoc()): ?>
            <tr>
                <td>
                    <img src="<?= $targetDir . htmlspecialchars($row['USERNAME']) ?>.JPG?<?= time() ?>" 
                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;"
                         onerror="this.src='images/default-avatar.jpg'">
                </td>
                <td><?= htmlspecialchars($row['USERNAME']) ?></td>
                <td><?= htmlspecialchars($row['FULLNAME']) ?></td>
                <td><?= htmlspecialchars($row['EMAIL'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['USERTYPE']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br><a href="index.php" class="back-btn">Back to Dashboard</a>
</div>
<?php require 'footer.php'; ?>