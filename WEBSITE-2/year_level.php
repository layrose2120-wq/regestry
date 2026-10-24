<?php
require 'header.php';

$message = ""; $id = $yearlevel = "";

if(isset($_POST['btn_search'])) {
    $s = trim($_POST['search_yearlevel']);
    $stmt = $conn->prepare("SELECT * FROM `tbl_yearlevel` WHERE `YEARLEVEL` = ?");
    $stmt->bind_param("s", $s); $stmt->execute(); $r = $stmt->get_result();
    if($r->num_rows) { $row = $r->fetch_assoc(); $id = $row['ID']; $yearlevel = $row['YEARLEVEL']; }
    else $message = "Not found.";
}

if(canSave() && isset($_POST['btn_save'])) {
    $yearlevel = trim($_POST['yearlevel']);
    $stmt = $conn->prepare("INSERT INTO `tbl_yearlevel` (`YEARLEVEL`) VALUES (?)");
    $stmt->bind_param("s", $yearlevel);
    if($stmt->execute()) { $message = "Saved."; $id = $yearlevel = ""; }
    else $message = "Save failed.";
}

if(canManage() && isset($_POST['btn_update'])) {
    $id = $_POST['txt_id']; $yearlevel = trim($_POST['yearlevel']);
    $stmt = $conn->prepare("UPDATE `tbl_yearlevel` SET `YEARLEVEL` = ? WHERE `ID` = ?");
    $stmt->bind_param("si", $yearlevel, $id);
    if($stmt->execute()) $message = "Updated.";
    else $message = "Update failed.";
}

if(canManage() && isset($_POST['btn_delete'])) {
    $id = $_POST['txt_id'];
    $stmt = $conn->prepare("DELETE FROM `tbl_yearlevel` WHERE `ID` = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { $message = "Deleted."; $id = $yearlevel = ""; }
    else $message = "Delete failed.";
}
?>

<div class="page-card">
    <h1>Year Level Management</h1>

    <form method="POST">
        <label>Search Year Level</label>
        <input type="text" name="search_yearlevel" class="form-input" placeholder="1st Year">
        <button type="submit" name="btn_search" class="btn-orange">Search</button>
        <hr>
    </form>

    <form method="POST">
        <input type="hidden" name="txt_id" value="<?= htmlspecialchars($id) ?>">
        <label>Year Level</label>
        <input type="text" name="yearlevel" value="<?= htmlspecialchars($yearlevel) ?>" class="form-input" required>
        <div class="btn-row">
            <button type="submit" name="btn_save" class="btn-green">Save</button>
            <button type="submit" name="btn_update" class="btn-blue" <?= $id && canManage() ? '' : 'disabled' ?>>Update</button>
            <button type="submit" name="btn_delete" class="btn-red" <?= $id && canManage() ? '' : 'disabled' ?> onclick="return confirm('Delete?')">Delete</button>
        </div>
    </form>

    <table class="data-table">
        <thead><tr><th>ID</th><th>Year Level</th></tr></thead>
        <tbody>
            <?php $r = $conn->query("SELECT * FROM `tbl_yearlevel` ORDER BY `ID`");
            while($row = $r->fetch_assoc()): ?>
            <tr><td><?= $row['ID'] ?></td><td><?= htmlspecialchars($row['YEARLEVEL']) ?></td></tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br><a href="index.php" class="back-btn">Back to Home</a>
</div>
<?php require 'footer.php'; ?>