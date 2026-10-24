<?php
require 'config.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$usertypeid = $_SESSION['usertypeid'] ?? 0;
$can_access = in_array($usertypeid, [1, 2, 3, 4]); // Admin, Registrar, Dean, Instructor

if (!$can_access) {
    echo "<h3>Access Denied</h3>You do not have permission to view this page.";
    exit;
}
?>
<?php require 'header.php'; ?>
<div class="page-card">
    <h1>👨‍🏫 Instructors Management</h1>

    <form method="POST" action="save_instructor.php" enctype="multipart/form-data" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;margin-bottom:30px;">
        <div>
            <label>Salutation</label>
            <input type="text" name="salutation" placeholder="Mr., Ms., Dr., etc." required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:5px;">
        </div>
        <div>
            <label>First Name</label>
            <input type="text" name="firstname" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:5px;">
        </div>
        <div>
            <label>Middle Name</label>
            <input type="text" name="middlename" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:5px;">
        </div>
        <div>
            <label>Last Name</label>
            <input type="text" name="lastname" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:5px;">
        </div>
        <div>
            <label>Photo</label>
            <input type="file" name="photo" accept="image/*" style="width:100%;padding:8px 0;">
        </div>
        <div style="display:flex;align-items:flex-end;">
            <button type="submit" style="padding:10px 24px;background:#2563eb;color:white;border:none;border-radius:5px;cursor:pointer;">Save Instructor</button>
        </div>
    </form>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Salutation</th>
                <th>Full Name</th>
            </tr>
        </thead>
        <tbody>
<?php
$res = $conn->query("SELECT * FROM `tbl_instructors` ORDER BY `ID` DESC");
while ($row = $res->fetch_assoc()):
    $fullName = trim($row['FIRSTNAME'] . ' ' . $row['MIDDLENAME'] . ' ' . $row['LASTNAME']);
    $photo = !empty($row['PHOTO']) && file_exists("uploads/" . $row['PHOTO'])
        ? "uploads/" . $row['PHOTO']
        : "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjZGRkZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMjAiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuMyVhIiBmaWxsPSIjNjY2Ij7igJ88L3RleHQ+PC9zdmc+";
?>
            <tr>
                <td><?= $row['ID'] ?></td>
                <td><img src="<?= $photo ?>" style="width:40px;height:40px;object-fit:cover;border-radius:50%;background:#eee;"></td>
                <td><?= htmlspecialchars($row['SALUTATION']) ?></td>
                <td><?= htmlspecialchars($row['SALUTATION'] . ' ' . $fullName) ?></td>
            </tr>
<?php endwhile; ?>
        </tbody>
    </table>
</div>
<style>
label { font-size:14px; font-weight:500; color:#374151; display:block; margin-bottom:5px; }
</style>
<?php require 'footer.php'; ?>