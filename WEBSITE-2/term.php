<?php
require 'header.php';

$message = ""; $id = $term = "";

if(isset($_POST['btn_search'])) {
    $s = trim($_POST['search_term']);
    $stmt = $conn->prepare("SELECT * FROM `tbl_term` WHERE `TERM` = ?");
    $stmt->bind_param("s", $s); $stmt->execute(); $r = $stmt->get_result();
    if($r->num_rows) { $row = $r->fetch_assoc(); $id = $row['ID']; $term = $row['TERM']; }
    else $message = "Not found.";
}

if(canSave() && isset($_POST['btn_save'])) {
    $term = trim($_POST['term']);
    $stmt = $conn->prepare("INSERT INTO `tbl_term` (`TERM`) VALUES (?)");
    $stmt->bind_param("s", $term);
    if($stmt->execute()) { $message = "Saved."; $id = $term = ""; }
    else $message = "Save failed.";
}

if(canManage() && isset($_POST['btn_update'])) {
    $id = $_POST['txt_id']; $term = trim($_POST['term']);
    $stmt = $conn->prepare("UPDATE `tbl_term` SET `TERM` = ? WHERE `ID` = ?");
    $stmt->bind_param("si", $term, $id);
    if($stmt->execute()) $message = "Updated.";
    else $message = "Update failed.";
}

if(canManage() && isset($_POST['btn_delete'])) {
    $id = $_POST['txt_id'];
    $stmt = $conn->prepare("DELETE FROM `tbl_term` WHERE `ID` = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { $message = "Deleted."; $id = $term = ""; }
    else $message = "Delete failed.";
}
?>

<div class="page-card">
    <h1>Term Management</h1>

    <form method="POST">
        <label>Search Term</label>
        <input type="text" name="search_term" class="form-input" placeholder="1st Semester">
        <button type="submit" name="btn_search" class="btn-orange">Search</button>
        <hr>
    </form>

    <form method="POST">
        <input type="hidden" name="txt_id" value="<?= htmlspecialchars($id) ?>">
        <label>Term</label>
        <input type="text" name="term" value="<?= htmlspecialchars($term) ?>" class="form-input" required>
        <div class="btn-row">
            <button type="submit" name="btn_save" class="btn-green">Save</button>
            <button type="submit" name="btn_update" class="btn-blue" <?= $id && canManage() ? '' : 'disabled' ?>>Update</button>
            <button type="submit" name="btn_delete" class="btn-red" <?= $id && canManage() ? '' : 'disabled' ?> onclick="return confirm('Delete?')">Delete</button>
        </div>
    </form>

    <table class="data-table">
        <thead><tr><th>ID</th><th>Term</th></tr></thead>
        <tbody>
            <?php $r = $conn->query("SELECT * FROM `tbl_term` ORDER BY `ID`");
            while($row = $r->fetch_assoc()): ?>
            <tr><td><?= $row['ID'] ?></td><td><?= htmlspecialchars($row['TERM']) ?></td></tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br><a href="index.php" class="back-btn">Back to Home</a>
</div>
<?php require 'footer.php'; ?>