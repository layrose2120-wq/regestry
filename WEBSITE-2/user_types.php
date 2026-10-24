<?php
require 'header.php';

$message = ""; $id = $usertype = "";

if (!canManage()) {
    echo "<div class='page-card'><h3>⛔ Access Denied</h3><p>Only Administrators can manage user types.</p><a href='index.php' class='back-btn'>Back to Dashboard</a></div>";
    require 'footer.php';
    exit;
}

if(isset($_POST['btn_search'])) {
    $s = trim($_POST['search_usertype']);
    $stmt = $conn->prepare("SELECT * FROM `tbl_usertype` WHERE `USERTYPE` = ?");
    $stmt->bind_param("s", $s);
    $stmt->execute(); $r = $stmt->get_result();
    if($r->num_rows) { $row = $r->fetch_assoc(); $id = $row['ID']; $usertype = $row['USERTYPE']; }
    else $message = "Not found.";
}

if(isset($_POST['btn_save'])) {
    $usertype = trim($_POST['usertype']);
    $stmt = $conn->prepare("INSERT INTO `tbl_usertype` (`USERTYPE`) VALUES (?)");
    $stmt->bind_param("s", $usertype);
    if($stmt->execute()) { $message = "✅ Saved."; $id = $usertype = ""; }
    else $message = "Save failed: " . $conn->error;
}

if(isset($_POST['btn_update'])) {
    $id = $_POST['txt_id']; $usertype = trim($_POST['usertype']);
    $stmt = $conn->prepare("UPDATE `tbl_usertype` SET `USERTYPE` = ? WHERE `ID` = ?");
    $stmt->bind_param("si", $usertype, $id);
    if($stmt->execute()) $message = "Updated.";
    else $message = "Update failed.";
}

if(isset($_POST['btn_delete'])) {
    $id = $_POST['txt_id'];
    $stmt = $conn->prepare("DELETE FROM `tbl_usertype` WHERE `ID` = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { $message = "Deleted."; $id = $usertype = ""; }
    else $message = "Delete failed.";
}

$list = $conn->query("SELECT * FROM `tbl_usertype` ORDER BY `ID`");
?>

<div class="page-card">
    <h1>User Types Management</h1>
    <?php if($message): ?><div class="message-box"><?= $message ?></div><?php endif; ?>

    <form method="POST">
        <label>Search User Type</label>
        <input type="text" name="search_usertype" class="form-input" placeholder="e.g. Student">
        <button type="submit" name="btn_search" class="btn-orange">Search</button>
        <hr>
    </form>

    <form method="POST">
        <input type="hidden" name="txt_id" value="<?= htmlspecialchars($id) ?>">
        <label>User Type Name</label>
        <input type="text" name="usertype" value="<?= htmlspecialchars($usertype) ?>" class="form-input" required>
        <div class="btn-row">
            <button type="submit" name="btn_save" class="btn-green">Save</button>
            <button type="submit" name="btn_update" class="btn-blue" <?= $id ? '' : 'disabled' ?>>Update</button>
            <button type="submit" name="btn_delete" class="btn-red" <?= $id ? '' : 'disabled' ?> onclick="return confirm('Delete?')">Delete</button>
        </div>
    </form>

    <table class="data-table">
        <thead><tr><th>ID</th><th>User Type</th></tr></thead>
        <tbody>
            <?php while($row = $list->fetch_assoc()): ?>
            <tr><td><?= $row['ID'] ?></td><td><?= htmlspecialchars($row['USERTYPE']) ?></td></tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br><a href="index.php" class="back-btn">Back to Dashboard</a>
</div>
<?php require 'footer.php'; ?>