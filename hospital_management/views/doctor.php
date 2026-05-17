<?php $user = $_SESSION['user']; $isEdit = !empty($editing); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Doctor Dashboard &mdash; Hospital Management</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="app-body">

<!-- Navbar -->
<header class="navbar">
    <div class="navbar-inner">
        <a class="brand" href="index.php?page=doctor">
            <span class="brand-icon">&#127973;</span>
            <span>HospitalSys</span>
        </a>
        <div class="nav-user">
            <span class="user-pill">
                <span class="user-avatar"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                <span class="user-meta">
                    <span class="user-name"><?= htmlspecialchars($user['name']) ?></span>
                    <span class="user-role">Doctor</span>
                </span>
            </span>
            <a href="index.php?page=logout" class="btn-logout">Logout</a>
        </div>
    </div>
</header>

<main class="main-content">
    <div class="page-header">
        <div>
            <h1 class="page-title">Manage Patients</h1>
            <p class="page-sub">Add, edit, search and remove patient records</p>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <?php $messages = ['added'   => 'Patient added successfully.',
                           'updated' => 'Patient updated successfully.',
                           'deleted' => 'Patient deleted successfully.'];
              $msg = $messages[$_GET['msg']] ?? null; ?>
        <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- ============ Add / Edit Form ============ -->
    <div class="card form-card">
        <h3 class="card-title">
            <?= $isEdit ? '&#9998; Edit Patient (#' . intval($editing['id']) . ')' : '+ Add New Patient' ?>
        </h3>
        <form method="POST"
              action="index.php?page=doctor&action=<?= $isEdit ? 'update&id=' . intval($editing['id']) : 'add' ?>"
              class="form" novalidate>
            <div class="field-row">
                <div class="field">
                    <label for="name">Patient Name</label>
                    <input type="text" id="name" name="name"
                           value="<?= htmlspecialchars($editing['name'] ?? '') ?>"
                           placeholder="e.g. Rahim Uddin" required>
                </div>
                <div class="field">
                    <label for="disease">Disease</label>
                    <input type="text" id="disease" name="disease"
                           value="<?= htmlspecialchars($editing['disease'] ?? '') ?>"
                           placeholder="e.g. Diabetes" required>
                </div>
            </div>
            <div class="field-row">
                <div class="field">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" min="0"
                           value="<?= htmlspecialchars($editing['age'] ?? '') ?>"
                           placeholder="e.g. 35" required>
                </div>
                <div class="field">
                    <label for="fee">Consultation Fee (&#2547;)</label>
                    <input type="number" id="fee" name="fee" step="0.01" min="0"
                           value="<?= htmlspecialchars($editing['fee'] ?? '') ?>"
                           placeholder="0.00" required>
                </div>
            </div>
            <div class="form-actions">
                <?php if ($isEdit): ?>
                    <a href="index.php?page=doctor" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Patient</button>
                <?php else: ?>
                    <button type="submit" class="btn btn-primary">Save Patient</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- ============ Patients Table ============ -->
    <div class="card">
        <div class="card-toolbar">
            <div class="search-wrap">
                <span class="search-icon">&#128269;</span>
                <input type="text" id="searchInput" class="search-input"
                       placeholder="Search by name or disease...">
            </div>
            <span class="badge" id="resultCount"><?= count($patients) ?> total</span>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient Name</th>
                        <th>Disease</th>
                        <th>Age</th>
                        <th>Fee (&#2547;)</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (empty($patients)): ?>
                        <tr><td colspan="6" class="empty">No patients yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($patients as $i => $patient): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($patient['name']) ?></td>
                                <td><?= htmlspecialchars($patient['disease']) ?></td>
                                <td><?= htmlspecialchars($patient['age']) ?></td>
                                <td>&#2547;<?= number_format($patient['fee'], 2) ?></td>
                                <td class="text-right">
                                    <a class="btn-sm btn-edit"
                                       href="index.php?page=doctor&action=edit&id=<?= $patient['id'] ?>">Edit</a>
                                    <a class="btn-sm btn-delete"
                                       href="index.php?page=doctor&action=delete&id=<?= $patient['id'] ?>"
                                       onclick="return confirm('Delete this patient?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<footer class="footer">&copy; <?= date('Y') ?> Hospital Management System</footer>

<!-- =========== Inline AJAX search =========== -->
<script>
(function () {
    var input   = document.getElementById('searchInput');
    var body    = document.getElementById('tableBody');
    var counter = document.getElementById('resultCount');
    var timer;

    function esc(s) {
        return String(s == null ? '' : s)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    function render(rows) {
        if (!rows.length) {
            body.innerHTML = '<tr><td colspan="6" class="empty">No matching results.</td></tr>';
            counter.textContent = '0 results';
            return;
        }
        var html = '';
        rows.forEach(function (p, i) {
            html +=
                '<tr>' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td>' + esc(p.name) + '</td>' +
                    '<td>' + esc(p.disease) + '</td>' +
                    '<td>' + esc(p.age) + '</td>' +
                    '<td>&#2547;' + parseFloat(p.fee).toFixed(2) + '</td>' +
                    '<td class="text-right">' +
                        '<a class="btn-sm btn-edit" href="index.php?page=doctor&action=edit&id=' + p.id + '">Edit</a>' +
                        '<a class="btn-sm btn-delete" href="index.php?page=doctor&action=delete&id=' + p.id +
                        '" onclick="return confirm(\'Delete this patient?\')">Delete</a>' +
                    '</td>' +
                '</tr>';
        });
        body.innerHTML = html;
        counter.textContent = rows.length + (input.value.trim() ? ' results' : ' total');
    }

    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
            fetch('index.php?page=ajax&type=patient&q=' + encodeURIComponent(input.value.trim()),
                  { credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .then(render)
                .catch(function (e) { console.error(e); });
        }, 200);
    });
})();
</script>

</body>
</html>
