<?php
define('APP_LOADED', true);
require 'config.php';

if (!isLoggedIn()) {
    header("Location: /WEBSITE/login.php");
    exit;
}

$message = "";
$id = $year = "";

if (isset($_POST['btn_search'])) {
    $s = trim($_POST['search_year']);
    $stmt = $conn->prepare("SELECT * FROM `tbl_schoolyear` WHERE `SCHOOLYEAR` = ?");
    $stmt->bind_param("s", $s);
    $stmt->execute();
    $r = $stmt->get_result();
    if ($r->num_rows) {
        $row = $r->fetch_assoc();
        $id = $row['ID'];
        $year = $row['SCHOOLYEAR'];
    } else {
        $message = "Not found.";
    }
}

if (canSave() && isset($_POST['btn_save'])) {
    $year = trim($_POST['year']);
    if (!empty($year)) {
        $stmt = $conn->prepare("INSERT INTO `tbl_schoolyear` (`SCHOOLYEAR`, `SAVED_BY`) VALUES (?, ?)");
        $stmt->bind_param("si", $year, $current_user_id);
        if ($stmt->execute()) {
            $message = "Saved.";
            $id = $year = "";
        } else {
            $message = "Save failed: " . $conn->error;
        }
    }
}

if (canManage() && isset($_POST['btn_update'])) {
    $id = $_POST['txt_id'];
    $year = trim($_POST['year']);
    $stmt = $conn->prepare("UPDATE `tbl_schoolyear` SET `SCHOOLYEAR` = ? WHERE `ID` = ?");
    $stmt->bind_param("si", $year, $id);
    if ($stmt->execute()) {
        $message = "Updated.";
    } else {
        $message = "Update failed: " . $conn->error;
    }
}

if (canManage() && isset($_POST['btn_delete'])) {
    $id = $_POST['txt_id'];
    $stmt = $conn->prepare("DELETE FROM `tbl_schoolyear` WHERE `ID` = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Deleted.";
        $id = $year = "";
    } else {
        $message = "Delete failed: " . $conn->error;
    }
}

if (isStudent()) {
    $result = $conn->query("SELECT * FROM `tbl_schoolyear` WHERE `SAVED_BY` = $current_user_id ORDER BY `ID`");
} else {
    $result = $conn->query("SELECT * FROM `tbl_schoolyear` ORDER BY `ID`");
}
?>
<?php require 'header.php'; ?>

<div class="page-card">
    <h1>School Year Management</h1>
    
    <?php if ($message): ?>
    <div style="background:#eef2ff; padding:0.75rem; border-radius:6px; margin-bottom:1rem;">
        <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <form method="POST">
        <label>Search School Year</label>
        <input type="text" name="search_year" class="form-input" placeholder="2024-2025">
        <button type="submit" name="btn_search" class="btn-orange">Search</button>
        <hr style="margin:1rem 0;">
    </form>

    <form method="POST">
        <input type="hidden" name="txt_id" value="<?= htmlspecialchars($id) ?>">
        <label>School Year</label>
        <input type="text" name="year" value="<?= htmlspecialchars($year) ?>" class="form-input" required>
        <div class="btn-row" style="margin-top:1rem;">
            <button type="submit" name="btn_save" class="btn-green" <?= !$id ? '' : 'disabled' ?>>Save</button>
            <button type="submit" name="btn_update" class="btn-blue" <?= $id && canManage() ? '' : 'disabled' ?>>Update</button>
            <button type="submit" name="btn_delete" class="btn-red" <?= $id && canManage() ? '' : 'disabled' ?> onclick="return confirm('Delete this record?')">Delete</button>
        </div>
    </form>

    <h3 style="margin-top:2rem;">Records</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>School Year</th>
                <?php if (!isStudent()): ?><th>Saved By</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['ID'] ?></td>
                <td><?= htmlspecialchars($row['SCHOOLYEAR']) ?></td>
                <?php if (!isStudent()): ?><td><?= $row['SAVED_BY'] ?? '-' ?></td><?php endif; ?>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br>
    <a href="/WEBSITE/index.php" class="back-btn">← Back to Home</a>
</div>

<?php require 'footer.php'; ?>