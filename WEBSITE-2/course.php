<?php
require 'header.php';

$message = ""; $id = $course = "";

if(isset($_POST['btn_search'])) {
    $s = trim($_POST['search_course']);
    $stmt = $conn->prepare("SELECT * FROM `tbl_course` WHERE `COURSE` = ?");
    $stmt->bind_param("s", $s); $stmt->execute(); $r = $stmt->get_result();
    if($r->num_rows) { $row = $r->fetch_assoc(); $id = $row['ID']; $course = $row['COURSE']; }
    else $message = "Not found.";
}

if(canSave() && isset($_POST['btn_save'])) {
    $course = trim($_POST['course']);
    $stmt = $conn->prepare("INSERT INTO `tbl_course` (`COURSE`) VALUES (?)");
    $stmt->bind_param("s", $course);
    if($stmt->execute()) { $message = "Saved."; $id = $course = ""; }
    else $message = "Save failed: " . $conn->error;
}

if(canManage() && isset($_POST['btn_update'])) {
    $id = $_POST['txt_id']; $course = trim($_POST['course']);
    $stmt = $conn->prepare("UPDATE `tbl_course` SET `COURSE` = ? WHERE `ID` = ?");
    $stmt->bind_param("si", $course, $id);
    if($stmt->execute()) $message = "Updated.";
    else $message = "Update failed.";
}

if(canManage() && isset($_POST['btn_delete'])) {
    $id = $_POST['txt_id'];
    $stmt = $conn->prepare("DELETE FROM `tbl_course` WHERE `ID` = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { $message = "Deleted."; $id = $course = ""; }
    else $message = "Delete failed.";
}
?>

<div class="page-card">
    <h1>Course Management</h1>

    <form method="POST">
        <label>Search Course</label>
        <input type="text" name="search_course" class="form-input" placeholder="Course Name">
        <button type="submit" name="btn_search" class="btn-orange">Search</button>
        <hr>
    </form>

    <form method="POST">
        <input type="hidden" name="txt_id" value="<?= htmlspecialchars($id) ?>">
        <label>Course Name</label>
        <input type="text" name="course" value="<?= htmlspecialchars($course) ?>" class="form-input" required>
        <div class="btn-row">
            <button type="submit" name="btn_save" class="btn-green">Save</button>
            <button type="submit" name="btn_update" class="btn-blue" <?= $id && canManage() ? '' : 'disabled' ?>>Update</button>
            <button type="submit" name="btn_delete" class="btn-red" <?= $id && canManage() ? '' : 'disabled' ?> onclick="return confirm('Delete?')">Delete</button>
        </div>
    </form>

    <table class="data-table">
        <thead><tr><th>ID</th><th>Course</th></tr></thead>
        <tbody>
            <?php $r = $conn->query("SELECT * FROM `tbl_course` ORDER BY `ID`");
            while($row = $r->fetch_assoc()): ?>
            <tr><td><?= $row['ID'] ?></td><td><?= htmlspecialchars($row['COURSE']) ?></td></tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br><a href="index.php" class="back-btn">Back to Home</a>
</div>
<?php require 'footer.php'; ?>