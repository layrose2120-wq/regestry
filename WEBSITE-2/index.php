<?php
require 'check_access.php';
require 'config.php';

$user_id = $_SESSION['user_id'];
$usertype = $_SESSION['usertype'];

if ($usertype === 'Student') {
    $subjects = $conn->query("SELECT * FROM `tbl_subject` WHERE `SAVED_BY` = $user_id ORDER BY `ID` DESC");
    $courses = $school_years = $terms = $year_levels = $sections = $instructors = null;
} else {
    $courses = can_view('course.php') ? $conn->query("SELECT * FROM `tbl_course` ORDER BY `ID` DESC") : null;
    $school_years = can_view('school_year.php') ? $conn->query("SELECT * FROM `tbl_schoolyear` ORDER BY `ID` DESC") : null;
    $terms = can_view('term.php') ? $conn->query("SELECT * FROM `tbl_term` ORDER BY `ID` DESC") : null;
    $year_levels = can_view('year_level.php') ? $conn->query("SELECT * FROM `tbl_yearlevel` ORDER BY `ID` DESC") : null;
    $sections = can_view('section.php') ? $conn->query("SELECT * FROM `tbl_section` ORDER BY `ID` DESC") : null;
    $subjects = can_view('subjects.php') ? $conn->query("SELECT * FROM `tbl_subject` ORDER BY `ID` DESC") : null;
    $instructors = can_view('instructors.php') ? $conn->query("SELECT * FROM `tbl_instructors` ORDER BY `ID` DESC") : null;
}
?>
<?php require 'header.php'; ?>
<div class="page-card">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['fullname'] ?? 'User') ?>! 👋</h1>

    <?php if ($usertype === 'Student'): ?>
    <div class="note-box">
        📝 Below are the records <strong>you submitted</strong>. Only you and admins can view them.
    </div>
    <?php endif; ?>

    <?php if ($instructors): ?>
    <h2>👨‍🏫 Instructors</h2>
    <?php if ($instructors->num_rows > 0): ?>
    <table class="data-table">
        <thead><tr><th>ID</th><th>Photo</th><th>Salutation</th><th>Full Name</th></tr></thead>
        <tbody>
            <?php while ($row = $instructors->fetch_assoc()): 
                $fullName = trim($row['FIRSTNAME'].' '.$row['MIDDLENAME'].' '.$row['LASTNAME']);
                $photo = !empty($row['PHOTO']) && file_exists("uploads/".$row['PHOTO'])
                    ? "uploads/".$row['PHOTO']
                    : "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjZGRkZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMjAiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuMyVhIiBmaWxsPSIjNjY2Ij7igJ88L3RleHQ+PC9zdmc+";
            ?>
            <tr>
                <td><?= $row['ID'] ?></td>
                <td><img src="<?= $photo ?>" style="width:40px;height:40px;object-fit:cover;border-radius:50%;"></td>
                <td><?= htmlspecialchars($row['SALUTATION']) ?></td>
                <td><?= htmlspecialchars($row['SALUTATION'].' '.$fullName) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="empty-note">No instructors yet. <a href="instructors.php">Add →</a></p>
    <?php endif; ?>
    <?php endif; ?>

    <?php if ($courses): ?>
    <h2>📘 Course</h2>
    <?php if ($courses->num_rows > 0): ?>
    <table class="data-table"><thead><tr><th>ID</th><th>Course Name</th></tr></thead><tbody>
        <?php while ($row = $courses->fetch_assoc()): ?>
        <tr><td><?= $row['ID'] ?></td><td><?= htmlspecialchars($row['COURSE'] ?? '-') ?></td></tr>
        <?php endwhile; ?>
    </tbody></table>
    <?php else: ?><p class="empty-note">No courses yet. <a href="course.php">Add →</a></p><?php endif; ?>
    <?php endif; ?>

    <?php if ($school_years): ?>
    <h2>🏫 School Year</h2>
    <?php if ($school_years->num_rows > 0): ?>
    <table class="data-table"><thead><tr><th>ID</th><th>School Year</th></tr></thead><tbody>
        <?php while ($row = $school_years->fetch_assoc()): ?>
        <tr><td><?= $row['ID'] ?></td><td><?= htmlspecialchars($row['SCHOOLYEAR'] ?? '-') ?></td></tr>
        <?php endwhile; ?>
    </tbody></table>
    <?php else: ?><p class="empty-note">No school year yet. <a href="school_year.php">Add →</a></p><?php endif; ?>
    <?php endif; ?>

    <?php if ($terms): ?>
    <h2>📅 Term</h2>
    <?php if ($terms->num_rows > 0): ?>
    <table class="data-table"><thead><tr><th>ID</th><th>Term</th></tr></thead><tbody>
        <?php while ($row = $terms->fetch_assoc()): ?>
        <tr><td><?= $row['ID'] ?></td><td><?= htmlspecialchars($row['TERM'] ?? '-') ?></td></tr>
        <?php endwhile; ?>
    </tbody></table>
    <?php else: ?><p class="empty-note">No term yet. <a href="term.php">Add →</a></p><?php endif; ?>
    <?php endif; ?>

    <?php if ($year_levels): ?>
    <h2>🎓 Year Level</h2>
    <?php if ($year_levels->num_rows > 0): ?>
    <table class="data-table"><thead><tr><th>ID</th><th>Year Level</th></tr></thead><tbody>
        <?php while ($row = $year_levels->fetch_assoc()): ?>
        <tr><td><?= $row['ID'] ?></td><td><?= htmlspecialchars($row['YEAR_LEVEL'] ?? '-') ?></td></tr>
        <?php endwhile; ?>
    </tbody></table>
    <?php else: ?><p class="empty-note">No year level yet. <a href="year_level.php">Add →</a></p><?php endif; ?>
    <?php endif; ?>

    <?php if ($sections): ?>
    <h2>🏷️ Section</h2>
    <?php if ($sections->num_rows > 0): ?>
    <table class="data-table"><thead><tr><th>ID</th><th>Section</th></tr></thead><tbody>
        <?php while ($row = $sections->fetch_assoc()): ?>
        <tr><td><?= $row['ID'] ?></td><td><?= htmlspecialchars($row['SECTION'] ?? '-') ?></td></tr>
        <?php endwhile; ?>
    </tbody></table>
    <?php else: ?><p class="empty-note">No section yet. <a href="section.php">Add →</a></p><?php endif; ?>
    <?php endif; ?>

    <?php if ($subjects): ?>
    <h2>📝 Subjects</h2>
    <?php if ($subjects->num_rows > 0): ?>
    <table class="data-table wide-table">
        <thead><tr><th>ID</th><th>Code</th><th>Title</th><th>Course</th><th>Year</th><th>Term</th><th>Credit</th></tr></thead>
        <tbody>
            <?php while ($row = $subjects->fetch_assoc()): ?>
            <tr>
                <td><?= $row['ID'] ?></td>
                <td><?= htmlspecialchars($row['CODE'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['TITLE'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['COURSE'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['YEARLEVEL'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['TERM'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['CREDIT'] ?? '-') ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?><p class="empty-note">No subjects yet. <a href="subjects.php">Add →</a></p><?php endif; ?>
    <?php endif; ?>
</div>
<style>
.note-box{background:#eef2ff;padding:1rem;border-radius:8px;margin:1rem 0;}
.empty-note{padding:1.5rem;background:#f9fafb;border-radius:8px;color:#6b7280;}
h2{margin-top:2.5rem;border-bottom:2px solid #e5e7eb;padding-bottom:.5rem;}
.data-table{width:100%;border-collapse:collapse;margin-top:.5rem;}
.data-table th,.data-table td{padding:.75rem .5rem;text-align:left;border-bottom:1px solid #e5e7eb;}
.data-table th{background:#f9fafb;font-weight:600;}
</style>
<?php require 'footer.php'; ?>