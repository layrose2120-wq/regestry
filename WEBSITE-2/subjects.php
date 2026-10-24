<?php
require 'header.php';

$message = ""; $id = "";
$course = $yearlevel = $term = $code = $title = $lecture = $laboratory = $credit = $grade = $prerequisite = "";

if(isset($_POST['btn_search'])) {
    $s = trim($_POST['search_code']);
    $stmt = $conn->prepare("SELECT * FROM `tbl_subject` WHERE `CODE` = ?");
    $stmt->bind_param("s", $s); $stmt->execute(); $r = $stmt->get_result();
    if($r->num_rows) {
        $row = $r->fetch_assoc();
        $id = $row['ID'];
        $course = $row['COURSE']; $yearlevel = $row['YEARLEVEL']; $term = $row['TERM'];
        $code = $row['CODE']; $title = $row['TITLE']; $lecture = $row['LECTURE'];
        $laboratory = $row['LABORATORY']; $credit = $row['CREDIT']; $grade = $row['GRADE'];
        $prerequisite = $row['PER_REQUISITE'];
        $message = "Record loaded.";
    } else $message = "Not found.";
}

if(canSave() && isset($_POST['btn_save'])) {
    $course = trim($_POST['course']); $yearlevel = trim($_POST['yearlevel']); $term = trim($_POST['term']);
    $code = trim($_POST['code']); $title = trim($_POST['title']); $lecture = trim($_POST['lecture']);
    $laboratory = trim($_POST['laboratory']); $credit = trim($_POST['credit']); $grade = trim($_POST['grade']);
    $prerequisite = trim($_POST['prerequisite']);
    
    $stmt = $conn->prepare("INSERT INTO `tbl_subject` 
        (`COURSE`,`YEARLEVEL`,`TERM`,`CODE`,`TITLE`,`LECTURE`,`LABORATORY`,`CREDIT`,`GRADE`,`PER_REQUISITE`)
        VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssssss", $course, $yearlevel, $term, $code, $title, $lecture, $laboratory, $credit, $grade, $prerequisite);
    if($stmt->execute()) { 
        $message = "Saved."; 
        $id = $course = $yearlevel = $term = $code = $title = $lecture = $laboratory = $credit = $grade = $prerequisite = ""; 
    } else $message = "Save failed: " . $conn->error;
}

if(canManage() && isset($_POST['btn_update'])) {
    $id = $_POST['txt_id'];
    $course = trim($_POST['course']); $yearlevel = trim($_POST['yearlevel']); $term = trim($_POST['term']);
    $code = trim($_POST['code']); $title = trim($_POST['title']); $lecture = trim($_POST['lecture']);
    $laboratory = trim($_POST['laboratory']); $credit = trim($_POST['credit']); $grade = trim($_POST['grade']);
    $prerequisite = trim($_POST['prerequisite']);
    
    $stmt = $conn->prepare("UPDATE `tbl_subject` SET
        `COURSE`=?,`YEARLEVEL`=?,`TERM`=?,`CODE`=?,`TITLE`=?,`LECTURE`=?,`LABORATORY`=?,`CREDIT`=?,`GRADE`=?,`PER_REQUISITE`=?
        WHERE `ID`=?");
    $stmt->bind_param("sssssssssi", $course, $yearlevel, $term, $code, $title, $lecture, $laboratory, $credit, $grade, $prerequisite, $id);
    if($stmt->execute()) $message = "Updated.";
    else $message = "Update failed.";
}

if(canManage() && isset($_POST['btn_delete'])) {
    $id = $_POST['txt_id'];
    $stmt = $conn->prepare("DELETE FROM `tbl_subject` WHERE `ID` = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { $message = "Deleted."; $id = ""; }
    else $message = "Delete failed.";
}
?>

<div class="page-card">
    <h1>Subjects Management</h1>

    <form method="POST">
        <label>Search Subject Code</label>
        <input type="text" name="search_code" class="form-input" placeholder="CS101">
        <button type="submit" name="btn_search" class="btn-orange">Search</button>
        <hr>
    </form>

    <form method="POST">
        <input type="hidden" name="txt_id" value="<?= htmlspecialchars($id) ?>">
        <label>Course</label>
        <input type="text" name="course" value="<?= htmlspecialchars($course) ?>" class="form-input" required>
        <label>Year Level</label>
        <input type="text" name="yearlevel" value="<?= htmlspecialchars($yearlevel) ?>" class="form-input" required>
        <label>Term</label>
        <input type="text" name="term" value="<?= htmlspecialchars($term) ?>" class="form-input" required>
        <label>Code</label>
        <input type="text" name="code" value="<?= htmlspecialchars($code) ?>" class="form-input" required>
        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" class="form-input" required>
        <label>Lecture</label>
        <input type="number" name="lecture" value="<?= htmlspecialchars($lecture) ?>" class="form-input" required>
        <label>Laboratory</label>
        <input type="number" name="laboratory" value="<?= htmlspecialchars($laboratory) ?>" class="form-input" required>
        <label>Credit</label>
        <input type="number" name="credit" value="<?= htmlspecialchars($credit) ?>" class="form-input" required>
        <label>Grade</label>
        <input type="text" name="grade" value="<?= htmlspecialchars($grade) ?>" class="form-input">
        <label>Prerequisite</label>
        <input type="text" name="prerequisite" value="<?= htmlspecialchars($prerequisite) ?>" class="form-input">
        
        <div class="btn-row">
            <button type="submit" name="btn_save" class="btn-green">Save</button>
            <button type="submit" name="btn_update" class="btn-blue" <?= $id && canManage() ? '' : 'disabled' ?>>Update</button>
            <button type="submit" name="btn_delete" class="btn-red" <?= $id && canManage() ? '' : 'disabled' ?> onclick="return confirm('Delete?')">Delete</button>
        </div>
    </form>

    <table class="data-table">
        <thead><tr><th>Code</th><th>Title</th><th>Course</th><th>Year Level</th><th>Term</th><th>Credit</th></tr></thead>
        <tbody>
            <?php $r = $conn->query("SELECT * FROM `tbl_subject` ORDER BY `ID`");
            while($row = $r->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['CODE']) ?></td>
                <td><?= htmlspecialchars($row['TITLE']) ?></td>
                <td><?= htmlspecialchars($row['COURSE']) ?></td>
                <td><?= htmlspecialchars($row['YEARLEVEL']) ?></td>
                <td><?= htmlspecialchars($row['TERM']) ?></td>
                <td><?= htmlspecialchars($row['CREDIT']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br><a href="index.php" class="back-btn">Back to Home</a>
</div>
<?php require 'footer.php'; ?>