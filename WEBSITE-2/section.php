<?php
require 'header.php';

$message = ""; $id = $section = "";

if(isset($_POST['btn_search'])) {
    $s = trim($_POST['search_section']);
    $stmt = $conn->prepare("SELECT * FROM `tbl_section` WHERE `SECTION` = ?");
    $stmt->bind_param("s", $s); $stmt->execute(); $r = $stmt->get_result();
    if($r->num_rows) { $row = $r->fetch_assoc(); $id = $row['ID']; $section = $row['SECTION']; }
    else $message = "Not found.";
}

if(canSave() && isset($_POST['btn_save'])) {
    $section = trim($_POST['section']);
    $stmt = $conn->prepare("INSERT INTO `tbl_section` (`SECTION`) VALUES (?)");
    $stmt->bind_param("s", $section);
    if($stmt->execute()) { $message = "Saved."; $id = $section = ""; }
    else $message = "Save failed.";
}

if(canManage() && isset($_POST['btn_update'])) {
    $id = $_POST['txt_id']; $section = trim($_POST['section']);
    $stmt = $conn->prepare("UPDATE `tbl_section` SET `SECTION` = ? WHERE `ID` = ?");
    $stmt->bind_param("si", $section, $id);
    if($stmt->execute()) $message = "Updated.";
    else $message = "Update failed.";
}

if(canManage() && isset($_POST['btn_delete'])) {
    $id = $_POST['txt_id'];
    $stmt = $conn->prepare("DELETE FROM `tbl_section` WHERE `ID` = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { $message = "Deleted."; $id = $section = ""; }
    else $message = "Delete failed.";
}
?>

<div class="page-card">
    <h1>Section Management</h1>
    
    <form method="POST">
        <label>Search Section</label>
        <input type="text" name="search_section" class="form-input" placeholder="A">
        <button type="submit" name="btn_search" class="btn-orange">Search</button>
        <hr>
    </form>

    <form method="POST">
        <input type="hidden" name="txt_id" value="<?= htmlspecialchars($id) ?>">
        <label>Section</label>
        <input type="text" name="section" value="<?= htmlspecialchars($section) ?>" class="form-input" required>
        <div class="btn-row">
            <button type="submit" name="btn_save" class="btn-green">Save</button>
            <button type="submit" name="btn_update" class="btn-blue" <?= $id && canManage() ? '' : 'disabled' ?>>Update</button>
            <button type="submit" name="btn_delete" class="btn-red" <?= $id && canManage() ? '' : 'disabled' ?> onclick="return confirm('Delete?')">Delete</button>
        </div>
    </form>

    <table class="data-table">
        <thead><tr><th>ID</th><th>Section</th></tr></thead>
        <tbody>
            <?php $r = $conn->query("SELECT * FROM `tbl_section` ORDER BY `ID`");
            while($row = $r->fetch_assoc()): ?>
            <tr><td><?= $row['ID'] ?></td><td><?= htmlspecialchars($row['SECTION']) ?></td></tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br><a href="index.php" class="back-btn">Back to Home</a>
</div>
<?php require 'footer.php'; ?>